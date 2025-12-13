@extends('layouts.admin')

@section('title', 'Edit Menu')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Menu</h4>
                    <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary btn-sm float-right">Kembali</a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.menu.update', $menu) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div class="form-group">
                            <label for="name">Nama Menu</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $menu->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" required>{{ old('description', $menu->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Price --}}
                        <div class="form-group">
                            <label for="price">Harga (Rp)</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $menu->price) }}" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Stock --}}
                        <div class="form-group">
                            <label for="stock">Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $menu->stock) }}" min="0" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Jumlah stok menu yang tersedia. Status tersedia akan otomatis disesuaikan berdasarkan stok.</small>
                        </div>

                        {{-- Category --}}
                        <div class="form-group">
                            <label for="category">Kategori <span class="text-danger">*</span></label>
                            <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                @php
                                    $categories = \App\Models\Menu::distinct()->pluck('category')->sort();
                                @endphp
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $menu->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Preparation Time --}}
                        <div class="form-group">
                            <label for="preparation_time">Waktu Persiapan (menit)</label>
                            <input type="number" class="form-control @error('preparation_time') is-invalid @enderror" id="preparation_time" name="preparation_time" value="{{ old('preparation_time', $menu->preparation_time) }}" min="1" placeholder="Opsional">
                        </div>

                        {{-- Image --}}
                        <div class="form-group">
                            <label for="image">Gambar Menu</label>
                            @if($menu->image)
                                <div class="mb-2">
                                    <img src="{{ $menu->image_url }}" alt="{{ $menu->name }}" width="100" class="img-thumbnail">
                                </div>
                            @endif
                            <input type="file" class="form-control-file @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Is Available --}}
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_available" name="is_available" value="1" {{ old('is_available', $menu->is_available) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">Tersedia</label>
                        </div>

                        {{-- Is Popular --}}
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="is_popular" name="is_popular" value="1" {{ old('is_popular', $menu->is_popular) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_popular">Populer</label>
                        </div>

                        {{-- Buttons --}}
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('admin.menu.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
