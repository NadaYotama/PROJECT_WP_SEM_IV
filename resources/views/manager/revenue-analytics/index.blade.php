@extends('layouts.manager')

@section('title', 'Revenue Analytics')

@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Page Heading -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">Revenue Analytics</h4>
                        <div class="d-flex">
                            <form method="GET" class="d-flex">
                                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control mr-2" required>
                                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control mr-2" required>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </form>
                        </div>
                    </div>

                    <!-- Revenue Overview Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Revenue</h5>
                                    <h3>Rp {{ number_format($analytics['total_revenue']) }}</h3>
                                    <small>{{ $analytics['revenue_growth'] >= 0 ? '+' : '' }}{{ number_format($analytics['revenue_growth'], 1) }}% from last period</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Orders</h5>
                                    <h3>{{ number_format($analytics['total_orders']) }}</h3>
                                    <small>Average: Rp {{ number_format($analytics['average_order_value']) }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Revenue Growth</h5>
                                    <h3>{{ $analytics['revenue_growth'] >= 0 ? '+' : '' }}{{ number_format($analytics['revenue_growth'], 1) }}%</h3>
                                    <small>vs previous period</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Avg Order Value</h5>
                                    <h3>Rp {{ number_format($analytics['average_order_value']) }}</h3>
                                    <small>Per transaction</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue Trend Chart -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Revenue Trend</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="revenueChart" width="400" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Distribution -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Revenue by Payment Method</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="paymentMethodChart" width="300" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Payment Method Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Payment Method</th>
                                                    <th>Total Amount</th>
                                                    <th>Transactions</th>
                                                    <th>Average</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($analytics['revenue_by_payment_method'] as $method)
                                                <tr>
                                                    <td>{{ ucfirst($method->payment_method) }}</td>
                                                    <td>Rp {{ number_format($method->total_amount) }}</td>
                                                    <td>{{ $method->transaction_count }}</td>
                                                    <td>Rp {{ number_format($method->total_amount / $method->transaction_count) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Revenue Generating Menus -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Top Revenue Generating Menus</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Menu Name</th>
                                                    <th>Category</th>
                                                    <th>Revenue</th>
                                                    <th>Quantity Sold</th>
                                                    <th>Average Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($analytics['top_menus'] as $menu)
                                                <tr>
                                                    <td>{{ $menu->name }}</td>
                                                    <td><span class="badge badge-primary">{{ ucfirst($menu->category) }}</span></td>
                                                    <td>Rp {{ number_format($menu->revenue) }}</td>
                                                    <td>{{ $menu->quantity_sold }}</td>
                                                    <td>Rp {{ number_format($menu->revenue / $menu->quantity_sold) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Trend Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueData = @json($analytics['revenue_data']);

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => item.date),
            datasets: [{
                label: 'Daily Revenue',
                data: revenueData.map(item => item.daily_revenue),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }, {
                label: 'Order Count',
                data: revenueData.map(item => item.order_count),
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                yAxisID: 'y1',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Revenue (Rp)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Order Count'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Payment Method Chart
    const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
    const paymentData = @json($analytics['revenue_by_payment_method']);

    new Chart(paymentCtx, {
        type: 'doughnut',
        data: {
            labels: paymentData.map(item => item.payment_method.charAt(0).toUpperCase() + item.payment_method.slice(1)),
            datasets: [{
                data: paymentData.map(item => item.total_amount),
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
});
</script>
@endsection
