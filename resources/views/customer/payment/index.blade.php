<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .payment-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 25px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #f9f9f9;
        }
        .btn-pay {
            background-color: #d32f2f;
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            font-weight: 600;
            width: 100%;
        }
        .btn-pay:hover {
            background-color: #b71c1c;
            color: white;
        }
    </style>
</head>
<body>

<div class="payment-container">
    <h4>Proses Pembayaran</h4>
    <p>Order ID: <strong>{{ $orderId }}</strong></p>
    <p>Total yang harus dibayar: <strong>Rp {{ number_format($grandTotal) }}</strong></p>

    <button id="pay-button" class="btn-pay">Bayar Sekarang</button>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    const payButton = document.getElementById('pay-button');

    payButton.addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                alert('Pembayaran berhasil! ID transaksi: ' + result.transaction_id);
                // Redirect or show success message
                window.location.href = '{{ route("customer.dashboard") }}';
            },
            onPending: function(result){
                alert('Pembayaran pending! Silakan lanjutkan pembayaran.');
                window.location.href = '{{ route("customer.dashboard") }}';
            },
            onError: function(result){
                alert('Pembayaran gagal! Silakan coba lagi.');
            },
            onClose: function(){
                alert('Anda menutup popup pembayaran tanpa menyelesaikan pembayaran.');
            }
        });
    });
</script>

</body>
</html>
