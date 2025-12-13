@extends('layouts.kasir')

@section('title', 'Revenue Report')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Revenue Report - Today</h1>

    <div class="row">
        <div class="col-md-3">
            <div class="card revenue-card">
                <div class="card-body">
                    <h5 class="card-title">Cash Payments</h5>
                    <p class="card-text h3">Rp {{ number_format($revenue['cash_today'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card revenue-card transfer">
                <div class="card-body">
                    <h5 class="card-title">Bank Transfers</h5>
                    <p class="card-text h3">Rp {{ number_format($revenue['transfer_today'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card revenue-card ewallet">
                <div class="card-body">
                    <h5 class="card-title">E-wallet Payments</h5>
                    <p class="card-text h3">Rp {{ number_format($revenue['ewallet_today'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card revenue-card" style="background: linear-gradient(135deg, #ff9800, #ffb74d);">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="card-text h3">Rp {{ number_format($revenue['total_revenue_today'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Could add more detailed reports or graphs here -->

</div>
@endsection
