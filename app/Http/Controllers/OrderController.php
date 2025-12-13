<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        // If customer came from admin area, redirect to dashboard to fix back button navigation
        if (Auth::user()->role === 'customer' && request()->headers->get('referer') && str_contains(request()->headers->get('referer'), '/admin')) {
            return redirect()->route('customer.dashboard');
        }

        $query = Order::with(['user', 'items', 'payment']);

        // Filter by role
        if (Auth::user()->role === 'customer') {
            $query->where('user_id', Auth::id());
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by order type
        if ($request->has('order_type') && $request->order_type != '') {
            $query->where('order_type', $request->order_type);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($query) use ($request) {
                      $query->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $orders = $query->latest()->paginate(20);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        $menus = Menu::where('is_available', true)->where('stock', '>', 0)->get();
        return view('customer.orders.store', compact('menus'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        // Filter items with quantity >= 1
        $filteredItems = [];
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $item) {
                if (isset($item['quantity']) && intval($item['quantity']) >= 1) {
                    $filteredItems[] = $item;
                }
            }
        }
        // Overwrite items input with filtered items
        $request->merge(['items' => $filteredItems]);

        $request->validate([
            'order_type' => 'required|in:dine_in,delivery,takeaway',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'delivery_address' => 'required_if:order_type,delivery',
            'delivery_phone' => 'required_if:order_type,delivery',
        ]);

        DB::beginTransaction();
        try {
            // Lock all menus that are included in the order to avoid race conditions
            $menuIds = array_column($request->items, 'menu_id');
            $lockedMenus = Menu::whereIn('id', $menuIds)->lockForUpdate()->get()->keyBy('id');

            // Calculate totals and validate stock based on locked rows
            $subtotal = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $menu = $lockedMenus->get($item['menu_id']);
                if (!$menu) {
                    throw new \Exception('Menu dengan ID ' . $item['menu_id'] . ' tidak ditemukan.');
                }

                $qty = intval($item['quantity']);
                if ($menu->stock < $qty) {
                    throw new \Exception('Stok untuk ' . $menu->name . ' tidak mencukupi. Tersedia: ' . $menu->stock);
                }

                $itemSubtotal = $menu->price * $qty;
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'menu_id' => $menu->id,
                    'item_name' => $menu->name,
                    'quantity' => $qty,
                    'price' => $menu->price,
                    'subtotal' => $itemSubtotal,
                    'notes' => $item['notes'] ?? null
                ];
            }

            $tax = $subtotal * 0.10; // 10% tax
            $deliveryFee = $request->order_type === 'delivery' ? 15000 : 0;
            $total = $subtotal + $tax + $deliveryFee;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_type' => $request->order_type,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
                'delivery_address' => $request->delivery_address,
                'delivery_phone' => $request->delivery_phone,
                'notes' => $request->notes,
                'payment_status' => 'unpaid'
            ]);

            // Create order items and decrement stock using locked models
            foreach ($orderItems as $item) {
                $order->items()->create($item);

                $menu = $lockedMenus->get($item['menu_id']);
                // decrement stock
                $menu->decrement('stock', $item['quantity']);
                // refresh stock value from database and update availability
                $menu->refresh();
                $menu->is_available = $menu->stock > 0;
                $menu->save();
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                           ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        // Check authorization
        if (Auth::user()->role === 'customer' && $order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $order->load(['user', 'items.menu', 'payment']);
        return view('orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivering,completed,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        if ($request->status === 'completed') {
            $order->update(['completed_at' => now()]);
        }

        return back()->with('success', 'Status pesanan berhasil diupdate!');
    }

    /**
     * Cancel order
     */
    public function cancel(Order $order)
    {
        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan!');
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Pesanan berhasil dibatalkan!');
    }

    /**
     * Show checkout page
     */
    public function checkout(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->isPaid()) {
            return redirect()->route('orders.show', $order)
                           ->with('info', 'Pesanan sudah dibayar!');
        }

        return view('orders.checkout', compact('order'));
    }

    /**
     * Process payment
     */
    public function processPayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,transfer_bca,transfer_mandiri,transfer_bni,gopay,ovo,dana,shopeepay,credit_card',
            'proof_image' => 'required_unless:payment_method,cash|image|max:2048'
        ]);

        DB::beginTransaction();
        try {
            // Upload proof if exists
            $proofPath = null;
            if ($request->hasFile('proof_image')) {
                $proofPath = $request->file('proof_image')->store('payment-proofs', 'public');
            }

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'amount' => $order->total,
                'method' => $request->payment_method,
                'status' => $request->payment_method === 'cash' ? 'pending' : 'processing',
                'proof_image' => $proofPath,
                'notes' => $request->notes
            ]);

            // Update order
            $order->update([
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'cash' ? 'unpaid' : 'paid',
                'payment_proof' => $proofPath
            ]);

            DB::commit();

            return redirect()->route('orders.show', $order)
                           ->with('success', 'Pembayaran berhasil! Menunggu konfirmasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get order stats for dashboard
     */
    public function stats()
    {
        $stats = [
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'today_revenue' => Order::whereDate('created_at', today())
                                   ->where('payment_status', 'paid')
                                   ->sum('total'),
            'monthly_revenue' => Order::whereMonth('created_at', now()->month)
                                    ->where('payment_status', 'paid')
                                    ->sum('total')
        ];

        return response()->json($stats);
    }

    /**
     * Display a listing of current (active) orders for authenticated customer.
     */
    public function currentOrders(Request $request)
    {
        // If customer came from admin area, redirect to dashboard to fix back button navigation
        if (Auth::user()->role === 'customer' && request()->headers->get('referer') && str_contains(request()->headers->get('referer'), '/admin')) {
            return redirect()->route('customer.dashboard');
        }

        $currentStatuses = ['pending', 'confirmed', 'preparing', 'ready', 'delivering'];

        $query = Order::with(['items', 'payment'])
                      ->where('user_id', auth()->id())
                      ->whereIn('status', $currentStatuses);

        // Optionally apply other filters if needed
        if ($request->has('order_type') && $request->order_type != '') {
            $query->where('order_type', $request->order_type);
        }

        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->latest()->paginate(20);

        // Reuse the same index view, passing a flag to distinguish current orders view
        return view('customer.orders.index', compact('orders'))->with('currentOrdersView', true);
    }

    /**
     * Display a listing of order history for authenticated customer.
     */
    public function historyOrders(Request $request)
    {
        $historyStatuses = ['completed', 'cancelled'];

        $query = Order::with(['items', 'payment'])
                      ->where('user_id', auth()->id())
                      ->whereIn('status', $historyStatuses);

        // Optionally apply other filters if needed
        if ($request->has('order_type') && $request->order_type != '') {
            $query->where('order_type', $request->order_type);
        }

        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%');
            });
        }

        $orders = $query->latest()->paginate(20);

        // Reuse the same index view, passing a flag to distinguish history orders view
        return view('customer.orders.index', compact('orders'))->with('historyOrdersView', true);
    }
}