@extends('layouts.app')

@section('title', 'Checkout Pesanan')

@section('content')
<div class="container py-5">
    @if(isset($snapToken))
        <!-- Payment Section -->
        <div class="row">
            <div class="col-md-8">
                <h3>Pembayaran</h3>
                <div class="card">
                    <div class="card-body">
                        <button id="pay-button" class="btn btn-success btn-lg">Bayar Sekarang</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <h4>Ringkasan Pesanan</h4>
                <div class="card">
                    <div class="card-body">
                        @foreach($cart as $item)
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                <div class="flex-grow-1">
                                    <div>{{ $item['name'] }} x{{ $item['quantity'] }}</div>
                                    <small class="text-muted">Rp {{ number_format($item['price'] * $item['quantity']) }}</small>
                                </div>
                            </div>
                        @endforeach
                        <hr>
                        @php
                            $subtotal = 0;
                            foreach($cart as $item) {
                                $subtotal += $item['price'] * $item['quantity'];
                            }
                            $tax = $subtotal * 0.1;
                            $deliveryFee = (isset($order) && $order->order_type === 'delivery') ? 10000 : 0;
                            $grandTotal = $subtotal + $tax + $deliveryFee;
                        @endphp
                        <div class="d-flex justify-content-between">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($subtotal) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Pajak (10%)</span>
                            <span>Rp {{ number_format($tax) }}</span>
                        </div>
                        @if($deliveryFee > 0)
                        <div class="d-flex justify-content-between">
                            <span>Biaya Pengantaran</span>
                            <span>Rp {{ number_format($deliveryFee) }}</span>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between font-weight-bold">
                            <span>Total</span>
                            <span>Rp {{ number_format($grandTotal) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Order Details Form -->
        <h3>Detail Pesanan</h3>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <form id="checkout-form" action="{{ route('customer.cart.checkout.process') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="order_type" class="form-label">Jenis Pesanan</label>
                        <select id="order_type" name="order_type" class="form-select" required>
                            <option value="" disabled selected>Pilih jenis pesanan</option>
                            <option value="dine_in" {{ old('order_type') == 'dine_in' ? 'selected' : '' }}>Dine In</option>
                            <option value="delivery" {{ old('order_type') == 'delivery' ? 'selected' : '' }}>Delivery</option>
                            <option value="takeaway" {{ old('order_type') == 'takeaway' ? 'selected' : '' }}>Takeaway</option>
                        </select>
                        @error('order_type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="delivery-details" style="display: none;">
                        <div class="mb-3">
                            <label for="delivery_address" class="form-label">Alamat Pengantaran</label>
                            <textarea id="delivery_address" name="delivery_address" class="form-control" rows="3">{{ old('delivery_address') }}</textarea>
                            @error('delivery_address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="delivery_phone" class="form-label">Nomor Telepon</label>
                            <input type="text" id="delivery_phone" name="delivery_phone" class="form-control" value="{{ old('delivery_phone') }}">
                            @error('delivery_phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Stock Validation Warning -->
                    <div id="stock-warning" class="alert alert-danger" style="display:none;"></div>

                    <button type="submit" class="btn btn-primary" id="checkout-submit">Lanjutkan ke Pembayaran</button>
                </form>
            </div>
            <div class="col-md-4">
                <h4>Ringkasan Keranjang</h4>
                <div class="card">
                    <div class="card-body">
                        @foreach($cart as $item)
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="me-3" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                <div class="flex-grow-1">
                                    <div>{{ $item['name'] }} x{{ $item['quantity'] }}</div>
                                    <small class="text-muted">Rp {{ number_format($item['price'] * $item['quantity']) }}</small>
                                </div>
                            </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between font-weight-bold">
                            <span>Total</span>
                            <span>Rp {{ number_format($total) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@if(isset($snapToken))
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
<script>
    document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                console.log('success');
                console.log(result);
                alert('Pembayaran berhasil!');

                // Clear cart from session via AJAX
                fetch('{{ route("customer.cart.clear") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                }).then(() => {
                    // Redirect to success page
                    window.location.href = '{{ route("customer.orders.index") }}';
                });
            },
            onPending: function(result){
                console.log('pending');
                console.log(result);
                alert('Pembayaran sedang diproses. Silakan tunggu konfirmasi.');
                // Redirect to orders page
                window.location.href = '{{ route("customer.orders.index") }}';
            },
            onError: function(result){
                console.log('error');
                console.log(result);
                alert('Pembayaran gagal: ' + result.status_message);
            }
        });
    };
</script>
@else
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderTypeSelect = document.getElementById('order_type');
        const deliveryDetails = document.getElementById('delivery_details');

        function toggleDeliveryDetails() {
            if (orderTypeSelect.value === 'delivery') {
                deliveryDetails.style.display = 'block';
                document.getElementById('delivery_address').setAttribute('required', 'required');
                document.getElementById('delivery_phone').setAttribute('required', 'required');
            } else {
                deliveryDetails.style.display = 'none';
                document.getElementById('delivery_address').removeAttribute('required');
                document.getElementById('delivery_phone').removeAttribute('required');
            }
        }

        orderTypeSelect.addEventListener('change', toggleDeliveryDetails);
        toggleDeliveryDetails();

        // Stock validation
        let cartItems = @json($cart);
        let stockError = false;
        let warningDiv = document.getElementById('stock-warning');
        let submitBtn = document.getElementById('checkout-submit');
        let warningMsg = '';
        cartItems.forEach(function(item) {
            if (item.quantity > item.stock) {
                stockError = true;
                warningMsg += `Stok menu <b>${item.name}</b> hanya tersedia <b>${item.stock}</b>, Anda memesan <b>${item.quantity}</b>.<br>`;
            }
        });
        if (stockError) {
            warningDiv.innerHTML = warningMsg;
            warningDiv.style.display = 'block';
            submitBtn.disabled = true;
        } else {
            warningDiv.style.display = 'none';
            submitBtn.disabled = false;
        }
    });
</script>
@endif
@endsection
