@extends('layouts.kasir')

@section('title', 'All Orders - Kasir Dashboard')

@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">All Orders</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('kasir.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">All Orders</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Daftar Semua Pesanan</h4>
                    <h6 class="card-subtitle">Kelola semua pesanan yang ada di sistem</h6>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-control" id="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="preparing">Preparing</option>
                                <option value="ready">Ready</option>
                                <option value="delivering">Delivering</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="typeFilter">
                                <option value="">Semua Tipe</option>
                                <option value="dine_in">Dine In</option>
                                <option value="takeaway">Takeaway</option>
                                <option value="delivery">Delivery</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="searchInput" placeholder="Cari nomor pesanan atau nama customer...">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary" id="filterBtn">Filter</button>
                            <button class="btn btn-secondary" id="resetBtn">Reset</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="ordersTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Tipe</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="font-weight-bold">{{ $order->user->name }}</div>
                                            <small class="text-muted">{{ $order->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge
                                            @if($order->order_type == 'dine_in') badge-primary
                                            @elseif($order->order_type == 'takeaway') badge-success
                                            @else badge-info
                                            @endif">
                                            @switch($order->order_type)
                                                @case('dine_in') Dine In @break
                                                @case('takeaway') Takeaway @break
                                                @case('delivery') Delivery @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        <div>
                                            @foreach($order->items->take(2) as $item)
                                            <div>{{ $item->item_name }} ({{ $item->quantity }}x)</div>
                                            @endforeach
                                            @if($order->items->count() > 2)
                                            <small class="text-muted">+{{ $order->items->count() - 2 }} lainnya</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge
                                            @if($order->payment_status == 'paid') badge-success
                                            @else badge-danger
                                            @endif">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
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
                                    <td>
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                            @if($order->payment_status == 'unpaid')
                                            <button class="btn btn-sm btn-success" onclick="processPayment({{ $order->id }})">
                                                <i class="fas fa-credit-card"></i> Bayar
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Page Content -->
    <!-- ============================================================== -->
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#ordersTable').DataTable({
        "order": [[7, "desc"]], // Sort by date column descending
        "pageLength": 25,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Tidak ada data yang ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });

    // Status update functionality
    $('.status-select').on('change', function() {
        var orderId = $(this).data('order-id');
        var newStatus = $(this).val();

        if (confirm('Apakah Anda yakin ingin mengubah status pesanan ini?')) {
            $.ajax({
                url: '{{ route("kasir.orders.updateStatus", ":orderId") }}'.replace(':orderId', orderId),
                method: 'POST',
                data: {
                    status: newStatus,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Show success message
                    toastr.success('Status pesanan berhasil diperbarui');
                    // Optionally reload the page or update the UI
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    toastr.error('Gagal memperbarui status pesanan');
                    console.log(xhr.responseText);
                }
            });
        } else {
            // Reset the select to previous value
            $(this).val($(this).data('original-value'));
        }
    });

    // Store original values for reset
    $('.status-select').each(function() {
        $(this).data('original-value', $(this).val());
    });

    // Filter functionality
    $('#filterBtn').on('click', function() {
        var status = $('#statusFilter').val();
        var type = $('#typeFilter').val();
        var search = $('#searchInput').val();

        // You can implement AJAX filtering here or redirect with parameters
        var url = '{{ route("kasir.orders.index") }}';
        var params = [];
        if (status) params.push('status=' + status);
        if (type) params.push('order_type=' + type);
        if (search) params.push('search=' + search);

        if (params.length > 0) {
            url += '?' + params.join('&');
        }

        window.location.href = url;
    });

    $('#resetBtn').on('click', function() {
        $('#statusFilter').val('');
        $('#typeFilter').val('');
        $('#searchInput').val('');
        window.location.href = '{{ route("kasir.orders.index") }}';
    });
});

function processPayment(orderId) {
    if (confirm('Apakah Anda yakin ingin memproses pembayaran untuk pesanan ini?')) {
        // Implement payment processing logic
        // This could redirect to a payment page or open a modal
        window.location.href = '/kasir/orders/' + orderId + '/payment';
    }
}
</script>
@endsection
