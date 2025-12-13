<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Menu;
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
            'total_menus' => Menu::count(),
            'available_menus' => Menu::where('is_available', true)->count(),
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

        return view('dashboard.manager', compact('stats', 'revenueChart', 'topItems', 'recentOrders'));
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
            'total_menus' => Menu::count(),
            'available_menus' => Menu::where('is_available', true)->count(),
        ];

        // Recent orders
        $recentOrders = Order::with(['user', 'items'])
                            ->latest()
                            ->limit(15)
                            ->get();

        return view('dashboard.admin', compact('stats', 'recentOrders'));
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
        ];

        // Active orders
        $activeOrders = Order::with(['user', 'items'])
                            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
                            ->latest()
                            ->get();

        return view('dashboard.kasir', compact('stats', 'activeOrders'));
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
        ];

        // Active orders
        $activeOrders = Order::with(['items'])
                            ->where('user_id', $userId)
                            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivering'])
                            ->latest()
                            ->get();

        // Order history
        $orderHistory = Order::with(['items'])
                            ->where('user_id', $userId)
                            ->where('status', 'completed')
                            ->latest()
                            ->limit(10)
                            ->get();

        return view('dashboard.customer', compact('stats', 'activeOrders', 'orderHistory'));
    }
}