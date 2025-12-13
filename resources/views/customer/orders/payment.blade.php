<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Proses Pembayaran - Rumah Makan Padang</title>
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container py-5">
        <h3>Proses Pembayaran</h3>
        <p>Pesanan Anda: #{{ $order->id }}</p>
        <p>Total pembayaran: Rp {{ number_format($order->total) }}</p>

        <button id="pay-button" class="btn btn-primary btn-lg">Bayar Sekarang</button>

        <script>
            var payButton = document.getElementById('pay-button');
            payButton.addEventListener('click', function () {
                window.snap.pay('{{ $snapToken }}', {
                    onSuccess: function(result){
                        alert("Pembayaran berhasil!");
                        window.location.href = "{{ route('customer.orders.index') }}";
                    },
                    onPending: function(result){
                        alert("Pembayaran tertunda. Silakan selesaikan pembayaran di halaman berikut.");
                        window.location.href = "{{ route('customer.orders.index') }}";
                    },
                    onError: function(result){
                        alert("Pembayaran gagal: " + result.status_message);
                        window.location.href = "{{ route('customer.cart.index') }}";
                    },
                    onClose: function(){
                        alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
                    }
                });
            });
        </script>
    </div>
</body>
</html>
