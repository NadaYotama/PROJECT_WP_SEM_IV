<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'balance', 'role')
                    ->where('role', 'customer')
                    ->paginate(15);

        return view('admin.balance.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.balance.show', compact('user'));
    }

    public function topUp(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255'
        ]);

        $user = User::findOrFail($id);

        DB::transaction(function () use ($user, $request) {
            $user->increment('balance', $request->amount);

            // Log the transaction (you might want to create a transactions table)
            // For now, we'll just update the balance
        });

        return redirect()->back()->with('success', 'Balance topped up successfully');
    }

    public function deduct(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255'
        ]);

        $user = User::findOrFail($id);

        if ($user->balance < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient balance');
        }

        DB::transaction(function () use ($user, $request) {
            $user->decrement('balance', $request->amount);

            // Log the transaction
        });

        return redirect()->back()->with('success', 'Balance deducted successfully');
    }

    public function history($id)
    {
        $user = User::findOrFail($id);
        // If you have a transactions table, fetch history here
        // For now, return empty history
        $transactions = collect();

        return view('admin.balance.history', compact('user', 'transactions'));
    }
}
