@extends('layouts.app')

@section('title', 'Pembayaran Pesanan #' . $order->order_number)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Pembayaran Pesanan #{{ $order->order_number }}</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Ringkasan Pesanan</h5>
                            <p><strong>Total Pembayaran:</strong></p>
                            <h3 class="text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</h3>
                        </div>
                        <div class="col-md-6">
                            <h5>Detail Pesanan</h5>
                            <p><strong>Jumlah Item:</strong> {{ $order->items->sum('quantity') }}</p>
                            <p><strong>Jenis Pesanan:</strong> {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}</p>
                        </div>
                    </div>

                    <form action="{{ route('orders.processPayment', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Metode Pembayaran</label>
                            <select name="payment_method" id="payment_method" class="form-select" required>
                                <option value="">Pilih metode pembayaran</option>
                                <option value="cash">Tunai</option>
                                <option value="transfer_bca">Transfer BCA</option>
                                <option value="transfer_mandiri">Transfer Mandiri</option>
                                <option value="transfer_bni">Transfer BNI</option>
                                <option value="gopay">GoPay</option>
                                <option value="ovo">OVO</option>
                                <option value="dana">DANA</option>
                                <option value="shopeepay">ShopeePay</option>
                                <option value="credit_card">Kartu Kredit</option>
                            </select>
                        </div>

                        <div id="proof-upload" class="mb-3" style="display: none;">
                            <label for="proof_image" class="form-label">Upload Bukti Pembayaran</label>
                            <input type="file" name="proof_image" id="proof_image" class="form-control" accept="image/*">
                            <div class="form-text">Upload gambar bukti pembayaran (maksimal 2MB)</div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Tambahkan catatan jika diperlukan"></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-credit-card"></i> Konfirmasi Pembayaran
                            </button>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('payment_method').addEventListener('change', function() {
    const proofUpload = document.getElementById('proof-upload');
    const proofInput = document.getElementById('proof_image');

    if (this.value === 'cash') {
        proofUpload.style.display = 'none';
        proofInput.removeAttribute('required');
    } else {
        proofUpload.style.display = 'block';
        proofInput.setAttribute('required', 'required');
    }
});
</script>
@endsection
