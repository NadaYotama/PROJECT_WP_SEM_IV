@extends('layouts.admin')

@section('title', 'Kelola Menu')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Kelola Menu</h4>
                    <div class="card-tools">
                        <a href="{{ route('admin.menu.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Menu
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <form method="GET" action="{{ route('admin.menu.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Cari menu..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">  
                                <select name="category" class="form-control">
                                    <option value="">Semua Kategori</option>
                                        @foreach($allowedCategories as $cat)
                                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : ''  }}>{{ $cat }}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Menu Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Gambar</th>
                                    <th>Nama Menu</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th>Populer</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menuItems as $menu)
                                <tr>
                                    <td>
                                        @if($menu->image)
                                    <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" width="100" class="img-thumbnail">
                                        @else
                                            <span class="text-muted">No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $menu->name }}</td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $menu->category }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge badge-{{ $menu->stock <= 0 ? 'danger' : ($menu->stock < 10 ? 'warning' : 'success') }} mr-2">
                                                {{ $menu->stock }}
                                            </span>
                                            <!-- <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-secondary btn-sm adjust-stock" data-menu-id="{{ $menu->id }}" data-action="decrease" data-current-stock="{{ $menu->stock }}" title="Kurangi Stok">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-primary btn-sm adjust-stock" data-menu-id="{{ $menu->id }}" data-action="increase" data-current-stock="{{ $menu->stock }}" title="Tambah Stok">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div> -->
                                        </div>
                                    </td>
                                    <td>
                                        @if($menu->is_available)
                                            <span class="badge badge-success">Tersedia</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Tersedia</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($menu->is_popular)
                                            <span class="badge badge-warning">Ya</span>
                                        @else
                                            <span class="badge badge-secondary">Tidak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.menu.edit', $menu) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.menu.destroy', $menu) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada menu ditemukan.</td>
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


<script>
$(document).ready(function() {
    $('.adjust-stock').on('click', function() {
        var menuId = $(this).data('menu-id');
        var action = $(this).data('action');
        var currentStock = $(this).data('current-stock');
        var menuName = $(this).closest('tr').find('td:nth-child(2)').text().trim();

        $('#stockAdjustmentModal #menu_name').val(menuName);
        $('#stockAdjustmentModal #current_stock').val(currentStock);
        $('#stockAdjustmentModal #menu_id').val(menuId);
        $('#stockAdjustmentModal #action').val(action);

        if (action === 'decrease') {
            $('#stockAdjustmentModal .modal-title').text('Kurangi Stok Menu');
            $('#stockAdjustmentModal #quantity_label').text('Jumlah yang akan dikurangi:');
            $('#stockAdjustmentModal #quantity').attr('max', currentStock);
        } else {
            $('#stockAdjustmentModal .modal-title').text('Tambah Stok Menu');
            $('#stockAdjustmentModal #quantity_label').text('Jumlah yang akan ditambahkan:');
            $('#stockAdjustmentModal #quantity').removeAttr('max');
        }

        $('#stockAdjustmentModal').modal('show');
    });

    $('#stockAdjustmentForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        var menuId = formData.get('menu_id');
        var action = formData.get('action');
        var quantity = parseInt(formData.get('quantity'));

        $.ajax({
            url: '{{ route("admin.menu.adjust-stock") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#stockAdjustmentModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Error adjusting stock: ' + xhr.responseText);
            }
        });
    });
});
</script>

<!-- Stock Adjustment Modal
<div class="modal fade" id="stockAdjustmentModal" tabindex="-1" role="dialog" aria-labelledby="stockAdjustmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stockAdjustmentModalLabel">Sesuaikan Stok Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="stockAdjustmentForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="menu_name">Nama Menu</label>
                        <input type="text" class="form-control" id="menu_name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="current_stock">Stok Saat Ini</label>
                        <input type="number" class="form-control" id="current_stock" readonly>
                    </div>
                    <div class="form-group">
                        <label id="quantity_label" for="quantity">Jumlah</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                    </div>
                    <input type="hidden" id="menu_id" name="menu_id">
                    <input type="hidden" id="action" name="action">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div> -->

@endsection
