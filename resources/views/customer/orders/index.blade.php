<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@if(isset($currentOrdersView) && $currentOrdersView) Pesanan Aktif @else Daftar Pesanan @endif - Rumah Makan Padang</title>
    <link rel="icon" type="image/jpeg" sizes="16x16" href="{{ asset('assets/images/logorumahmakan.jpg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .order-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: box-shadow 0.3s ease;
        }
        .order-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .order-status {
            font-weight: 600;
            text-transform: capitalize;
        }
        .payment-status-unpaid {
            color: #d32f2f;
            font-weight: 600;
        }
        .payment-status-paid {
            color: #388e3c;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                @if(isset($currentOrdersView) && $currentOrdersView)
                    Pesanan Aktif
                @elseif(isset($historyOrdersView) && $historyOrdersView)
                    Riwayat Pesanan
                @else
                    Daftar Pesanan
                @endif
            </h2>
            <div class="btn-group" role="group">
                <a href="{{ route('customer.orders.current') }}" class="btn btn-outline-primary @if(isset($currentOrdersView) && $currentOrdersView) active @endif">Pesanan Aktif</a>
                <a href="{{ route('customer.orders.history') }}" class="btn btn-outline-primary @if(isset($historyOrdersView) && $historyOrdersView) active @endif">Riwayat Pesanan</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="GET" action="@if(isset($currentOrdersView) && $currentOrdersView) {{ route('customer.orders.current') }} @elseif(isset($historyOrdersView) && $historyOrdersView) {{ route('customer.orders.history') }} @else {{ route('customer.orders.index') }} @endif" class="mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="Cari nomor pesanan atau nama pelanggan" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">-- Status Pesanan --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="delivering" {{ request('status') == 'delivering' ? 'selected' : '' }}>Delivering</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="order_type" class="form-select">
                        <option value="">-- Jenis Pesanan --</option>
                        <option value="dine_in" {{ request('order_type') == 'dine_in' ? 'selected' : '' }}>Makan di Tempat</option>
                        <option value="delivery" {{ request('order_type') == 'delivery' ? 'selected' : '' }}>Pengantaran</option>
                        <option value="takeaway" {{ request('order_type') == 'takeaway' ? 'selected' : '' }}>Bawa Pulang</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="payment_status" class="form-select">
                        <option value="">-- Status Pembayaran --</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Sudah Bayar</option>
                        <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i> Filter</button>
                </div>
                <div class="col-md-1">
                    <a href="@if(isset($currentOrdersView) && $currentOrdersView) {{ route('customer.orders.current') }} @elseif(isset($historyOrdersView) && $historyOrdersView) {{ route('customer.orders.history') }} @else {{ route('customer.orders.index') }} @endif" class="btn btn-outline-secondary w-100" title="Reset Filter"><i class="fas fa-sync"></i></a>
                </div>
            </div>
        </form>

        @if($orders->count() > 0)
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5>Pesanan #{{ $order->order_number }}</h5>
                        <span class="order-status badge 
                            @if($order->status == 'cancelled') bg-danger
                            @elseif($order->status == 'completed') bg-success
                            @else bg-warning text-dark
                            @endif
                        ">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <p>
                        <strong>Tanggal:</strong> {{ $order->created_at->format('d/m/Y H:i') }} <br>
                        <strong>Jenis Pesanan:</strong> {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}<br>
                        <strong>Total:</strong> Rp {{ number_format($order->total, 0, ',', '.') }}<br>
                        <strong>Status Pembayaran:</strong>
                            @if($order->payment_status === 'paid')
                                <span class="payment-status-paid">Sudah Bayar</span>
                            @else
                                <span class="payment-status-unpaid">Belum Bayar</span>
                            @endif
                    </p>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                    @if($order->payment_status !== 'paid')
                        <a href="{{ route('orders.checkout', $order->id) }}" class="btn btn-success btn-sm">Bayar Sekarang</a>
                    @endif
                </div>
            @endforeach

            <div class="mt-4">
                {{ $orders->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center mt-5">
                <h4>Belum ada pesanan.</h4>
                <a href="{{ route('customer.menu.index') }}" class="btn btn-primary mt-3"><i class="fas fa-utensils"></i> Lihat Menu</a>
            </div>
        @endif
    </div>

    <script>
        // Handle back button to redirect to customer dashboard
        (function() {
            // If user came from admin area, manipulate history to make back go to dashboard
            if (document.referrer && document.referrer.includes('/admin')) {
                // Replace current history with dashboard URL
                history.replaceState({page: 'dashboard'}, 'Dashboard', '{{ route('customer.dashboard') }}');
                // Push orders page back to history
                history.pushState({page: 'orders'}, document.title, location.href);
            } else {
                // Normal case: ensure back goes to dashboard
                history.replaceState({page: 'dashboard'}, 'Dashboard', '{{ route('customer.dashboard') }}');
                history.pushState({page: 'orders'}, document.title, location.href);
            }

            window.addEventListener('popstate', function(event) {
                // When back is pressed, redirect to dashboard
                if (event.state && event.state.page === 'dashboard') {
                    window.location.href = '{{ route('customer.dashboard') }}';
                }
            });
        })();
    </script>
</body>
</html>
