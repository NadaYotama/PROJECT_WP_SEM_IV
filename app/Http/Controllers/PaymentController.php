<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function getSnapToken(Request $request)
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is3ds');

        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => 10000,
            ],
            'customer_details' => [
                'first_name' => 'Yoga',
                'last_name' => 'Meleniawan',
                'email' => 'yogameleniawan@example.com',
                'phone' => '08111222333',
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function methods()
    {
        $methods = [
            'credit_card' => 'Credit Card',
            'bank_transfer' => 'Bank Transfer',
            'e_wallet' => 'E-Wallet',
            'qris' => 'QRIS',
            'cod' => 'Cash on Delivery'
        ];

        return response()->json($methods);
    }

    public function customerTransactionHistory()
    {
        $payments = Payment::with(['order'])
                          ->where('user_id', Auth::id())
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);

        return view('customer.payments.history', compact('payments'));
    }

    public function customerMethods()
    {
        // Get the last pending order for the user
        $order = Order::where('user_id', Auth::id())
                      ->where('status', 'pending')
                      ->where('payment_status', 'unpaid')
                      ->latest()
                      ->first();

        if (!$order) {
            return redirect()->route('customer.cart.index')->with('error', 'Tidak ada pesanan yang perlu dibayar.');
        }

        $methods = [
            'cash' => [
                'name' => 'Tunai',
                'description' => 'Bayar langsung di kasir restoran',
                'available' => true,
                'type' => 'offline',
                'icon' => 'fas fa-money-bill-wave'
            ],
            'bank_transfer' => [
                'name' => 'Transfer Bank',
                'description' => 'Transfer ke rekening bank (BCA, Mandiri, BNI, BRI)',
                'available' => true,
                'type' => 'midtrans',
                'icon' => 'fas fa-university',
                'midtrans_type' => 'bank_transfer'
            ],
            'gopay' => [
                'name' => 'GoPay',
                'description' => 'Pembayaran via GoPay',
                'available' => true,
                'type' => 'midtrans',
                'icon' => 'fab fa-google-pay',
                'midtrans_type' => 'gopay'
            ],
            'ovo' => [
                'name' => 'OVO',
                'description' => 'Pembayaran via OVO',
                'available' => true,
                'type' => 'midtrans',
                'icon' => 'fas fa-mobile-alt',
                'midtrans_type' => 'ovo'
            ],
            'dana' => [
                'name' => 'DANA',
                'description' => 'Pembayaran via DANA',
                'available' => true,
                'type' => 'midtrans',
                'icon' => 'fas fa-wallet',
                'midtrans_type' => 'dana'
            ],
            'shopeepay' => [
                'name' => 'ShopeePay',
                'description' => 'Pembayaran via ShopeePay',
                'available' => true,
                'type' => 'midtrans',
                'icon' => 'fas fa-shopping-bag',
                'midtrans_type' => 'shopeepay'
            ],
            'qris' => [
                'name' => 'QRIS',
                'description' => 'Pembayaran via QRIS (GoPay, OVO, DANA, dll)',
                'available' => true,
                'type' => 'midtrans',
                'icon' => 'fas fa-qrcode',
                'midtrans_type' => 'qris'
            ],
            'credit_card' => [
                'name' => 'Kartu Kredit',
                'description' => 'Pembayaran dengan kartu kredit (biaya admin 2%)',
                'available' => true,
                'type' => 'midtrans',
                'icon' => 'fas fa-credit-card',
                'midtrans_type' => 'credit_card'
            ]
        ];

        return view('customer.payment.methods', compact('methods', 'order'));
    }

    public function initiate(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'method' => 'required|string',
            'amount' => 'required|numeric|min:0'
        ]);

        $order = Order::find($request->order_id);

        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'method' => $request->method,
            'status' => 'pending'
        ]);

        return response()->json(['payment' => $payment, 'message' => 'Payment initiated successfully']);
    }

    public function show(Payment $payment)
    {
        // Authorization removed - no policies defined
        return response()->json($payment);
    }

    public function verify(Request $request, Payment $payment)
    {
        // Authorization removed - no policies defined

        $payment->update(['status' => 'verified']);

        return response()->json(['message' => 'Payment verified successfully']);
    }

    public function downloadReceipt(Payment $payment)
    {
        // Authorization removed - no policies defined

        // Generate and return receipt PDF
        // Implementation depends on your PDF library
        return response()->json(['message' => 'Receipt download not implemented yet']);
    }

    public function resendNotification(Request $request, Payment $payment)
    {
        // Authorization removed - no policies defined

        // Send notification logic
        return response()->json(['message' => 'Notification resent successfully']);
    }

    public function cancel(Payment $payment)
    {
        // Authorization removed - no policies defined

        $payment->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Payment cancelled successfully']);
    }

    public function showProof(Payment $payment)
    {
        // Check if payment has a proof image
        if (!$payment->proof_image) {
            abort(404, 'Proof image not found');
        }

        // Return the proof image file from storage
        $path = storage_path('app/public/' . $payment->proof_image);

        if (!file_exists($path)) {
            abort(404, 'Proof image file not found');
        }

        return response()->file($path);
    }

    public function creditCard(Request $request)
    {
        // Handle credit card payment
        return $this->processPayment($request, 'credit_card');
    }

    public function bankTransfer(Request $request)
    {
        // Handle bank transfer payment
        return $this->processPayment($request, 'bank_transfer');
    }

    public function eWallet(Request $request)
    {
        // Handle e-wallet payment
        return $this->processPayment($request, 'e_wallet');
    }

    public function gopay(Request $request)
    {
        // Handle GoPay payment
        return $this->processPayment($request, 'gopay');
    }

    public function ovo(Request $request)
    {
        // Handle OVO payment
        return $this->processPayment($request, 'ovo');
    }

    public function dana(Request $request)
    {
        // Handle DANA payment
        return $this->processPayment($request, 'dana');
    }

    public function shopeepay(Request $request)
    {
        // Handle ShopeePay payment
        return $this->processPayment($request, 'shopeepay');
    }

    public function qris(Request $request)
    {
        // Handle QRIS payment
        return $this->processPayment($request, 'qris');
    }

    public function cod(Request $request)
    {
        // Handle cash on delivery
        return $this->processPayment($request, 'cod');
    }

    private function processPayment(Request $request, $method)
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is3ds');

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0'
        ]);

        $order = Order::find($request->order_id);

        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'method' => $method,
            'status' => 'pending'
        ]);

        // Midtrans integration for online payments
        if (in_array($method, ['credit_card', 'bank_transfer', 'gopay', 'ovo', 'dana', 'shopeepay', 'qris'])) {
            $params = [
                'transaction_details' => [
                    'order_id' => $payment->id,
                    'gross_amount' => $request->amount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone ?? '',
                ],
                'payment_type' => $method,
            ];

            try {
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $payment->update(['snap_token' => $snapToken]);
                return response()->json(['payment' => $payment, 'snap_token' => $snapToken]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        // For COD, update order status
        if ($method === 'cod') {
            $order->update([
                'payment_method' => 'cash',
                'payment_status' => 'unpaid', // Will be paid at cashier
                'status' => 'confirmed'
            ]);
        }

        return response()->json(['payment' => $payment, 'message' => 'Payment processed successfully']);
    }

    public function midtransWebhook(Request $request)
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('services.midtrans.is3ds');

        $notification = $request->all();

        // The order_id from Midtrans is our payment_code (PAY-{id})
        $orderId = $notification['order_id'];
        $statusCode = $notification['status_code'];
        $grossAmount = $notification['gross_amount'];

        // Extract payment ID from order_id (PAY-{id})
        $paymentId = str_replace('PAY-', '', $orderId);
        $payment = Payment::find($paymentId);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        // Update payment status
        if ($statusCode == 200) {
            $payment->update([
                'status' => 'success',
                'verified_at' => now()
            ]);

            // Update order status and payment status
            $payment->order->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
                'status' => 'confirmed' // Move to confirmed status after payment
            ]);

            // Kurangi stok menu setelah pembayaran berhasil
            foreach ($payment->order->items as $item) {
                $menu = \App\Models\Menu::find($item->menu_id);
                if ($menu) {
                    $menu->stock -= $item->quantity;
                    $menu->stock = max(0, $menu->stock); // Pastikan stok tidak negatif
                    $menu->save();
                }
            }
        } elseif ($statusCode == 201) {
            $payment->update(['status' => 'pending']);
        } elseif ($statusCode == 202) {
            $payment->update(['status' => 'failed']);
            $payment->order->update(['payment_status' => 'failed']);
        }

        return response()->json(['message' => 'Webhook processed successfully']);
    }
}
