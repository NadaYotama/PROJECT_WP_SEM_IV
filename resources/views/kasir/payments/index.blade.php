@extends('layouts.kasir')

@section('title', 'Process Payments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">Process Payments</h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('kasir.payments.history') }}" class="btn btn-outline-info">
                                <i class="mdi mdi-history"></i> Payment History
                            </a>
                            <button onclick="window.location.reload()" class="btn btn-outline-primary">
                                <i class="mdi mdi-refresh"></i> Refresh
                            </button>
                        </div>
                    </div>

                    <!-- Payment Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Hari Ini</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($stats['total_today']) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="mdi mdi-cash-multiple fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Berhasil</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['success_count'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="mdi mdi-check-circle fa-2x text-gray-300"></i>
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
                                                Menunggu Verifikasi</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_count'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="mdi mdi-clock-outline fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Ditolak</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['failed_count'] }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="mdi mdi-close-circle fa-2x text-gray-300"></i>
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
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Diproses</option>
                                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Berhasil</option>
                                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Metode</label>
                                    <select name="method" class="form-select">
                                        <option value="">Semua Metode</option>
                                        <option value="cash" {{ request('method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                        <option value="transfer" {{ request('method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                        <option value="ewallet" {{ request('method') == 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Dari Tanggal</label>
                                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Sampai Tanggal</label>
                                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Cari</label>
                                    <div class="input-group">
                                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode pembayaran..." class="form-control">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="mdi mdi-magnify"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <a href="{{ route('kasir.payments.index') }}" class="btn btn-outline-secondary w-100">
                                        <i class="mdi mdi-refresh"></i>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Payments Table -->
                    <div class="card">
                        <div class="card-body">
                            @if($payments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Kode Pembayaran</th>
                                            <th>Customer</th>
                                            <th>Order</th>
                                            <th>Metode</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $payment)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $payment->payment_code }}</strong>
                                                    @if($payment->proof_image)
                                                    <br><small class="text-muted"><i class="mdi mdi-image"></i> Bukti tersedia</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $payment->user->name }}</strong>
                                                    <br><small class="text-muted">{{ $payment->user->email }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $payment->order->order_number }}</strong>
                                                    <br><small class="text-muted">{{ ucfirst(str_replace('_', ' ', $payment->order->order_type)) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $payment->getMethodBadgeAttribute() }}">
                                                    {{ $payment->getMethodNameAttribute() }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong class="text-success">Rp {{ number_format($payment->amount) }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $payment->getStatusBadgeAttribute() }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $payment->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('kasir.payments.show', $payment) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="mdi mdi-eye"></i> Detail
                                                    </a>
                                                    @if($payment->proof_image)
                                                    <a href="{{ route('payments.proof', $payment) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                        <i class="mdi mdi-image"></i> Bukti
                                                    </a>
                                                    @endif
                                                    @if(in_array($payment->status, ['pending', 'processing']))
                                                    <div class="btn-group" role="group">
                                                        <form action="{{ route('kasir.payments.verify', $payment) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="success">
                                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui pembayaran ini?')">
                                                                <i class="mdi mdi-check"></i> Setuju
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('kasir.payments.verify', $payment) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="status" value="failed">
                                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tolak pembayaran ini?')">
                                                                <i class="mdi mdi-close"></i> Tolak
                                                            </button>
                                                        </form>
                                                    </div>
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
                                {{ $payments->appends(request()->query())->links() }}
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="mdi mdi-cash-multiple-off display-1 text-muted"></i>
                                <h4 class="mt-3">Belum ada pembayaran</h4>
                                <p class="text-muted">Pembayaran akan muncul di sini setelah customer melakukan pembayaran</p>
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
    title: 'Berhasil!',
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

// Auto refresh every 30 seconds for pending payments
@if($stats['pending_count'] > 0)
setTimeout(function() {
    window.location.reload();
}, 30000);
@endif
</script>
@endsection
