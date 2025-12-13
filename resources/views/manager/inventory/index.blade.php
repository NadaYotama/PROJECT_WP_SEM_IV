@extends('layouts.manager')

@section('title', 'Kelola Inventory')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Kelola Inventory</h4>
                    <div class="card-tools">
                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#bulkUpdateModal">
                            <i class="fas fa-plus-circle"></i> Update Stok Massal
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <form method="GET" action="{{ route('manager.inventory.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Cari menu..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="category" class="form-control">
                                    <option value="">Semua Kategori</option>
                                    <option value="main_course" {{ request('category') == 'main_course' ? 'selected' : '' }}>Hidangan Utama</option>
                                    <option value="appetizer" {{ request('category') == 'appetizer' ? 'selected' : '' }}>Hidangan Pembuka</option>
                                    <option value="beverage" {{ request('category') == 'beverage' ? 'selected' : '' }}>Minuman</option>
                                    <option value="dessert" {{ request('category') == 'dessert' ? 'selected' : '' }}>Dessert</option>
                                    <option value="side_dish" {{ request('category') == 'side_dish' ? 'selected' : '' }}>Lauk Pendamping</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="stock_status" class="form-control">
                                    <option value="">Semua Status</option>
                                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Rendah</option>
                                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                                    <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('manager.inventory.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Inventory Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $stats['low_stock'] }}</h5>
                                    <p>Stok Rendah (< 10)</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $stats['out_of_stock'] }}</h5>
                                    <p>Stok Habis</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $stats['available'] }}</h5>
                                    <p>Tersedia</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $stats['total_items'] }}</h5>
                                    <p>Total Item</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Gambar</th>
                                    <th>Nama Menu</th>
                                    <th>Kategori</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Status Stok</th>
                                    <th>Terakhir Update</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menuItems as $menu)
                                <tr>
                                    <td>
                                        @if($menu->image)
                                            <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" width="50" height="50" class="rounded">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $menu->name }}</td>
                                    <td>
                                        <span class="badge badge-primary">
                                            @switch($menu->category)
                                                @case('main_course') Hidangan Utama @break
                                                @case('appetizer') Hidangan Pembuka @break
                                                @case('beverage') Minuman @break
                                                @case('dessert') Dessert @break
                                                @case('side_dish') Lauk Pendamping @break
                                                @default {{ $menu->category }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $menu->stock <= 0 ? 'danger' : ($menu->stock < 10 ? 'warning' : 'success') }}">
                                            {{ $menu->stock }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($menu->stock <= 0)
                                            <span class="badge badge-danger">Habis</span>
                                        @elseif($menu->stock < 10)
                                            <span class="badge badge-warning">Rendah</span>
                                        @else
                                            <span class="badge badge-success">Tersedia</span>
                                        @endif
                                    </td>
                                    <td>{{ $menu->updated_at->diffForHumans() }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#updateStockModal" data-menu-id="{{ $menu->id }}" data-menu-name="{{ $menu->name }}" data-current-stock="{{ $menu->stock }}">
                                            <i class="fas fa-edit"></i> Update Stok
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada menu ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Stock Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1" role="dialog" aria-labelledby="updateStockModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStockModalLabel">Update Stok Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updateStockForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="menuName">Nama Menu</label>
                        <input type="text" class="form-control" id="menuName" readonly>
                    </div>
                    <div class="form-group">
                        <label for="currentStock">Stok Saat Ini</label>
                        <input type="number" class="form-control" id="currentStock" readonly>
                    </div>
                    <div class="form-group">
                        <label for="newStock">Stok Baru</label>
                        <input type="number" class="form-control" id="newStock" name="stock" min="0" required>
                        <small class="form-text text-muted">Masukkan jumlah stok baru</small>
                    </div>
                    <div class="form-group">
                        <label for="adjustmentType">Jenis Penyesuaian</label>
                        <select class="form-control" id="adjustmentType" name="adjustment_type">
                            <option value="set">Set Stok</option>
                            <option value="add">Tambah Stok</option>
                            <option value="subtract">Kurangi Stok</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notes">Catatan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Opsional: tambahkan catatan untuk perubahan ini"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Stok</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Update Modal -->
<div class="modal fade" id="bulkUpdateModal" tabindex="-1" role="dialog" aria-labelledby="bulkUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkUpdateModalLabel">Update Stok Massal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('manager.inventory.bulk-update') }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Pilih menu yang ingin diupdate stoknya dan masukkan nilai baru.
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>Nama Menu</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Stok Baru</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($menuItems as $menu)
                                <tr>
                                    <td><input type="checkbox" name="menu_ids[]" value="{{ $menu->id }}" class="menu-checkbox"></td>
                                    <td>{{ $menu->name }}</td>
                                    <td>{{ $menu->stock }}</td>
                                    <td><input type="number" name="stocks[{{ $menu->id }}]" class="form-control form-control-sm" min="0" placeholder="Stok baru"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Terpilih</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Handle update stock modal
    $('#updateStockModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var menuId = button.data('menu-id');
        var menuName = button.data('menu-name');
        var currentStock = button.data('current-stock');

        var modal = $(this);
        modal.find('#menuName').val(menuName);
        modal.find('#currentStock').val(currentStock);
        modal.find('#newStock').val(currentStock);
        modal.find('#updateStockForm').attr('action', '{{ route("manager.inventory.update", ":id") }}'.replace(':id', menuId));
    });

    // Handle adjustment type change
    $('#adjustmentType').change(function() {
        var type = $(this).val();
        var currentStock = parseInt($('#currentStock').val());
        var newStockInput = $('#newStock');

        if (type === 'add' || type === 'subtract') {
            newStockInput.attr('placeholder', type === 'add' ? 'Jumlah yang ditambahkan' : 'Jumlah yang dikurangi');
            newStockInput.val('');
        } else {
            newStockInput.attr('placeholder', 'Stok baru');
            newStockInput.val(currentStock);
        }
    });

    // Select all checkbox
    $('#selectAll').change(function() {
        $('.menu-checkbox').prop('checked', $(this).prop('checked'));
    });

    $('.menu-checkbox').change(function() {
        if (!$(this).prop('checked')) {
            $('#selectAll').prop('checked', false);
        } else if ($('.menu-checkbox:checked').length === $('.menu-checkbox').length) {
            $('#selectAll').prop('checked', true);
        }
    });
});
</script>
@endsection
