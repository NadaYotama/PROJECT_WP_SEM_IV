<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:customer');
    }

    /**
     * Add item to cart
     */
    public function add(Request $request, Menu $menu)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if menu is available
        if (!$menu->is_available) {
            return back()->withErrors(['menu' => 'Menu ini tidak tersedia saat ini.']);
        }

        // Get current cart from session
        $cart = Session::get('cart', []);

        // Check if item already exists in cart
        $itemKey = $menu->id;
        if (isset($cart[$itemKey])) {
            // Update quantity
            $cart[$itemKey]['quantity'] += $request->quantity;
        } else {
            // Add new item
            $cart[$itemKey] = [
                'id' => $menu->id,
                'name' => $menu->name,
                'price' => $menu->price,
                'quantity' => $request->quantity,
                'notes' => $request->notes,
                'image' => $menu->image_url,
                'preparation_time' => $menu->preparation_time,
            ];
        }

        // Save cart to session
        Session::put('cart', $cart);

        return back()->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    /**
     * Show cart
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = $this->calculateTotal($cart);

        return view('customer.cart.index', compact('cart', 'total'));
    }

    /**
     * Update cart item
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string|max:500',
        ]);

        $cart = Session::get('cart', []);

        if (isset($cart[$itemId])) {
            if ($request->quantity <= 0) {
                unset($cart[$itemId]);
            } else {
                // Check stock availability
                $menu = Menu::find($itemId);
                if ($menu && $menu->stock < $request->quantity) {
                    return back()->withErrors(['quantity' => 'Stok tidak cukup. Stok tersedia: ' . $menu->stock]);
                }

                $cart[$itemId]['quantity'] = $request->quantity;
                $cart[$itemId]['notes'] = $request->notes;
            }

            Session::put('cart', $cart);
        }

        return back()->with('success', 'Keranjang berhasil diperbarui!');
    }

    /**
     * Remove item from cart
     */
    public function remove($itemId)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            Session::put('cart', $cart);
        }

        return back()->with('success', 'Item berhasil dihapus dari keranjang!');
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        Session::forget('cart');

        return back()->with('success', 'Keranjang berhasil dikosongkan!');
    }

    /**
     * Calculate total price
     */
    private function calculateTotal($cart)
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    /**
     * Show checkout form
     */
    public function checkout()
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('customer.cart.index')->with('error', 'Keranjang kosong!');
        }

        $total = $this->calculateTotal($cart);

        return view('customer.cart.checkout', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong!');
        }

        // Validate order details
        $request->validate([
            'order_type' => 'required|in:dine_in,delivery,takeaway',
            'delivery_address' => 'required_if:order_type,delivery',
            'delivery_phone' => 'required_if:order_type,delivery',
        ]);

        // Calculate total
        $subtotal = 0;
        $itemDetails = [];

        foreach ($cart as $item) {
            $price = (float) $item['price'];
            $quantity = (int) $item['quantity'];
            $subtotal += $price * $quantity;

            $itemDetails[] = [
                'id' => (string) $item['id'],
                'price' => $price,
                'quantity' => $quantity,
                'name' => $item['name'],
            ];
        }

        // Calculate tax and delivery fee
        $tax = $subtotal * 0.1; // 10% tax
        $deliveryFee = $request->order_type === 'delivery' ? 10000 : 0;
        $total = $subtotal + $tax + $deliveryFee;

        // Add tax as separate item for Midtrans
        $itemDetails[] = [
            'id' => 'tax',
            'price' => $tax,
            'quantity' => 1,
            'name' => 'Pajak (10%)',
        ];

        // Add delivery fee as separate item for Midtrans if applicable
        if ($deliveryFee > 0) {
            $itemDetails[] = [
                'id' => 'delivery_fee',
                'price' => $deliveryFee,
                'quantity' => 1,
                'name' => 'Biaya Pengantaran',
            ];
        }

        // Create order
        $order = \App\Models\Order::create([
            'user_id' => Auth::id(),
            'order_type' => $request->order_type,
            'status' => 'pending',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'delivery_fee' => $deliveryFee,
            'total' => $total,
            'delivery_address' => $request->delivery_address,
            'delivery_phone' => $request->delivery_phone,
            'payment_method' => 'cash', // Default to cash, will be updated when payment method is selected
            'payment_status' => 'unpaid',
        ]);

        // Create order items and reduce stock
        DB::transaction(function () use ($cart, $order) {
            foreach ($cart as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['id'],
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'notes' => $item['notes'] ?? null,
                ]);

                // Reduce stock
                $menu = Menu::where('id', $item['id'])->lockForUpdate()->first();
                if ($menu) {
                    if ($menu->stock < $item['quantity']) {
                        throw new \Exception('Stok tidak cukup untuk menu: ' . $menu->name);
                    }
                    $menu->decrement('stock', $item['quantity']);
                    // Update availability
                    $menu->is_available = $menu->stock > 0;
                    $menu->save();
                }
            }
        });

        // Create payment record
        $payment = \App\Models\Payment::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'amount' => $total,
            'method' => 'midtrans',
            'status' => 'pending',
        ]);

        // Generate transaction ID using payment ID and timestamp for uniqueness
        $transactionID = 'PAY-' . $payment->id . '-' . time();

        // Update payment with transaction ID
        $payment->update(['payment_code' => $transactionID]);

        // Data Midtrans
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $transactionID,
                'gross_amount' => (int) $total,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?? '',
            ],
            'item_details' => $itemDetails,
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            // Update payment with snap token
            $payment->update(['notes' => json_encode(['snap_token' => $snapToken])]);

            return view('customer.cart.checkout', compact('snapToken', 'cart', 'total', 'order', 'payment'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

}
