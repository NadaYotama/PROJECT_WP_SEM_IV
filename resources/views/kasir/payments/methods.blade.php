@extends('layouts.kasir')

@section('title', 'Payment Methods')

@section('content')
<div class="container-fluid">
    <h1 class="mt-4">Payment Methods</h1>
    <div class="row">
        @foreach ($methods as $method)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3 text-center">
                        @if ($method['icon'] === 'bank')
                            <i class="mdi mdi-bank mdi-48px text-primary"></i>
                        @elseif ($method['icon'] === 'mobile')
                            <i class="mdi mdi-cellphone-android mdi-48px text-success"></i>
                        @elseif ($method['icon'] === 'money-bill')
                            <i class="mdi mdi-cash mdi-48px text-warning"></i>
                        @elseif ($method['icon'] === 'credit-card')
                            <i class="mdi mdi-credit-card mdi-48px text-info"></i>
                        @else
                            <i class="mdi mdi-cash mdi-48px text-secondary"></i>
                        @endif
                    </div>
                    <div>
                        <h4>{{ $method['name'] }}</h4>
                        @if (isset($method['account_number']))
                        <p class="mb-1"><strong>Account Number:</strong> {{ $method['account_number'] }}</p>
                        <p class="mb-1"><strong>Account Name:</strong> {{ $method['account_name'] }}</p>
                        @endif
                        @if (isset($method['phone']))
                        <p class="mb-1"><strong>Phone:</strong> {{ $method['phone'] }}</p>
                        @endif
                        @if (isset($method['description']))
                        <p class="mb-0">{{ $method['description'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
