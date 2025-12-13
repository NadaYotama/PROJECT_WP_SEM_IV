<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Menu;
use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:kasir');
    }

    public function index()
    {
        // Payment Stats (4 Cards)
        $stats = [
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'ready_orders' => Order::where('status', 'ready')->count(),
            'pending_payments' => Payment::where('status', 'processing')->count(),
        ];

        // Revenue Breakdown (3 Cards)
        $revenue = [
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

        // Pending Payments (PRIORITY SECTION)
        $pendingPayments = Payment::with(['order', 'user'])
                                 ->where('status', 'processing')
                                 ->latest()
                                 ->limit(20)
                                 ->get();

        // Active Orders Table
        $activeOrders = Order::with(['user', 'items'])
                            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
                            ->latest()
                            ->get();

        return view('dashboard.kasir', compact('stats', 'revenue', 'pendingPayments', 'activeOrders'));
    }

    public function orders(Request $request)
    {
        // Payment Stats (4 Cards)
        $stats = [
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'ready_orders' => Order::where('status', 'ready')->count(),
            'pending_payments' => Payment::where('status', 'processing')->count(),
        ];

        $query = Order::with(['user', 'items']);

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('order_type') && $request->order_type) {
            $query->where('order_type', $request->order_type);
        }

        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%')
                               ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
        }

        $orders = $query->latest()->paginate(25);

        return view('kasir.orders.index', compact('stats', 'orders'));
    }

    public function createOrder()
    {
        $menus = Menu::where('is_available', true)->get();

        // Payment Stats (4 Cards)
        $stats = [
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'ready_orders' => Order::where('status', 'ready')->count(),
            'pending_payments' => Payment::where('status', 'processing')->count(),
        ];

        return view('kasir.orders.create', compact('menus', 'stats'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'order_type' => 'required|in:dine_in,takeaway,delivery',
            'table_number' => 'nullable|required_if:order_type,dine_in|integer',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        // Calculate total
        $total = 0;
        $orderItems = [];
        foreach ($request->items as $item) {
            $menu = Menu::find($item['menu_id']);
            $subtotal = $menu->price * $item['quantity'];
            $total += $subtotal;
            $orderItems[] = [
                'menu_id' => $item['menu_id'],
                'quantity' => $item['quantity'],
                'price' => $menu->price,
                'subtotal' => $subtotal,
            ];
        }

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . time() . '-' . rand(100, 999),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'order_type' => $request->order_type,
            'table_number' => $request->table_number,
            'total_amount' => $total,
            'status' => 'pending',
            'payment_status' => 'pending',
            'notes' => $request->notes,
        ]);

        // Create order items
        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        return redirect()->route('kasir.orders.index')->with('success', 'Order created successfully');
    }

    public function showOrder(Order $order)
    {
        $order->load('user', 'items.menu', 'payment');

        // Payment Stats (4 Cards)
        $stats = [
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'ready_orders' => Order::where('status', 'ready')->count(),
            'pending_payments' => Payment::where('status', 'processing')->count(),
        ];

        return view('kasir.orders.show', compact('order', 'stats'));
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivering,completed,cancelled'
        ]);

        $order = Order::findOrFail($orderId);
        $order->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function pendingOrders(Request $request)
    {
        // Payment Stats (4 Cards)
        $stats = [
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'ready_orders' => Order::where('status', 'ready')->count(),
            'pending_payments' => Payment::where('status', 'processing')->count(),
        ];

        $query = Order::with(['user', 'items.menu'])
                     ->where('status', 'pending');

        // Apply filters
        if ($request->has('order_type') && $request->order_type) {
            $query->where('order_type', $request->order_type);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $search . '%');
            });
        }

        $pendingOrders = $query->latest()->paginate(25);

        return view('kasir.orders.pending', compact('stats', 'pendingOrders'));
    }

    public function completedOrders(Request $request)
    {
        // Payment Stats (4 Cards)
        $stats = [
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'ready_orders' => Order::where('status', 'ready')->count(),
            'pending_payments' => Payment::where('status', 'processing')->count(),
        ];

        $query = Order::with(['user', 'items.menu'])
                     ->where('status', 'completed');

        // Apply filters
        if ($request->has('order_type') && $request->order_type) {
            $query->where('order_type', $request->order_type);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $search . '%');
            });
        }

        $completedOrders = $query->latest()->paginate(25);

        return view('kasir.orders.completed', compact('stats', 'completedOrders'));
    }

    public function payments(Request $request)
    {
        // Payment Stats (4 Cards)
        $stats = [
            'total_today' => Payment::whereDate('created_at', today())
                                   ->where('status', 'success')
                                   ->sum('amount'),
            'success_count' => Payment::whereDate('created_at', today())
                                     ->where('status', 'success')
                                     ->count(),
            'pending_count' => Payment::where('status', 'processing')->count(),
            'failed_count' => Payment::whereDate('created_at', today())
                                    ->where('status', 'failed')
                                    ->count(),
        ];

        $query = Payment::with(['order', 'user']);

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('method') && $request->method) {
            if ($request->method == 'transfer') {
                $query->whereIn('method', ['transfer_bca', 'transfer_mandiri', 'transfer_bni']);
            } elseif ($request->method == 'ewallet') {
                $query->whereIn('method', ['gopay', 'ovo', 'dana', 'shopeepay']);
            } else {
                $query->where('method', $request->method);
            }
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_code', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('order', function($orderQuery) use ($search) {
                      $orderQuery->where('order_number', 'like', '%' . $search . '%');
                  });
            });
        }

        $payments = $query->latest()->paginate(25);

        return view('kasir.payments.index', compact('stats', 'payments'));
    }

    public function showPayment($paymentId)
    {
        $payment = Payment::with(['order.items.menu', 'user', 'verifiedBy'])->findOrFail($paymentId);

        // Payment Stats (4 Cards)
        $stats = [
            'orders_today' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'ready_orders' => Order::where('status', 'ready')->count(),
            'pending_payments' => Payment::where('status', 'processing')->count(),
        ];

        return view('kasir.payments.show', compact('payment', 'stats'));
    }

    public function verifyPayment(Request $request, $paymentId)
    {
        $request->validate([
            'status' => 'required|in:success,failed',
            'notes' => 'nullable|string'
        ]);

        $payment = Payment::findOrFail($paymentId);

        \DB::beginTransaction();
        try {
            // Update payment status
            $payment->update([
                'status' => $request->status,
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'notes' => $request->notes
            ]);

            // Update order if payment successful
            if ($request->status === 'success') {
                $payment->order->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                    'status' => $payment->order->status === 'pending' ? 'confirmed' : $payment->order->status
                ]);
            } else {
                // If payment failed, set order payment status back to unpaid
                $payment->order->update([
                    'payment_status' => 'unpaid'
                ]);
            }

            \DB::commit();

            $message = $request->status === 'success'
                ? 'Pembayaran berhasil diverifikasi!'
                : 'Pembayaran ditolak!';

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function paymentHistory(Request $request)
    {
        // Payment History Stats (4 Cards)
        $stats = [
            'total_processed' => Payment::where('status', 'success')
                                       ->whereDate('verified_at', today())
                                       ->sum('amount'),
            'total_refunded' => Payment::where('status', 'failed')
                                      ->whereDate('verified_at', today())
                                      ->sum('amount'),
            'transactions_today' => Payment::whereDate('verified_at', today())
                                           ->whereIn('status', ['success', 'failed'])
                                           ->count(),
            'avg_transaction' => Payment::where('status', 'success')
                                       ->whereDate('verified_at', today())
                                       ->avg('amount') ?? 0,
        ];

        $query = Payment::with(['order', 'user', 'verifiedBy'])
                       ->whereIn('status', ['success', 'failed']);

        // Apply filters
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('method') && $request->method) {
            if ($request->method == 'transfer') {
                $query->whereIn('method', ['transfer_bca', 'transfer_mandiri', 'transfer_bni']);
            } elseif ($request->method == 'ewallet') {
                $query->whereIn('method', ['gopay', 'ovo', 'dana', 'shopeepay']);
            } else {
                $query->where('method', $request->method);
            }
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('verified_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('verified_at', '<=', $request->date_to);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_code', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('order', function($orderQuery) use ($search) {
                      $orderQuery->where('order_number', 'like', '%' . $search . '%');
                  });
            });
        }

        $payments = $query->latest('verified_at')->paginate(25);

        return view('kasir.payments.history', compact('stats', 'payments'));
    }

    public function refunds(Request $request)
    {
        // Refund Stats (4 Cards)
        $stats = [
            'total_refunded' => Payment::where('status', 'failed')
                                      ->whereDate('verified_at', today())
                                      ->sum('amount'),
            'refund_requests' => Payment::where('status', 'failed')
                                        ->whereDate('verified_at', today())
                                        ->count(),
            'avg_refund' => Payment::where('status', 'failed')
                                  ->whereDate('verified_at', today())
                                  ->avg('amount') ?? 0,
            'refund_rate' => Payment::whereDate('verified_at', today())
                                   ->count() > 0 ? 
                                   (Payment::where('status', 'failed')
                                          ->whereDate('verified_at', today())
                                          ->count() /
                                    Payment::whereDate('verified_at', today())
                                           ->count()) * 100 : 0,
        ];

        $query = Payment::with(['order', 'user', 'verifiedBy'])
                       ->where('status', 'failed');

        // Apply filters
        if ($request->has('method') && $request->method) {
            if ($request->method == 'transfer') {
                $query->whereIn('method', ['transfer_bca', 'transfer_mandiri', 'transfer_bni']);
            } elseif ($request->method == 'ewallet') {
                $query->whereIn('method', ['gopay', 'ovo', 'dana', 'shopeepay']);
            } else {
                $query->where('method', $request->method);
            }
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('verified_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('verified_at', '<=', $request->date_to);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payment_code', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('order', function($orderQuery) use ($search) {
                      $orderQuery->where('order_number', 'like', '%' . $search . '%');
                  });
            });
        }

        $refunds = $query->latest('verified_at')->paginate(25);

        return view('kasir.payments.refunds', compact('stats', 'refunds'));
    }

    public function settings()
    {
        return view('dashboard.kasir-settings');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        auth()->user()->update($request->only(['name', 'email', 'phone', 'address']));

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    public function dailySalesReport()
    {
        $totalSalesToday = Payment::whereDate('created_at', today())
                                  ->where('status', 'success')
                                  ->sum('amount');

        return view('kasir.reports.daily-sales', compact('totalSalesToday'));
    }

    public function paymentMethods()
    {
        try {
            $methods = [
                [
                    'code' => 'transfer_bca',
                    'name' => 'Transfer BCA',
                    'type' => 'bank_transfer',
                    'account_number' => '1234567890',
                    'account_name' => 'RM Padang',
                    'icon' => 'bank'
                ],
                [
                    'code' => 'transfer_mandiri',
                    'name' => 'Transfer Mandiri',
                    'type' => 'bank_transfer',
                    'account_number' => '9876543210',
                    'account_name' => 'RM Padang',
                    'icon' => 'bank'
                ],
                [
                    'code' => 'transfer_bni',
                    'name' => 'Transfer BNI',
                    'type' => 'bank_transfer',
                    'account_number' => '5555666677',
                    'account_name' => 'RM Padang',
                    'icon' => 'bank'
                ],
                [
                    'code' => 'gopay',
                    'name' => 'GoPay',
                    'type' => 'e_wallet',
                    'phone' => '081234567890',
                    'icon' => 'mobile'
                ],
                [
                    'code' => 'ovo',
                    'name' => 'OVO',
                    'type' => 'e_wallet',
                    'phone' => '081234567890',
                    'icon' => 'mobile'
                ],
                [
                    'code' => 'dana',
                    'name' => 'DANA',
                    'type' => 'e_wallet',
                    'phone' => '081234567890',
                    'icon' => 'mobile'
                ],
                [
                    'code' => 'shopeepay',
                    'name' => 'ShopeePay',
                    'type' => 'e_wallet',
                    'phone' => '081234567890',
                    'icon' => 'mobile'
                ],
                [
                    'code' => 'cash',
                    'name' => 'Tunai',
                    'type' => 'cash',
                    'description' => 'Bayar langsung di kasir atau saat pesanan tiba',
                    'icon' => 'money-bill'
                ],
                [
                    'code' => 'credit_card',
                    'name' => 'Kartu Kredit',
                    'type' => 'credit_card',
                    'description' => 'Visa, Mastercard, JCB',
                    'icon' => 'credit-card'
                ]
            ];

            return view('kasir.payments.methods', compact('methods'));
        } catch (\Exception $e) {
            \Log::error('Error loading payment methods page: ' . $e->getMessage());
            abort(500, 'Terjadi kesalahan saat menampilkan metode pembayaran.');
        }
    }

    public function revenueReport()
    {
        // Calculate revenue summary data, e.g. total revenue for today and breakdowns by method
        $revenue = [
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
            'total_revenue_today' => Payment::whereDate('created_at', today())
                                          ->where('status', 'success')
                                          ->sum('amount'),
        ];

        return view('kasir.reports.revenue', compact('revenue'));
    }
}
