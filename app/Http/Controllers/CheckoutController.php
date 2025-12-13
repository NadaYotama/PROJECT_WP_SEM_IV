<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->middleware('auth');
        $this->midtrans = $midtrans;
    }

    /**
     * Process checkout form, create order and generate Midtrans snap token
     */
    public function process(Request $request)
    {
        $cart = session('cart', []);

        if (count($cart) === 0) {
            return redirect()->route('customer.cart.index')->with('error', 'Keranjang kosong.');
        }

        $request->validate([
            'order_type' => 'required|in:dine_in,delivery,takeaway',
            'delivery_address' => 'required_if:order_type,delivery',
            'delivery_phone' => 'required_if:order_type,delivery',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $orderItems = [];

            foreach ($cart as $itemId => $item) {
                $itemSubtotal = $item['price'] * $item['quantity'];
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'menu_id' => $itemId,
                    'item_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $itemSubtotal,
                    'notes' => $item['notes'] ?? null,
                ];
            }

            $tax = $subtotal * 0.10; // 10% tax
            $serviceFee = $subtotal * 0.05; // 5% service fee
            $deliveryFee = $request->order_type === 'delivery' ? 15000 : 0;
            $total = $subtotal + $tax + $serviceFee + $deliveryFee;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_type' => $request->order_type,
                'status' => 'pending',
                'subtotal' => $subtotal,
                'tax' => $tax,
                'service_fee' => $serviceFee,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
                'delivery_address' => $request->delivery_address,
                'delivery_phone' => $request->delivery_phone,
                'payment_status' => 'unpaid',
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            // Generate Midtrans snap token
            $transactionDetails = [
                'order_id' => $order->id,
                'gross_amount' => $order->total,
            ];

            $customerDetails = [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => $request->delivery_phone ?? '',
            ];

            $snapToken = $this->midtrans->createSnapToken($transactionDetails, $customerDetails);

            DB::commit();

            // Clear the cart session
            session()->forget('cart');

            return view('customer.orders.payment', compact('order', 'snapToken'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('customer.cart.index')->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Handle Midtrans payment notification (webhook)
     */
    public function notification(Request $request)
    {
        // Retrieve notification payload from Midtrans
        $notification = $request->all();

        // Optional: You can verify the notification signature here for security

        // Get the order by the order_id sent in notification
        $orderId = $notification['order_id'] ?? null;

        if (!$orderId) {
            return response()->json(['message' => 'order_id not provided'], 400);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Process the transaction status sent by Midtrans
        $transactionStatus = $notification['transaction_status'] ?? '';
        $fraudStatus = $notification['fraud_status'] ?? '';

        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus === 'challenge') {
                    // Payment is challenged by FDS
                    $order->status = 'challenge';
                    $order->payment_status = 'pending';
                } elseif ($fraudStatus === 'accept') {
                    // Payment is successful
                    $order->status = 'processing';
                    $order->payment_status = 'paid';
                }
                break;
            case 'settlement':
                $order->status = 'processing';
                $order->payment_status = 'paid';
                break;
            case 'pending':
                $order->status = 'pending';
                $order->payment_status = 'unpaid';
                break;
            case 'deny':
                $order->status = 'denied';
                $order->payment_status = 'unpaid';
                break;
            case 'expire':
                $order->status = 'expired';
                $order->payment_status = 'unpaid';
                break;
            case 'cancel':
                $order->status = 'cancelled';
                $order->payment_status = 'unpaid';
                break;
            default:
                // Unknown status, do nothing or log
                break;
        }

        $order->save();

        // Respond with 200 OK to acknowledge receipt of notification
        return response()->json(['message' => 'Notification processed']);
    }
}
