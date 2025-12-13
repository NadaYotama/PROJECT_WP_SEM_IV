@extends('layouts.manager')

@section('title', 'Kelola Menu')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Kelola Menu</h4>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <form method="GET" action="{{ route('manager.menu.index') }}" class="mb-4">
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
                                <a href="{{ route('manager.menu.index') }}" class="btn btn-secondary btn-block">
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
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Any additional JavaScript can be added here
});
</script>
@endsection
