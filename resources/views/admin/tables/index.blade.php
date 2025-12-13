@extends('layouts.admin')

@section('title', 'Table Management')

@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Table Management</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tables</li>
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>All Tables</h5>
                        <a href="{{ route('admin.tables.create') }}" class="btn btn-primary btn-sm">Add New Table</a>
                    </div>
                    <div class="card-body">
                        <!-- Search and Filter Form -->
                        <form method="GET" action="{{ route('admin.tables.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="search" class="form-control" placeholder="Search by table number or location" value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="status" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                            <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                            <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-secondary btn-block">Filter</button>
                                </div>
                            </div>
                        </form>

                        <!-- Tables Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Table Number</th>
                                        <th>Capacity</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tables as $table)
                                    <tr>
                                        <td>{{ $table->id }}</td>
                                        <td>{{ $table->table_number }}</td>
                                        <td>{{ $table->capacity }}</td>
                                        <td>{{ $table->location }}</td>
                                        <td>
                                            <span class="badge badge-{{ $table->status == 'available' ? 'success' : ($table->status == 'occupied' ? 'danger' : ($table->status == 'reserved' ? 'warning' : 'secondary')) }}">
                                                {{ ucfirst($table->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $table->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.tables.edit', $table) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form method="POST" action="{{ route('admin.tables.destroy', $table) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this table?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No tables found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        {{ $tables->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
</div>
@endsection
