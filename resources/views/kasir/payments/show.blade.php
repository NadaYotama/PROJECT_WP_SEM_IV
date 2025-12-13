@extends('layouts.kasir')

@section('title', 'Payment Detail - RM Padang Sederhana')

@section('content')
<div class="row">
    <!-- Payment Stats Cards -->
    <div class="col-lg-3 col-md-6">
        <div class="card revenue-card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="m-r-10">
                        <span class="text-white display-5"><i class="mdi mdi-cash-multiple"></i></span>
                    </div>
                    <div>
                        <h4 class="card-title text-white m-b-5">Rp {{ number_format($stats['orders_today'], 0, ',', '.') }}</h4>
                        <h6 class="card-subtitle text-white op-7 m-b-0">Orders Today</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="m-r-10">
                        <span class="text-warning display-5"><i class="mdi mdi-clock-outline"></i></span>
                    </div>
                    <div>
                        <h4 class="card-title m-b-5">{{ $stats['pending_orders'] }}</h4>
                        <h6 class="card-subtitle text-muted m-b-0">Pending Orders</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="m-r-10">
                        <span class="text-success display-5"><i class="mdi mdi-check-circle"></i></span>
                    </div>
                    <div>
                        <h4 class="card-title m-b-5">{{ $stats['ready_orders'] }}</h4>
                        <h6 class="card-subtitle text-muted m-b-0">Ready Orders</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="m-r-10">
                        <span class="text-info display-5"><i class="mdi mdi-credit-card"></i></span>
                    </div>
                    <div>
                        <h4 class="card-title m-b-5">{{ $stats['pending_payments'] }}</h4>
                        <h6 class="card-subtitle text-muted m-b-0">Pending Payments</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center m-b-20">
                    <h4 class="card-title">Payment Detail</h4>
                    <div class="d-flex">
                        <a href="{{ route('kasir.payments.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left"></i> Back to Payments
                        </a>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Payment Information</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Payment Code:</strong></td>
                                        <td>{{ $payment->payment_code }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Amount:</strong></td>
                                        <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Method:</strong></td>
                                        <td>
                                            @if($payment->method == 'cash')
                                                <span class="badge badge-success">Cash</span>
                                            @elseif(in_array($payment->method, ['transfer_bca', 'transfer_mandiri', 'transfer_bni']))
                                                <span class="badge badge-info">Bank Transfer</span>
                                            @elseif(in_array($payment->method, ['gopay', 'ovo', 'dana', 'shopeepay']))
                                                <span class="badge badge-primary">E-Wallet</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($payment->method) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            @if($payment->status == 'processing')
                                                <span class="badge badge-warning">Processing</span>
                                            @elseif($payment->status == 'success')
                                                <span class="badge badge-success">Success</span>
                                            @elseif($payment->status == 'failed')
                                                <span class="badge badge-danger">Failed</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($payment->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @if($payment->verified_at)
                                    <tr>
                                        <td><strong>Verified At:</strong></td>
                                        <td>{{ $payment->verified_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endif
                                    @if($payment->verifiedBy)
                                    <tr>
                                        <td><strong>Verified By:</strong></td>
                                        <td>{{ $payment->verifiedBy->name }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Order Information</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Order Number:</strong></td>
                                        <td>
                                            <a href="{{ route('kasir.orders.show', $payment->order->id) }}" class="text-decoration-none">
                                                {{ $payment->order->order_number }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Customer:</strong></td>
                                        <td>{{ $payment->order->customer_name ?? $payment->user->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Order Type:</strong></td>
                                        <td>{{ ucfirst($payment->order->order_type) }}</td>
                                    </tr>
                                    @if($payment->order->table_number)
                                    <tr>
                                        <td><strong>Table Number:</strong></td>
                                        <td>{{ $payment->order->table_number }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td><strong>Order Status:</strong></td>
                                        <td>
                                            @if($payment->order->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($payment->order->status == 'confirmed')
                                                <span class="badge badge-info">Confirmed</span>
                                            @elseif($payment->order->status == 'preparing')
                                                <span class="badge badge-primary">Preparing</span>
                                            @elseif($payment->order->status == 'ready')
                                                <span class="badge badge-success">Ready</span>
                                            @elseif($payment->order->status == 'completed')
                                                <span class="badge badge-success">Completed</span>
                                            @else
                                                <span class="badge badge-secondary">{{ ucfirst($payment->order->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="row m-t-20">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Order Items</h5>
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
                                            @forelse($payment->order->items as $item)
                                            <tr>
                                                <td>{{ $item->menu->name }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center">No items found.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3">Total</th>
                                                <th>Rp {{ number_format($payment->order->total_amount, 0, ',', '.') }}</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Actions -->
                @if($payment->status == 'processing')
                <div class="row m-t-20">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Payment Actions</h5>
                                <div class="d-flex justify-content-center">
                                    <form action="{{ route('kasir.payments.verify', $payment) }}" method="POST" class="d-inline m-r-10">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="success">
                                        <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Approve this payment?')">
                                            <i class="mdi mdi-check"></i> Approve Payment
                                        </button>
                                    </form>
                                    <form action="{{ route('kasir.payments.verify', $payment) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="failed">
                                        <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('Reject this payment?')">
                                            <i class="mdi mdi-close"></i> Reject Payment
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Notes -->
                @if($payment->notes)
                <div class="row m-t-20">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Notes</h5>
                                <p>{{ $payment->notes }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
