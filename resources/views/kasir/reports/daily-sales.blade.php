@extends('layouts.kasir')

@section('title', 'Daily Sales Report')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Daily Sales Report</h1>
    <div class="card">
        <div class="card-body">
            <h3>Total Sales Today</h3>
            <p class="display-4">Rp {{ number_format($totalSalesToday, 0, ',', '.') }}</p>
        </div>
    </div>
</div>
@endsection
