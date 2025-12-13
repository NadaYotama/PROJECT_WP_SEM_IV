<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Order;

class MenuCustomerDashboardController extends Controller
{
    public function index()
    {
        // Fetch menu data from database
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
                            'stock' => $menu->stock,
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

        return view('customer.menu.index', compact('menus', 'categories'));
    }

    public function orders()
    {
        $orders = Order::with(['items.menu'])
                      ->where('user_id', auth()->id())
                      ->latest()
                      ->paginate(10);

        return view('customer.menu.orders', compact('orders'));
    }
}
