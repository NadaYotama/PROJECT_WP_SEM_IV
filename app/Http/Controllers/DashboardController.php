<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Menu;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'manager':
                return $this->managerDashboard();
            case 'admin':
                return $this->adminDashboard();
            case 'kasir':
                return $this->kasirDashboard();
            case 'customer':
                return $this->customerDashboard();
            default:
                return $this->customerDashboard();
        }
    }

    /**
     * Manager Dashboard
     */
    private function managerDashboard()
    {
        $stats = [
            'total_revenue_today' => Order::whereDate('created_at', today())
                                         ->where('payment_status', 'paid')
                                         ->sum('total'),
            'total_revenue_month' => Order::whereMonth('created_at', now()->month)
                                         ->where('payment_status', 'paid')
                                         ->sum('total'),
            'total_orders_today' => Order::whereDate('created_at', today())->count(),
            'total_orders_month' => Order::whereMonth('created_at', now()->month)->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'active_orders' => Order::whereIn('status', ['confirmed', 'preparing', 'ready', 'delivering'])->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_staff' => User::whereIn('role', ['admin', 'kasir'])->count(),
            'pending_payments' => Payment::where('status', 'processing')->count(),
            'total_reservations_today' => Reservation::whereDate('date', today())->count(),
            'available_tables' => Table::where('status', 'available')->count(),
        ];

        // Revenue chart data (last 7 days)
        $revenueChart = Order::where('payment_status', 'paid')
                            ->where('created_at', '>=', now()->subDays(7))
                            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as revenue'))
                            ->groupBy('date')
                            ->orderBy('date')
                            ->get();

        // Top selling items
        $topItems = DB::table('order_items')
                     ->select('item_name', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(subtotal) as revenue'))
                     ->groupBy('item_name')
                     ->orderBy('total_sold', 'desc')
                     ->limit(10)
                     ->get();

        // Recent orders
        $recentOrders = Order::with('user')
                            ->latest()
                            ->limit(10)
                            ->get();

        return view('dashboard.manager', [
            'stats' => $stats,
            'revenueChart' => $revenueChart,
            'topItems' => $topItems,
            'recentOrders' => $recentOrders,
        ]);
    }

    /**
     * Admin Dashboard
     */
    private function adminDashboard()
    {
        $stats = [
            'total_orders_today' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'confirmed_orders' => Order::where('status', 'confirmed')->count(),
            'preparing_orders' => Order::where('status', 'preparing')->count(),
            'ready_orders' => Order::where('status', 'ready')->count(),
            'delivering_orders' => Order::where('status', 'delivering')->count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'confirmed_reservations' => Reservation::where('status', 'confirmed')
                                                  ->whereDate('date', today())
                                                  ->count(),
            'total_menus' => Menu::count(),
            'available_menus' => Menu::where('is_available', true)->count(),
        ];

        // Recent orders
        $recentOrders = Order::with(['user', 'items'])
                            ->latest()
                            ->limit(15)
                            ->get();

        // Today's reservations
        $todayReservations = Reservation::whereDate('date', today())
                                       ->orderBy('time')
                                       ->get();

        return view('dashboard.admin', compact('stats', 'recentOrders', 'todayReservations'));
    }

    /**
     * Kasir Dashboard
     */
    private function kasirDashboard()
    {
        $stats = [
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'ready_orders' => Order::where('status', 'ready')->count(),
            'pending_payments' => Payment::where('status', 'processing')->count(),
            'cash_today' => Payment::whereDate('created_at', today())
                                  ->where('method', 'cash')
                                  ->where('status', 'success')
                                  ->sum('amount'),
            'transfer_today' => Payment::whereDate('created_at', today())
                                      ->whereIn('method', ['transfer_bca', 'transfer_mandiri', 'transfer_bni'])
                                      ->where('status', 'success')
                                      ->sum('amount'),
            'ewallet_today' => Payment::whereDate('created_at', today())
                                     ->whereIn('method', ['gopay', 'ovo', 'dana', 'shopeepay'])
                                     ->where('status', 'success')
                                     ->sum('amount'),
        ];

        // Pending payments for verification
        $pendingPayments = Payment::with(['order', 'user'])
                                 ->where('status', 'processing')
                                 ->latest()
                                 ->limit(20)
                                 ->get();

        // Active orders
        $activeOrders = Order::with(['user', 'items'])
                            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
                            ->latest()
                            ->get();

        return view('dashboard.kasir', compact('stats', 'pendingPayments', 'activeOrders'));
    }

    /**
     * Customer Dashboard
     */
    private function customerDashboard()
    {
        $userId = Auth::id();

        $stats = [
            'total_orders' => Order::where('user_id', $userId)->count(),
            'active_orders' => Order::where('user_id', $userId)
                                   ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivering'])
                                   ->count(),
            'completed_orders' => Order::where('user_id', $userId)
                                      ->where('status', 'completed')
                                      ->count(),
            'total_spent' => Order::where('user_id', $userId)
                                 ->where('payment_status', 'paid')
                                 ->sum('total'),
            'pending_payments' => Order::where('user_id', $userId)
                                      ->where('payment_status', 'unpaid')
                                      ->count(),
            'active_reservations' => Reservation::where('user_id', $userId)
                                               ->where('status', 'confirmed')
                                               ->where('date', '>=', today())
                                               ->count(),
        ];

        // Active orders
        $activeOrders = Order::with(['items', 'payment'])
                            ->where('user_id', $userId)
                            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivering'])
                            ->latest()
                            ->get();

        // Order history
        $orderHistory = Order::with(['items', 'payment'])
                            ->where('user_id', $userId)
                            ->where('status', 'completed')
                            ->latest()
                            ->limit(10)
                            ->get();

        // Upcoming reservations
        $upcomingReservations = Reservation::where('user_id', $userId)
                                          ->where('status', 'confirmed')
                                          ->where('date', '>=', today())
                                          ->orderBy('date')
                                          ->orderBy('time')
                                          ->get();

        // Menu items for ordering
        $menus = Menu::where('is_available', true)
                    ->orderBy('category')
                    ->orderBy('name')
                    ->get()
                    ->map(function ($menu) {
                        return [
                            'id' => $menu->id,
                            'name' => $menu->name,
                            'description' => $menu->description,
                            'price' => $menu->price,
                            'category' => $menu->category,
                            'image' => $menu->image_url, // Use the accessor for image URL
                            'is_available' => $menu->is_available
                        ];
                    })
                    ->toArray();

        $categories = Menu::where('is_available', true)
                         ->distinct()
                         ->pluck('category')
                         ->sort()
                         ->values()
                         ->toArray();

        return view('dashboard.customer', compact('stats', 'activeOrders', 'orderHistory', 'upcomingReservations', 'menus', 'categories'));
    }
}