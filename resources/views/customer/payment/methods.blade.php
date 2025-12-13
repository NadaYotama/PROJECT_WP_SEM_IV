<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Metode Pembayaran - Rumah Makan Padang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .payment-method-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: box-shadow 0.3s ease;
            background: white;
        }
        .payment-method-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .payment-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .method-name {
            font-weight: 600;
            margin-bottom: 10px;
        }
        .method-description {
            color: #666;
            font-size: 0.9rem;
        }
        .status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Metode Pembayaran</h2>
                <p class="text-muted mb-4">Pilih metode pembayaran yang tersedia untuk pesanan Anda</p>
            </div>
        </div>

        <div class="row">
            @foreach($methods as $key => $method)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="payment-method-card position-relative">
                    @if($method['available'])
                        <span class="badge bg-success status-badge">Tersedia</span>
                    @else
                        <span class="badge bg-warning status-badge">Tidak Tersedia</span>
                    @endif

                    <div class="text-center">
                        <div class="payment-icon text-primary">
                            <i class="{{ $method['icon'] }}"></i>
                        </div>

                        <h5 class="method-name">{{ $method['name'] }}</h5>
                        <p class="method-description">{{ $method['description'] }}</p>

                        @if($method['available'])
                            <div class="mt-3">
                                @if($method['type'] === 'midtrans')
                                    <button class="btn btn-primary btn-sm" onclick="selectPaymentMethod('{{ $key }}', '{{ $method['midtrans_type'] }}')">
                                        <i class="fas fa-credit-card"></i> Pilih Metode
                                    </button>
                                @else
                                    <button class="btn btn-success btn-sm" onclick="selectOfflinePayment('{{ $key }}')">
                                        <i class="fas fa-money-bill-wave"></i> Pilih Metode
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="mt-3">
                                <small class="text-warning">
                                    <i class="fas fa-exclamation-triangle"></i> Sedang dalam pemeliharaan
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle"></i> Informasi Penting</h6>
                    <ul class="mb-0">
                        <li>Pembayaran tunai dapat dilakukan langsung di kasir restoran</li>
                        <li>Untuk transfer bank, pastikan menggunakan nomor rekening yang benar</li>
                        <li>E-wallet memerlukan aplikasi yang terinstall di perangkat Anda</li>
                        <li>Pembayaran kartu kredit dikenakan biaya admin 2%</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12 text-center">
                <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
    <script>
        function selectPaymentMethod(key, midtransType) {
            const orderId = '{{ $order->id }}';
            const amount = '{{ $order->total }}';
            const csrfToken = '{{ csrf_token() }}';

            // Make AJAX request to get snap token
            fetch('/payments/' + midtransType, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    order_id: orderId,
                    amount: amount
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.snap_token) {
                    // Initiate Midtrans Snap payment
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result){
                            alert("Pembayaran berhasil!");
                            window.location.href = "{{ route('customer.orders.index') }}";
                        },
                        onPending: function(result){
                            alert("Pembayaran tertunda. Silakan selesaikan pembayaran.");
                            window.location.href = "{{ route('customer.orders.index') }}";
                        },
                        onError: function(result){
                            alert("Pembayaran gagal: " + result.status_message);
                        },
                        onClose: function(){
                            alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
                        }
                    });
                } else {
                    alert('Gagal mendapatkan token pembayaran: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses pembayaran.');
            });
        }

        function selectOfflinePayment(key) {
            const orderId = '{{ $order->id }}';
            const amount = '{{ $order->total }}';
            const csrfToken = '{{ csrf_token() }}';

            // Make AJAX request to process COD payment
            fetch('/payments/cod', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    order_id: orderId,
                    amount: amount
                })
            })
            .then(response => response.json())
            .then(data => {
                alert("Pesanan dikonfirmasi. Silakan bayar di kasir.");
                window.location.href = "{{ route('customer.orders.index') }}";
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memproses pembayaran.');
            });
        }
    </script>
</body>
</html>
