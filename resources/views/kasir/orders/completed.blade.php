@extends('layouts.kasir')

@section('title', 'Completed Orders')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">Completed Orders</h4>
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
                                <a href="{{ route('kasir.orders.completed') }}" class="btn btn-secondary">Clear</a>
                            </form>
                        </div>
                    </div>

                    @if($completedOrders->count() > 0)
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
                                        <th>Completed At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($completedOrders as $order)
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
                                            <span class="badge badge-success">Completed</span>
                                        </td>
                                        <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('kasir.orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                                                <button type="button" class="btn btn-sm btn-secondary" onclick="printReceipt({{ $order->id }})">Print</button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $completedOrders->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-check-circle-outline" style="font-size: 4rem; color: #ccc;"></i>
                            <h5 class="mt-3">No Completed Orders</h5>
                            <p class="text-muted">Completed orders will appear here.</p>
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
function printReceipt(orderId) {
    // Open print window or redirect to print page
    window.open('{{ route("kasir.orders.show", ":orderId") }}'.replace(':orderId', orderId) + '?print=1', '_blank');
}
</script>
@endsection
