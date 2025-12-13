@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Detail Pesanan #{{ $order->order_number }}</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Tanggal Pesanan:</strong><br>
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Status Pesanan:</strong><br>
                            <span class="badge
                                @if($order->status == 'cancelled') bg-danger
                                @elseif($order->status == 'completed') bg-success
                                @else bg-warning text-dark
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Jenis Pesanan:</strong><br>
                            {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}
                        </div>
                        <div class="col-md-6">
                            <strong>Status Pembayaran:</strong><br>
                            <span class="badge
                                @if($order->payment_status == 'paid') bg-success
                                @else bg-danger
                                @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    @if($order->order_type == 'delivery')
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Alamat Pengantaran:</strong><br>
                            {{ $order->delivery_address }}
                        </div>
                        <div class="col-md-6">
                            <strong>Nomor Telepon:</strong><br>
                            {{ $order->delivery_phone }}
                        </div>
                    </div>
                    @endif

                    <h5 class="mt-4">Detail Menu</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="border p-3">
                                <div class="d-flex justify-content-between">
                                    <span>Subtotal:</span>
                                    <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Pajak (10%):</span>
                                    <span>Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                                </div>
                                @if($order->delivery_fee > 0)
                                <div class="d-flex justify-content-between">
                                    <span>Biaya Pengantaran:</span>
                                    <span>Rp {{ number_format($order->delivery_fee, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                <hr>
                                <div class="d-flex justify-content-between font-weight-bold">
                                    <span>Total:</span>
                                    <span>Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            @if($order->payment_status !== 'paid' && $order->status !== 'cancelled')
            <div class="card">
                <div class="card-header">
                    <h5>Pembayaran</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('orders.checkout', $order) }}" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                    </a>
                </div>
            </div>
            @endif

            @if($order->payment)
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Informasi Pembayaran</h5>
                </div>
                <div class="card-body">
                    <p><strong>Metode:</strong> {{ ucfirst(str_replace('_', ' ', $order->payment->method)) }}</p>
                    <p><strong>Status:</strong>
                        <span class="badge
                            @if($order->payment->status == 'success') bg-success
                            @elseif($order->payment->status == 'pending') bg-warning
                            @else bg-danger
                            @endif">
                            {{ ucfirst($order->payment->status) }}
                        </span>
                    </p>
                    @if($order->payment->proof_image)
                    <p><strong>Bukti Pembayaran:</strong></p>
                    <img src="{{ asset('storage/' . $order->payment->proof_image) }}" class="img-fluid" alt="Bukti Pembayaran">
                    @endif
                </div>
            </div>
            @endif

            <div class="card mt-3">
                <div class="card-body">
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('customer.orders.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
                        </a>
                    @elseif(auth()->user()->role === 'kasir')
                        <a href="{{ route('kasir.orders.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
                        </a>
                    @elseif(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
                        </a>
                    @elseif(auth()->user()->role === 'manager')
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
