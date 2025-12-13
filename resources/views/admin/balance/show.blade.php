@extends('layouts.admin')

@section('title', 'Balance Details')

@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Balance Details</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.balance.index') }}">Balance Management</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->

    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Customer Balance Details</h4>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body">
                                    <h5 class="card-title text-success">Current Balance</h5>
                                    <h2 class="text-success">Rp {{ number_format($user->balance, 0, ',', '.') }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info">
                                <div class="card-body">
                                    <h5 class="card-title text-info">Customer Information</h5>
                                    <p><strong>Name:</strong> {{ $user->name }}</p>
                                    <p><strong>Email:</strong> {{ $user->email }}</p>
                                    <p><strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <button class="btn btn-success btn-block" data-toggle="modal" data-target="#topUpModal">Top Up Balance</button>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#deductModal">Deduct Balance</button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('admin.balance.history', $user) }}" class="btn btn-info">View Transaction History</a>
                        <a href="{{ route('admin.balance.index') }}" class="btn btn-secondary">Back to Balance Management</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
</div>

<!-- Top Up Modal -->
<div class="modal fade" id="topUpModal" tabindex="-1" role="dialog" aria-labelledby="topUpModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="topUpModalLabel">Top Up Balance - {{ $user->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.balance.topup', $user) }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="amount">Amount (Rp)</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="0.01" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Top Up</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Deduct Modal -->
<div class="modal fade" id="deductModal" tabindex="-1" role="dialog" aria-labelledby="deductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deductModalLabel">Deduct Balance - {{ $user->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.balance.deduct', $user) }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="amount">Amount (Rp)</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="0.01" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <p class="text-muted">Current Balance: Rp {{ number_format($user->balance, 0, ',', '.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Deduct</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
