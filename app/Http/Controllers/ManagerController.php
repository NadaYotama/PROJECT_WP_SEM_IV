<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Menu;
use App\Models\Table;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:manager');
    }

    public function index()
    {
        // Stats for dashboard cards
        $stats = [
            'today_sales' => Order::whereDate('created_at', today())
                                   ->where('payment_status', 'paid')
                                   ->sum('total'),
            'low_stock_items' => Menu::where('stock', '<', 10)->count(),
            'present_staff' => User::whereIn('role', ['admin', 'kasir', 'manager'])
                                   ->where('is_active', true)->count(), // Simplified, assuming all active staff are present
            'total_staff' => User::whereIn('role', ['admin', 'kasir', 'manager'])->count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
        ];

        // Performance data
        $totalOrdersToday = Order::whereDate('created_at', today())->count();
        $completedOrdersToday = Order::whereDate('created_at', today())
                                     ->where('status', 'completed')->count();
        $currentRevenue = Order::whereDate('created_at', today())
                               ->where('payment_status', 'paid')
                               ->sum('total');
        $targetRevenue = 5000000; // Example target, can be from settings

        $performance = [
            'orders_completed_percentage' => $totalOrdersToday > 0 ? ($completedOrdersToday / $totalOrdersToday) * 100 : 0,
            'orders_completed' => $completedOrdersToday,
            'total_orders_today' => $totalOrdersToday,
            'revenue_percentage' => $targetRevenue > 0 ? min(($currentRevenue / $targetRevenue) * 100, 100) : 0,
            'current_revenue' => $currentRevenue,
            'target_revenue' => $targetRevenue,
        ];

        // Table status
        $tableStatus = [
            'available' => Table::where('status', 'available')->count(),
            'occupied' => Table::where('status', 'occupied')->count(),
            'reserved' => Table::where('status', 'reserved')->count(),
            'maintenance' => Table::where('status', 'maintenance')->count(),
        ];

        // Recent Orders (10 latest)
        $recentOrders = Order::with('user')
                            ->latest()
                            ->limit(10)
                            ->get();

        // Staff Attendance Today (from attendance table)
        $staffAttendance = User::whereIn('role', ['admin', 'kasir', 'manager'])
                              ->where('is_active', true)
                              ->with(['attendances' => function($query) {
                                  $query->where('date', today());
                              }])
                              ->get()
                              ->map(function($user) {
                                  $attendance = $user->attendances->first();
                                  return (object) [
                                      'user' => $user,
                                      'status' => $attendance ? $attendance->status : 'absent',
                                      'check_in_time' => $attendance ? $attendance->check_in_time : null,
                                  ];
                              });

        return view('dashboard.manager', compact('stats', 'performance', 'tableStatus', 'recentOrders', 'staffAttendance'));
    }

    public function settings()
    {
        $settings = [
            'email_orders' => Setting::get('email_orders', 1),
            'email_reservations' => Setting::get('email_reservations', 1),
            'email_reports' => Setting::get('email_reports', 0),
        ];

        return view('dashboard.manager-settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        Setting::set('email_orders', $request->has('email_orders') ? 1 : 0);
        Setting::set('email_reservations', $request->has('email_reservations') ? 1 : 0);
        Setting::set('email_reports', $request->has('email_reports') ? 1 : 0);

        return redirect()->route('manager.settings')->with('success', 'Notification preferences updated successfully.');
    }

    public function menu(Request $request)
    {
        $query = Menu::query();

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        $menuItems = $query->paginate(15);

        // Fetch distinct categories from the database for filter dropdown
        $allowedCategories = Menu::distinct()->pluck('category')->sort()->values()->toArray();

        return view('manager.menu.index', compact('menuItems', 'allowedCategories'));
    }

    public function categories(Request $request)
    {
        $query = Menu::select('category')->distinct();

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->where('category', 'like', '%' . $request->search . '%');
        }

        $categories = $query->paginate(15);

        return view('manager.categories.index', compact('categories'));
    }

    public function inventory(Request $request)
    {
        $query = Menu::query();

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        // Filter by stock status
        if ($request->has('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->where('stock', '<', 10)->where('stock', '>', 0);
                    break;
                case 'out':
                    $query->where('stock', '<=', 0);
                    break;
                case 'available':
                    $query->where('stock', '>=', 10);
                    break;
            }
        }

        $menuItems = $query->paginate(15);

        // Stats
        $stats = [
            'low_stock' => Menu::where('stock', '<', 10)->where('stock', '>', 0)->count(),
            'out_of_stock' => Menu::where('stock', '<=', 0)->count(),
            'available' => Menu::where('stock', '>=', 10)->count(),
            'total_items' => Menu::count(),
        ];

        return view('manager.inventory.index', compact('menuItems', 'stats'));
    }

    public function updateInventory(Request $request, Menu $menu)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
            'adjustment_type' => 'required|in:set,add,subtract',
            'notes' => 'nullable|string|max:255',
        ]);

        $newStock = $request->stock;
        $adjustmentType = $request->adjustment_type;

        if ($adjustmentType === 'add') {
            $menu->stock += $newStock;
        } elseif ($adjustmentType === 'subtract') {
            $menu->stock -= $newStock;
            $menu->stock = max(0, $menu->stock);
        } else {
            $menu->stock = $newStock;
        }

        $menu->save();

        return redirect()->route('manager.inventory.index')->with('success', 'Stok menu berhasil diperbarui.');
    }

    public function bulkUpdateInventory(Request $request)
    {
        $request->validate([
            'menu_ids' => 'required|array',
            'menu_ids.*' => 'exists:menus,id',
            'stocks' => 'required|array',
            'stocks.*' => 'integer|min:0',
        ]);

        $menuIds = $request->menu_ids;
        $stocks = $request->stocks;

        foreach ($menuIds as $menuId) {
            if (isset($stocks[$menuId])) {
                $menu = Menu::find($menuId);
                if ($menu) {
                    $menu->stock = $stocks[$menuId];
                    $menu->save();
                }
            }
        }

        return redirect()->route('manager.inventory.index')->with('success', 'Stok menu berhasil diperbarui secara massal.');
    }

    /**
     * Staff Management
     */
    public function staff(Request $request)
    {
        $query = User::whereIn('role', ['admin', 'kasir', 'manager']);

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        // Filter by role
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }

        $staff = $query->paginate(15);

        return view('manager.staff.index', compact('staff'));
    }

    public function createStaff()
    {
        return view('manager.staff.create');
    }

    public function storeStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,manager,kasir',
            'is_active' => 'required|boolean',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('manager.staff.index')->with('success', 'Staff created successfully.');
    }

    public function showStaff(User $user)
    {
        // Ensure only staff roles can be viewed
        if (!in_array($user->role, ['admin', 'kasir', 'manager'])) {
            abort(404);
        }

        return view('manager.staff.show', compact('user'));
    }

    public function editStaff(User $user)
    {
        // Ensure only staff roles can be edited
        if (!in_array($user->role, ['admin', 'kasir', 'manager'])) {
            abort(404);
        }

        return view('manager.staff.edit', compact('user'));
    }

    public function updateStaff(Request $request, User $user)
    {
        // Ensure only staff roles can be updated
        if (!in_array($user->role, ['admin', 'kasir', 'manager'])) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,manager,kasir',
            'is_active' => 'required|boolean',
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'role', 'is_active']));

        return redirect()->route('manager.staff.index')->with('success', 'Staff updated successfully.');
    }

    public function destroyStaff(User $user)
    {
        // Ensure only staff roles can be deleted
        if (!in_array($user->role, ['admin', 'kasir', 'manager'])) {
            abort(404);
        }

        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return redirect()->route('manager.staff.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('manager.staff.index')->with('success', 'Staff deleted successfully.');
    }

    /**
     * Staff Performance Management
     */
    public function performance(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        // Get all staff
        $staff = User::whereIn('role', ['admin', 'kasir', 'manager'])
                    ->where('is_active', true)
                    ->get();

        // Calculate performance stats
        $performanceStats = [
            'total_staff' => $staff->count(),
            'present_today' => $staff->filter(function($user) {
                return $user->attendances()->where('date', today())->where('status', 'present')->exists();
            })->count(),
            'late_today' => $staff->filter(function($user) {
                return $user->attendances()->where('date', today())->where('status', 'late')->exists();
            })->count(),
            'absent_today' => $staff->filter(function($user) {
                return $user->attendances()->where('date', today())->where('status', 'absent')->exists();
            })->count(),
        ];

        // Calculate working days in the month
        $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();
        $workingDays = 0;
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            if ($date->isWeekday()) {
                $workingDays++;
            }
        }

        // Get performance data for each staff
        $staffPerformance = $staff->map(function($user) use ($month, $year, $workingDays) {
            $attendances = $user->attendances()
                               ->whereMonth('date', $month)
                               ->whereYear('date', $year)
                               ->get();

            $present = $attendances->where('status', 'present')->count();
            $late = $attendances->where('status', 'late')->count();
            $absent = $attendances->where('status', 'absent')->count();
            $halfDay = $attendances->where('status', 'half_day')->count();

            $totalPresent = $present + ($halfDay * 0.5);
            $attendanceRate = $workingDays > 0 ? ($totalPresent / $workingDays) * 100 : 0;

            return [
                'user_id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'working_days' => $workingDays,
                'present' => $present,
                'late' => $late,
                'absent' => $absent,
                'half_day' => $halfDay,
                'attendance_rate' => round($attendanceRate, 1),
            ];
        })->sortByDesc('attendance_rate')->values();

        return view('manager.performance.index', compact('staffPerformance', 'performanceStats'));
    }

    /**
     * Menu Performance Management
     */
    public function menuPerformance(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Get menu performance data
        $menuPerformance = DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                'menus.id',
                'menus.name',
                'menus.category',
                'menus.price',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders')
            )
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->where('orders.payment_status', 'paid')
            ->groupBy('menus.id', 'menus.name', 'menus.category', 'menus.price')
            ->orderBy('total_revenue', 'desc')
            ->get();

        // Calculate performance metrics
        $totalRevenue = $menuPerformance->sum('total_revenue');
        $totalQuantity = $menuPerformance->sum('total_quantity');
        $totalOrders = $menuPerformance->sum('total_orders');

        // Get top performing menus
        $topMenus = $menuPerformance->take(10);

        // Get menu categories performance
        $categoryPerformance = DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                'menus.category',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders')
            )
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->where('orders.payment_status', 'paid')
            ->groupBy('menus.category')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return view('manager.menu-performance.index', compact(
            'menuPerformance',
            'topMenus',
            'categoryPerformance',
            'totalRevenue',
            'totalQuantity',
            'totalOrders',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Reports Management
     */
    public function reports(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $reports = [
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_revenue' => Order::whereBetween('created_at', [$startDate, $endDate])->sum('total'),
            'total_payments' => Payment::whereBetween('created_at', [$startDate, $endDate])->where('status', 'success')->sum('amount'),
            'total_reservations' => Reservation::whereBetween('created_at', [$startDate, $endDate])->count(),
            'popular_menu' => DB::table('order_items')
                ->join('menus', 'order_items.menu_id', '=', 'menus.id')
                ->select('menus.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
                ->whereBetween('order_items.created_at', [$startDate, $endDate])
                ->groupBy('menus.id', 'menus.name')
                ->orderBy('total_quantity', 'desc')
                ->take(10)
                ->get(),
        ];

        return view('manager.reports.index', compact('reports', 'startDate', 'endDate'));
    }

    /**
     * Reservation Report Management
     */
    public function reservationReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Get reservations within date range
        $reservations = Reservation::with(['user', 'table'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate reservation statistics
        $reports = [
            'total_reservations' => $reservations->count(),
            'confirmed_reservations' => $reservations->where('status', 'confirmed')->count(),
            'pending_reservations' => $reservations->where('status', 'pending')->count(),
            'cancelled_reservations' => $reservations->where('status', 'cancelled')->count(),
            'daily_reservations' => Reservation::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy(DB::raw('DATE(created_at)'))
                ->get(),
        ];

        return view('manager.reservation-report.index', compact('reports', 'reservations', 'startDate', 'endDate'));
    }

    /**
     * Revenue Analytics Management
     */
    public function revenueAnalytics(Request $request)
    {
        $startDate = $request->get('start_date') ? \Carbon\Carbon::parse($request->get('start_date')) : now()->startOfMonth();
        $endDate = $request->get('end_date') ? \Carbon\Carbon::parse($request->get('end_date')) : now()->endOfMonth();

        // Get revenue data
        $revenueData = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as daily_revenue'),
                DB::raw('COUNT(*) as order_count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get();

        // Calculate revenue statistics
        $totalRevenue = $revenueData->sum('daily_revenue');
        $totalOrders = $revenueData->sum('order_count');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Get revenue by payment method
        $revenueByPaymentMethod = Payment::select(
                'method as payment_method',
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'success')
            ->groupBy('method')
            ->orderBy('total_amount', 'desc')
            ->get();

        // Get top revenue generating menus
        $topMenus = DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                'menus.name',
                'menus.category',
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue'),
                DB::raw('SUM(order_items.quantity) as quantity_sold')
            )
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
            ->where('orders.payment_status', 'paid')
            ->groupBy('menus.id', 'menus.name', 'menus.category')
            ->orderBy('revenue', 'desc')
            ->take(10)
            ->get();

        // Calculate growth metrics (compare with previous period)
        $previousPeriodStart = \Carbon\Carbon::parse($startDate)->subDays(\Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1)->format('Y-m-d');
        $previousPeriodEnd = \Carbon\Carbon::parse($startDate)->subDay()->format('Y-m-d');

        $previousRevenue = Order::whereBetween('created_at', [$previousPeriodStart, $previousPeriodEnd])
            ->where('payment_status', 'paid')
            ->sum('total');

        $revenueGrowth = $previousRevenue > 0 ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;

        $analytics = [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'average_order_value' => $averageOrderValue,
            'revenue_growth' => $revenueGrowth,
            'revenue_data' => $revenueData,
            'revenue_by_payment_method' => $revenueByPaymentMethod,
            'top_menus' => $topMenus,
        ];

        return view('manager.revenue-analytics.index', compact('analytics', 'startDate', 'endDate'));
    }
}
