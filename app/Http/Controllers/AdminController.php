<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Menu;
use App\Models\Table;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Admin Dashboard
     */
    public function index()
    {
        // Redirect non-admin users to their appropriate dashboard
        if (auth()->user()->role !== 'admin') {
            if (auth()->user()->role === 'customer') {
                return redirect()->route('customer.dashboard');
            } elseif (auth()->user()->role === 'manager') {
                return redirect()->route('manager.dashboard');
            } elseif (auth()->user()->role === 'kasir') {
                return redirect()->route('kasir.dashboard');
            }
        }
        // Quick Stats (5 Cards)
        $stats = [
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'confirmed_orders' => Order::where('status', 'confirmed')->count(),
            'preparing_orders' => Order::where('status', 'preparing')->count(),
            'ready_orders' => Order::whereIn('status', ['ready', 'delivering'])->count(),
            'low_stock_items' => Menu::where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'out_of_stock_items' => Menu::where('stock', '=', 0)->count(),
            'total_stock_value' => Menu::sum(DB::raw('stock * price')),
        ];

        // Management Cards (4 Cards)
        $management = [
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'reservations_today' => Reservation::where('status', 'confirmed')
                                                        ->whereDate('date', today())
                                                        ->count(),
            'total_menus' => Menu::count(),
            'available_tables' => Table::where('status', 'available')->count(),
        ];

        // Recent Orders (Real-time)
        $recentOrders = Order::with(['user', 'items'])
                            ->latest()
                            ->limit(15)
                            ->get();

        // Today's Reservations
        $todayReservations = Reservation::whereDate('date', today())
                                       ->orderBy('time')
                                       ->get();

        // Recent Payments
        $recentPayments = Payment::with('user')
                                ->latest()
                                ->limit(10)
                                ->get();

        // Recent Reservations
        $recentReservations = Reservation::with(['user', 'table'])->latest()->limit(10)->get();

        // Recent Stock Reductions
        $recentStockReductions = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->select(
                'orders.order_number',
                'menus.name as item_name',
                'order_items.quantity',
                'menus.stock as current_stock',
                'order_items.created_at'
            )
            ->where('orders.status', '!=', 'cancelled')
            ->orderBy('order_items.created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent Activities (using orders as activities)
        $recentActivities = Order::with('user')->latest()->limit(10)->get()->map(function($order) {
            return (object) [
                'created_at' => $order->created_at,
                'description' => 'Order ' . $order->order_number . ' placed',
                'user' => $order->user,
                'status' => $order->status
            ];
        });

        // Notifications (dummy for now)
        $notifications = collect(); // Empty collection, can be populated later

        // User Stats
        $userStats = [
            'admins' => User::where('role', 'admin')->count(),
            'managers' => User::where('role', 'manager')->count(),
            'kasirs' => User::where('role', 'kasir')->count(),
            'customers' => User::where('role', 'customer')->count(),
        ];

        // Data for sections
        $menuItems = Menu::paginate(15);
        $sidebarMenuImage = Menu::whereNotNull('image')->value('image');
        $orders = Order::with('user')->latest()->paginate(15);
        $reservations = Reservation::with('user', 'table')->latest()->paginate(15);
        $tables = Table::paginate(15);
        $users = User::paginate(15);
        $payments = Payment::with('user', 'order')->latest()->paginate(15);

        // Reports
        $startDate = now()->startOfMonth()->format('Y-m-d');
        $endDate = now()->endOfMonth()->format('Y-m-d');
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

        return view('dashboard.admin', compact('stats', 'management', 'recentOrders', 'todayReservations', 'recentPayments', 'recentStockReductions', 'menuItems', 'orders', 'reservations', 'tables', 'users', 'payments', 'reports', 'startDate', 'endDate', 'sidebarMenuImage'));
    }

    /**
     * User Management
     */
    public function users(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        // Filter by role
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,kasir,customer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,kasir,customer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui');
    }

    public function destroyUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    }

    /**
     * Menu Management
     */
    public function menu(Request $request)
    {
        $query = Menu::query();

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        if ($request->ajax()) {
            $menuItems = $query->paginate(15);
            return response()->json($menuItems);
        }

        $menuItems = $query->paginate(15);

        // Fetch distinct categories from the database for filter dropdown
        $allowedCategories = Menu::distinct()->pluck('category')->sort()->values()->toArray();

        return view('admin.menu.index', compact('menuItems', 'allowedCategories'));
    }

    public function createMenu()
    {
        return view('admin.menu.create');
    }

    public function storeMenu(Request $request)
    {
        // Fetch allowed categories from the database
        $allowedCategories = Menu::distinct()->pluck('category')->toArray();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => ['required', 'string', Rule::in($allowedCategories)],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preparation_time' => 'nullable|integer|min:1',
            'is_available' => 'boolean',
            'is_popular' => 'boolean',
            'stock' => 'nullable|integer|min:0',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu', 'public');
        }

        Menu::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'image' => $imagePath,
            'preparation_time' => $request->preparation_time,
            'is_available' => $request->is_available ?? true,
            'is_popular' => $request->is_popular ?? false,
            'stock' => $request->stock ?? 0,
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan');
    }

    public function editMenu(Menu $menu)
    {
        return view('admin.menu.edit', compact('menu'));
    }

    public function updateMenu(Request $request, Menu $menu)
    {
        // Fetch allowed categories from the database
        $allowedCategories = Menu::distinct()->pluck('category')->toArray();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => ['required', 'string', Rule::in($allowedCategories)],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preparation_time' => 'nullable|integer|min:1',
            'is_available' => 'boolean',
            'is_popular' => 'boolean',
            'stock' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $imagePath = $request->file('image')->store('menu', 'public');
        } else {
            $imagePath = $menu->image;
        }

        $menu->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'image' => $imagePath,
            'preparation_time' => $request->preparation_time,
            'is_available' => $request->is_available ?? true,
            'is_popular' => $request->is_popular ?? false,
            'stock' => $request->stock ?? 0,
        ]);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui');
    }

    public function destroyMenu(Menu $menu)
    {
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        $menu->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus');
    }

    public function adjustStock(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'action' => 'required|in:increase,decrease',
            'quantity' => 'required|integer|min:1',
        ]);
        $quantity = (int) $request->quantity;

        // Use transaction and row lock to avoid race conditions with customer orders
        try {
            $result = DB::transaction(function () use ($request, $quantity) {
                $menu = Menu::where('id', $request->menu_id)->lockForUpdate()->first();
                if (!$menu) {
                    return [
                        'success' => false,
                        'message' => 'Menu tidak ditemukan.'
                    ];
                }

                if ($request->action === 'increase') {
                    $menu->increment('stock', $quantity);
                } else {
                    // decrease
                    if ($menu->stock < $quantity) {
                        return [
                            'success' => false,
                            'message' => 'Stok tidak cukup untuk dikurangi.'
                        ];
                    }
                    $menu->decrement('stock', $quantity);
                }

                // Refresh model and update availability
                $menu->refresh();
                $menu->is_available = $menu->stock > 0;
                $menu->save();

                return [
                    'success' => true,
                    'message' => 'Stok berhasil disesuaikan.',
                    'new_stock' => $menu->stock
                ];
            });

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Order Management
     */
    public function orders(Request $request)
    {
        $query = Order::with('user');

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load('user', 'items.menu', 'payment');
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivering,completed,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui');
    }

    /**
     * Payment Management
     */
    public function payments(Request $request)
    {
        $query = Payment::with('user', 'order');

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->where('payment_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function showPayment(Payment $payment)
    {
        $payment->load('user', 'order');
        return view('admin.payments.show', compact('payment'));
    }

    public function verifyPayment(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:success,failed',
        ]);

        $payment->update([
            'status' => $request->status,
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui');
    }

    /**
     * Table Management
     */
    public function tables(Request $request)
    {
        $query = Table::query();

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->where('table_number', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $tables = $query->paginate(15);

        return view('admin.tables.index', compact('tables'));
    }

    public function createTable()
    {
        return view('admin.tables.create');
    }

    public function storeTable(Request $request)
    {
        $request->validate([
            'table_number' => 'required|string|max:255|unique:tables',
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'status' => 'required|in:available,occupied,reserved,maintenance',
        ]);

        Table::create($request->all());

        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil ditambahkan');
    }

    public function editTable(Table $table)
    {
        return view('admin.tables.edit', compact('table'));
    }

    public function updateTable(Request $request, Table $table)
    {
        $request->validate([
            'table_number' => 'required|string|max:255|unique:tables,table_number,' . $table->id,
            'capacity' => 'required|integer|min:1',
            'location' => 'required|string|max:255',
            'status' => 'required|in:available,occupied,reserved,maintenance',
        ]);

        $table->update($request->all());

        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil diperbarui');
    }

    public function destroyTable(Table $table)
    {
        $table->delete();
        return redirect()->route('admin.tables.index')->with('success', 'Meja berhasil dihapus');
    }

    /**
     * Reservation Management
     */
    public function reservations(Request $request)
    {
        $query = Reservation::with('user', 'table');

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $reservations = $query->latest()->paginate(15);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function storeReservation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:20',
            'guests' => 'required|integer|min:1',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        Reservation::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'guests' => $request->guests,
            'date' => $request->date,
            'time' => $request->time,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Reservasi berhasil ditambahkan');
    }

    public function showReservation(Reservation $reservation)
    {
        $reservation->load('user', 'table');
        return view('admin.reservations.show', compact('reservation'));
    }

    public function updateReservationStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $reservation->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status reservasi berhasil diperbarui');
    }

    public function cancelReservation(Reservation $reservation)
    {
        $reservation->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Reservasi berhasil dibatalkan');
    }

    /**
     * Category Management
     */
    public function categories(Request $request)
    {
        $query = Menu::select('category', DB::raw('COUNT(*) as menu_count'))
            ->groupBy('category');

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->where('category', 'like', '%' . $request->search . '%');
        }

        $categories = $query->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:menus,category',
        ]);

        // Since categories are just strings in menus, we don't create a new record
        // But we can validate that the category doesn't already exist
        $existing = Menu::where('category', $request->name)->exists();
        if ($existing) {
            return redirect()->back()->withErrors(['name' => 'Kategori sudah ada.']);
        }

        // Optionally, create a dummy menu to establish the category, but since it's just for listing, we can skip
        // For now, just redirect back with success
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function updateCategory(Request $request, $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:menus,category,' . $category . ',category',
        ]);

        // Update all menus with the old category to the new one
        Menu::where('category', $category)->update(['category' => $request->name]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroyCategory($category)
    {
        // Delete all menus in this category
        Menu::where('category', $category)->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori dan semua menu di dalamnya berhasil dihapus');
    }

    /**
     * Reports
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

        return view('admin.reports.index', compact('reports', 'startDate', 'endDate'));
    }

    /**
     * User Activity
     */
    public function userActivity(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Collect activities from various sources
        $activities = collect();

        // Orders
        $orders = Order::with('user')->whereBetween('created_at', [$startDate, $endDate])->get();
        foreach ($orders as $order) {
            $activities->push((object) [
                'created_at' => $order->created_at,
                'user' => $order->user,
                'type' => 'order',
                'description' => 'Order ' . $order->order_number . ' placed',
                'details' => 'Total: ' . $order->total,
                'status' => $order->status,
            ]);
        }

        // Payments
        $payments = Payment::with('user')->whereBetween('created_at', [$startDate, $endDate])->get();
        foreach ($payments as $payment) {
            $activities->push((object) [
                'created_at' => $payment->created_at,
                'user' => $payment->user,
                'type' => 'payment',
                'description' => 'Payment ' . $payment->payment_code . ' processed',
                'details' => 'Amount: ' . $payment->amount,
                'status' => $payment->status,
            ]);
        }

        // Reservations
        $reservations = Reservation::with('user')->whereBetween('created_at', [$startDate, $endDate])->get();
        foreach ($reservations as $reservation) {
            $activities->push((object) [
                'created_at' => $reservation->created_at,
                'user' => $reservation->user,
                'type' => 'reservation',
                'description' => 'Reservation made for ' . $reservation->date . ' at ' . $reservation->time,
                'details' => 'Guests: ' . $reservation->guests,
                'status' => $reservation->status,
            ]);
        }

        // Sort by created_at descending
        $activities = $activities->sortByDesc('created_at');

        // Paginate
        $paginatedActivities = new \Illuminate\Pagination\LengthAwarePaginator(
            $activities->forPage($request->get('page', 1), 15),
            $activities->count(),
            15,
            $request->get('page', 1),
            ['path' => $request->url(), 'pageName' => 'page']
        );

        return view('admin.user-activity.index', compact('paginatedActivities', 'startDate', 'endDate'));
    }
}
        