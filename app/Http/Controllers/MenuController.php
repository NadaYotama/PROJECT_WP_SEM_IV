<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        // Ambil data menu dari database - hanya yang tersedia dan stok > 0
        $menus = Menu::where('is_available', true)->where('stock', '>', 0)->get()->unique('name');
        $categories = Menu::where('is_available', true)->where('stock', '>', 0)->distinct()->pluck('category')->toArray();

        return view('menu.index', compact('menus', 'categories'));
    }
}
