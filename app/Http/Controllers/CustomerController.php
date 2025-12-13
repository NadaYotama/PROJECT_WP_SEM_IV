<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Reservation;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:customer');
    }

    public function index()
    {
        $userId = Auth::id();

        // Stats Cards (4 Cards)
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

        // Active Orders
        $activeOrders = Order::with(['items', 'payment'])
                            ->where('user_id', $userId)
                            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'delivering'])
                            ->latest()
                            ->get();

        // Order History
        $orderHistory = Order::with(['items', 'payment'])
                            ->where('user_id', $userId)
                            ->where('status', 'completed')
                            ->latest()
                            ->limit(10)
                            ->get();

        // Upcoming Reservations
        $upcomingReservations = Reservation::where('user_id', $userId)
                                          ->where('status', 'confirmed')
                                          ->where('date', '>=', today())
                                          ->orderBy('date')
                                          ->orderBy('time')
                                          ->get();

        $totalOrders = $stats['total_orders'];
        $totalReservations = Reservation::where('user_id', $userId)->count();
        $favoriteMenus = 0; // Placeholder, implement later if needed
        $totalSpent = $stats['total_spent'];
        $orders = Order::with(['items', 'payment'])->where('user_id', $userId)->latest()->get();
        $reservations = $upcomingReservations;

        // Fetch featured menus that are available
        $featuredMenus = Menu::where('is_available', true)->where('is_popular', true)->get();

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

        return view('dashboard.customer', compact('stats', 'activeOrders', 'orderHistory', 'upcomingReservations', 'totalOrders', 'totalReservations', 'favoriteMenus', 'totalSpent', 'orders', 'reservations', 'featuredMenus', 'menus', 'categories'));
    }
}
