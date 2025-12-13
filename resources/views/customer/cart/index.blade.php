<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Rumah Makan Padang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .cart-item {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .cart-total {
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 30px;
            margin-top: 30px;
        }
        .btn-cart {
            background: #d32f2f;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
        }
        .btn-cart:hover {
            background: #b71c1c;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h2>Keranjang Belanja</h2>
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        @if(count($cart) > 0)
        <div class="row">
            <div class="col-md-8">
                @foreach($cart as $itemId => $item)
                <div class="cart-item d-flex align-items-center">
                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                    <div class="flex-grow-1 ms-3">
                        <h5>{{ $item['name'] }}</h5>
                        <p>Harga: Rp {{ number_format($item['price']) }}</p>
                        @if(!empty($item['notes']))
                        <p>Catatan: {{ $item['notes'] }}</p>
                        @endif
                        <form method="POST" action="{{ route('customer.cart.update', $itemId) }}" class="d-flex align-items-center">
                            @csrf
                            @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="10" class="form-control form-control-sm me-2" style="width: 80px;">
                            <input type="text" name="notes" value="{{ $item['notes'] }}" placeholder="Catatan (opsional)" maxlength="500" class="form-control form-control-sm me-2" style="max-width: 200px;">
                            <button type="submit" class="btn btn-sm btn-primary me-2">Update</button>
                        </form>
                    </div>
                    <div>
                        <strong>Rp {{ number_format($item['price'] * $item['quantity']) }}</strong>
                    </div>
                    <div class="ms-3">
                        <form method="POST" action="{{ route('customer.cart.remove', $itemId) }}" onsubmit="return confirm('Hapus item ini dari keranjang?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                @endforeach
                <form method="POST" action="{{ route('customer.cart.clear') }}" onsubmit="return confirm('Kosongkan seluruh keranjang?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger mt-3">Kosongkan Keranjang</button>
                </form>
            </div>
            <div class="col-md-4">
                <div class="cart-total">
                    <h4>Ringkasan Pesanan</h4>
                    <hr>
                    <p>Subtotal ({{ count($cart) }} item): Rp {{ number_format($total) }}</p>
                    <p>Pajak (10%): Rp {{ number_format($total * 0.10) }}</p>
                    <hr>
                    <h5>Total: Rp {{ number_format($total * 1.10) }}</h5>
                    <form id="checkout-form" action="{{ route('customer.cart.checkout.process') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="order_type" class="form-label">Jenis Pesanan</label>
                            <select name="order_type" id="order_type" class="form-select" required>
                                <option value="" selected disabled>Pilih salah satu</option>
                                <option value="dine_in">Dine In</option>
                                <option value="delivery">Delivery Online</option>
                                <option value="takeaway">Take Away</option>
                            </select>
                        </div>

                        <div id="delivery_fields" class="mb-3" style="display: none;">
                            <label for="delivery_address" class="form-label">Alamat Pengantaran</label>
                            <textarea name="delivery_address" id="delivery_address" class="form-control" placeholder="Alamat pengantaran"></textarea>
                            <label for="delivery_phone" class="form-label mt-2">Nomor Telepon</label>
                            <input type="text" name="delivery_phone" id="delivery_phone" class="form-control" placeholder="Nomor telepon untuk pengantaran" />
                        </div>

                        <!-- Stock Validation Warning -->
                        <div id="stock-warning" class="alert alert-danger" style="display:none;"></div>

                        <button type="submit" class="btn btn-cart w-100 mt-3" id="checkout-submit">
                            <i class="fas fa-credit-card"></i> Lanjut ke Checkout
                        </button>
                    </form>

                    <script>
                        const orderTypeSelect = document.getElementById('order_type');
                        const deliveryFields = document.getElementById('delivery_fields');
                        const deliveryAddress = document.getElementById('delivery_address');
                        const deliveryPhone = document.getElementById('delivery_phone');
                        const checkoutForm = document.getElementById('checkout-form');
                        const submitBtn = document.getElementById('checkout-submit');
                        const warningDiv = document.getElementById('stock-warning');
                        let cartItems = @json($cart);
                        let stockError = false;
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

                        function toggleDeliveryFields() {
                            if (orderTypeSelect.value === 'delivery') {
                                deliveryFields.style.display = 'block';
                            } else {
                                deliveryFields.style.display = 'none';
                                deliveryAddress.value = '';
                                deliveryPhone.value = '';
                            }
                        }

                        orderTypeSelect.addEventListener('change', toggleDeliveryFields);

                        checkoutForm.addEventListener('submit', function(e) {
                            if (!orderTypeSelect.value) {
                                e.preventDefault();
                                alert('Silakan pilih jenis pesanan.');
                                orderTypeSelect.focus();
                                return false;
                            }
                            if (orderTypeSelect.value === 'delivery') {
                                if (!deliveryAddress.value.trim()) {
                                    e.preventDefault();
                                    alert('Silakan isi alamat pengantaran.');
                                    deliveryAddress.focus();
                                    return false;
                                }
                                if (!deliveryPhone.value.trim()) {
                                    e.preventDefault();
                                    alert('Silakan isi nomor telepon pengantaran.');
                                    deliveryPhone.focus();
                                    return false;
                                }
                            }
                        });

                        // Initialize on page load
                        window.onload = toggleDeliveryFields;
                    </script>
                    <a href="{{ route('customer.menu.index') }}" class="btn btn-outline-secondary w-100 mt-2"><i class="fas fa-utensils"></i> Tambah Menu</a>
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-primary w-100 mt-2"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </div>
            </div>
        </div>
        @else
        <div class="text-center mt-5">
            <h3>Keranjang Kosong</h3>
            <p>Belum ada menu yang ditambahkan ke keranjang</p>
            <a href="{{ route('customer.menu.index') }}" class="btn btn-primary">Lihat Menu</a>
        </div>
        @endif
    </div>
</body>
</html>
