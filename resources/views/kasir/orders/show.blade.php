@extends('layouts.kasir')

@section('title', 'Order Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">Order Details - {{ $order->order_number }}</h4>
                        <div class="d-flex gap-2">
                            <button onclick="window.print()" class="btn btn-primary">
                                <i class="mdi mdi-printer"></i> Print Receipt
                            </button>
                            <a href="{{ route('kasir.orders.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Back to Orders
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Order Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p><strong>Order Number:</strong><br>{{ $order->order_number }}</p>
                                            <p><strong>Order Type:</strong><br>
                                                <span class="badge badge-{{ $order->order_type == 'dine_in' ? 'primary' : ($order->order_type == 'takeaway' ? 'warning' : 'info') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}
                                                </span>
                                            </p>
                                            @if($order->table_number)
                                            <p><strong>Table Number:</strong><br>{{ $order->table_number }}</p>
                                            @endif
                                        </div>
                                        <div class="col-sm-6">
                                            <p><strong>Status:</strong><br>
                                                <span class="badge badge-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </p>
                                            <p><strong>Payment Status:</strong><br>
                                                <span class="badge badge-{{ $order->payment_status == 'paid' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </p>
                                            <p><strong>Order Date:</strong><br>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Customer Information</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Name:</strong><br>{{ $order->customer_name }}</p>
                                    @if($order->customer_phone)
                                    <p><strong>Phone:</strong><br>{{ $order->customer_phone }}</p>
                                    @endif
                                    @if($order->notes)
                                    <p><strong>Notes:</strong><br>{{ $order->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Order Items</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Quantity</th>
                                                    <th>Price</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->items as $item)
                                                <tr>
                                                    <td>{{ $item->menu->name }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>Rp {{ number_format($item->price) }}</td>
                                                    <td>Rp {{ number_format($item->subtotal) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-right">Total:</th>
                                                    <th>Rp {{ number_format($order->total_amount) }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
@if(request('print'))
window.onload = function() {
    window.print();
}
@endif
</script>
@endsection
