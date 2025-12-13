@extends('layouts.manager')

@section('title', 'Kelola Kategori Menu')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Kelola Kategori Menu</h4>
                </div>
                <div class="card-body">
                    <!-- Search -->
                    <form method="GET" action="{{ route('manager.categories.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Cari kategori..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('manager.categories.index') }}" class="btn btn-secondary btn-block">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Categories Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Jumlah Menu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                <tr>
                                    <td>{{ $category->category }}</td>
                                    <td>{{ \App\Models\Menu::where('category', $category->category)->count() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center">Tidak ada kategori ditemukan.</td>
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
