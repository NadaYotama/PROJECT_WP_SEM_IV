@extends('layouts.kasir')

@section('title', 'Refunds')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">Refunds Management</h4>
                        <div class="d-flex gap-2">
                            <button onclick="window.location.reload()" class="btn btn-outline-primary">
                                <i class="mdi mdi-refresh"></i> Refresh
                            </button>
                        </div>
                    </div>

                    <!-- Refund Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Total Refunded Today</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($stats['total_refunded']) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="mdi mdi-cash-refund fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Refund Requests Today</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['refund_requests'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="mdi mdi-alert-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Avg Refund Amount</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($stats['avg_refund']) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="mdi mdi-chart-line fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-secondary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                                Refund Rate</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['refund_rate'], 1) }}%</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="mdi mdi-percent fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-2">
                                    <label class="form-label">Method</label>
                                    <select name="method" class="form-select">
                                        <option value="">All Methods</option>
                                        <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="transfer" {{ request('method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                        <option value="ewallet" {{ request('method') == 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">From Date</label>
                                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">To Date</label>
                                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Search</label>
                                    <div class="input-group">
                                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Payment code, customer name..." class="form-control">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="mdi mdi-magnify"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <a href="{{ route('kasir.payments.refunds') }}" class="btn btn-outline-secondary w-100">
                                        <i class="mdi mdi-refresh"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Refunds Table -->
                    <div class="card">
                        <div class="card-body">
                            @if($refunds->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Payment Code</th>
                                            <th>Customer</th>
                                            <th>Order</th>
                                            <th>Method</th>
                                            <th>Amount</th>
                                            <th>Refunded At</th>
                                            <th>Verified By</th>
                                            <th>Notes</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($refunds as $refund)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $refund->payment_code }}</strong>
                                                    @if($refund->proof_image)
                                                    <br><small class="text-muted"><i class="mdi mdi-image"></i> Proof available</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $refund->user->name }}</strong>
                                                    <br><small class="text-muted">{{ $refund->user->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $refund->order->order_number }}</strong>
                                                    <br><small class="text-muted">{{ ucfirst(str_replace('_', ' ', $refund->order->order_type)) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $refund->getMethodBadgeAttribute() }}">
                                                    {{ $refund->getMethodNameAttribute() }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="text-danger">Rp {{ number_format($refund->amount) }}</strong>
                                            </td>
                                            <td>
                                                {{ $refund->verified_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $refund->verifiedBy->name }}</strong>
                                                    <br><small class="text-muted">{{ $refund->verifiedBy->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($refund->notes)
                                                <span class="text-muted" title="{{ $refund->notes }}">
                                                    {{ Str::limit($refund->notes, 30) }}
                                                </span>
                                                @else
                                                <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('kasir.payments.show', $refund) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="mdi mdi-eye"></i> Detail
                                                    </a>
                                                    @if($refund->proof_image)
                                                    <a href="{{ route('payments.proof', $refund) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                        <i class="mdi mdi-image"></i> Proof
                                                    </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $refunds->appends(request()->query())->links() }}
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="mdi mdi-cash-refund-off display-1 text-muted"></i>
                                <h4 class="mt-3">No Refunds</h4>
                                <p class="text-muted">No refund transactions found for the selected criteria</p>
                            </div>
                            @endif
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
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '{{ session('success') }}',
    timer: 3000,
    showConfirmButton: false
});
@endif

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Error!',
    text: '{{ session('error') }}',
    timer: 3000,
    showConfirmButton: false
});
@endif
</script>
@endsection
