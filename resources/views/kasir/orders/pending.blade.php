@extends('layouts.kasir')

@section('title', 'Pending Orders')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">Pending Orders</h4>
                        <div class="d-flex gap-2">
                            <form method="GET" class="d-flex gap-2">
                                <select name="order_type" class="form-control">
                                    <option value="">All Order Types</option>
                                    <option value="dine_in" {{ request('order_type') == 'dine_in' ? 'selected' : '' }}>Dine In</option>
                                    <option value="takeaway" {{ request('order_type') == 'takeaway' ? 'selected' : '' }}>Takeaway</option>
                                    <option value="delivery" {{ request('order_type') == 'delivery' ? 'selected' : '' }}>Delivery</option>
                                </select>
                                <input type="text" name="search" class="form-control" placeholder="Search orders..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('kasir.orders.pending') }}" class="btn btn-secondary">Clear</a>
                            </form>
                        </div>
                    </div>

                    @if($pendingOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Type</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingOrders as $order)
                                    <tr>
                                        <td>
                                            <strong>{{ $order->order_number }}</strong>
                                            @if($order->table_number)
                                                <br><small class="text-muted">Table: {{ $order->table_number }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $order->customer_name }}
                                            @if($order->customer_phone)
                                                <br><small class="text-muted">{{ $order->customer_phone }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $order->order_type == 'dine_in' ? 'primary' : ($order->order_type == 'takeaway' ? 'warning' : 'info') }}">
                                                {{ ucfirst(str_replace('_', ' ', $order->order_type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @foreach($order->items as $item)
                                                {{ $item->menu->name }} ({{ $item->quantity }})<br>
                                            @endforeach
                                        </td>
                                        <td>Rp {{ number_format($order->total_amount) }}</td>
                                        <td>
                                            <select class="form-control status-select" data-order-id="{{ $order->id }}">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                                <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                                <option value="delivering" {{ $order->status == 'delivering' ? 'selected' : '' }}>Delivering</option>
                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('kasir.orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                                                <button type="button" class="btn btn-sm btn-success" onclick="processOrder({{ $order->id }})">Process</button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $pendingOrders->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-clipboard-list-outline" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="mt-3">No Pending Orders</h5>
                            <p class="text-muted">All orders have been processed.</p>
                            <a href="{{ route('kasir.orders.index') }}" class="btn btn-primary">View All Orders</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Status update for orders
    $('.status-select').on('change', function() {
        var orderId = $(this).data('order-id');
        var newStatus = $(this).val();

        if (confirm('Are you sure you want to change the status to ' + newStatus + '?')) {
            $.ajax({
                url: '{{ route("kasir.orders.updateStatus", ":orderId") }}'.replace(':orderId', orderId),
                method: 'POST',
                data: {
                    status: newStatus,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success('Order status updated successfully');
                    location.reload();
                },
                error: function() {
                    toastr.error('Error updating order status');
                }
            });
        } else {
            // Reset the select to previous value
            $(this).val($(this).find('option[selected]').val());
        }
    });
});

function processOrder(orderId) {
    if (confirm('Process this order?')) {
        $.ajax({
            url: '{{ route("kasir.orders.updateStatus", ":orderId") }}'.replace(':orderId', orderId),
            method: 'POST',
            data: {
                status: 'confirmed',
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success('Order processed successfully');
                location.reload();
            },
            error: function() {
                toastr.error('Error processing order');
            }
        });
    }
}
</script>
@endsection
