@extends('layouts.kasir')

@section('content')
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 d-flex no-block align-items-center">
                        <h4 class="page-title">Dashboard Kasir</h4>
                        <div class="ml-auto text-right">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('kasir.dashboard') }}">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Ringkasan</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Payment Stats Cards -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="card payment-card bg-warning text-white">
                            <div class="card-body text-center">
                                <h4>{{ $stats['orders_today'] }}</h4>
                                <p>Pesanan Hari Ini</p>
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card payment-card bg-danger text-white">
                            <div class="card-body text-center">
                                <h4>{{ $stats['pending_orders'] }}</h4>
                                <p>Pending Orders</p>
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card payment-card bg-success text-white">
                            <div class="card-body text-center">
                                <h4>{{ $stats['ready_orders'] }}</h4>
                                <p>Ready Orders</p>
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card payment-card bg-info text-white">
                            <div class="card-body text-center position-relative">
                                <h4>{{ $stats['pending_payments'] }}</h4>
                                <p>Pending Payments</p>
                                <i class="fas fa-credit-card fa-2x"></i>
                                @if($stats['pending_payments'] > 0)
                                <span class="pending-badge">{{ $stats['pending_payments'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Revenue Breakdown Cards -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card revenue-card payment-card">
                            <div class="card-body text-center">
                                <h5>Cash Payment</h5>
                                <h3>Rp {{ number_format($revenue['cash_today']) }}</h3>
                                <p>Hari Ini</p>
                                <i class="fas fa-money-bill-wave fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card revenue-card transfer payment-card">
                            <div class="card-body text-center">
                                <h5>Transfer Payment</h5>
                                <h3>Rp {{ number_format($revenue['transfer_today']) }}</h3>
                                <p>Hari Ini</p>
                                <i class="fas fa-university fa-2x"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card revenue-card ewallet payment-card">
                            <div class="card-body text-center">
                                <h5>E-Wallet Payment</h5>
                                <h3>Rp {{ number_format($revenue['ewallet_today']) }}</h3>
                                <p>Hari Ini</p>
                                <i class="fas fa-mobile-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Pending Payments Table -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>Pending Payments</h5>
                                <span class="badge badge-danger">{{ $pendingPayments->count() }} pending</span>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="pendingPaymentsTable">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Customer</th>
                                                <th>Amount</th>
                                                <th>Method</th>
                                                <th>Status</th>
                                                <th>Time</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pendingPayments as $payment)
                                            <tr>
                                                <td>{{ $payment->order->order_number ?? 'N/A' }}</td>
                                                <td>{{ $payment->order->user->name ?? 'N/A' }}</td>
                                                <td>Rp {{ number_format($payment->amount) }}</td>
                                                <td>{{ ucfirst(str_replace('_', ' ', $payment->method)) }}</td>
                                                <td><span class="badge badge-warning">{{ ucfirst($payment->status) }}</span></td>
                                                <td>{{ $payment->created_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ route('kasir.payments.show', $payment) }}" class="btn btn-sm btn-success">Process</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Active Orders Table -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Active Orders</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" id="activeOrdersTable">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Customer</th>
                                                <th>Items</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($activeOrders as $order)
                                            <tr>
                                                <td>{{ $order->order_number ?? '#' . $order->id }}</td>
                                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                                <td>{{ $order->items->count() ?? 0 }} items</td>
                                                <td>
                                                    <select class="form-control status-select" data-order-id="{{ $order->id }}">
                                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                        <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                                        <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                                    </select>
                                                </td>
                                                <td>Rp {{ number_format($order->total_amount ?? 0) }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary">View</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ============================================================== -->
                <!-- Quick Actions -->
                <!-- ============================================================== -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5>Quick Actions</h5>
                                <a href="{{ route('kasir.orders.create') }}" class="btn btn-primary mr-2">New Order</a>
                                <a href="{{ route('kasir.payments.index') }}" class="btn btn-success mr-2">Process Payments</a>
                                <a href="{{ route('kasir.payments.history') }}" class="btn btn-info mr-2">View Reports</a>
                                <a href="{{ route('kasir.settings') }}" class="btn btn-warning">Settings</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
@endsection

@section('scripts')
    <script>
        /****************************************
         *       Basic Table                   *
         ****************************************/
        $('#pendingPaymentsTable').DataTable();
        $('#activeOrdersTable').DataTable();

        // Status update for orders
        $('.status-select').on('change', function() {
            var orderId = $(this).data('order-id');
            var newStatus = $(this).val();
            // AJAX call to update status
            $.ajax({
                url: '/kasir/orders/' + orderId + '/status',
                method: 'POST',
                data: { status: newStatus, _token: '{{ csrf_token() }}' },
                success: function(response) {
                    // Update UI or show success message
                    console.log('Status updated successfully');
                },
                error: function() {
                    alert('Error updating status');
                }
            });
        });


    </script>
@endsection
