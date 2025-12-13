<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function update(Request $request)
    {
        // Add your settings update logic here
        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
