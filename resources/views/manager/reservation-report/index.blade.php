@extends('layouts.manager')

@section('title', 'Reservation Report - Manager Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Reservation Report</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('manager.reservation-report.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-4">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-4">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">Generate Report</button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5>Total Reservations</h5>
                                    <h3>{{ $reports['total_reservations'] }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5>Confirmed</h5>
                                    <h3>{{ $reports['confirmed_reservations'] }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5>Pending</h5>
                                    <h3>{{ $reports['pending_reservations'] }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5>Cancelled</h5>
                                    <h3>{{ $reports['cancelled_reservations'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Daily Reservation Trends</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Reservations</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($reports['daily_reservations'] as $daily)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($daily->date)->format('d M Y') }}</td>
                                                    <td>{{ $daily->count }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Reservation Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="reservationTable">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Customer</th>
                                                    <th>Table</th>
                                                    <th>Date & Time</th>
                                                    <th>Guests</th>
                                                    <th>Status</th>
                                                    <th>Created</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($reservations as $reservation)
                                                <tr>
                                                    <td>{{ $reservation->id }}</td>
                                                    <td>{{ $reservation->user->name ?? 'N/A' }}</td>
                                                    <td>{{ $reservation->table->name ?? 'N/A' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($reservation->reservation_date . ' ' . $reservation->reservation_time)->format('d M Y H:i') }}</td>
                                                    <td>{{ $reservation->number_of_guests }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $reservation->status == 'confirmed' ? 'success' : ($reservation->status == 'pending' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($reservation->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $reservation->created_at->format('d M Y') }}</td>
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
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#reservationTable').DataTable({
        "order": [[ 0, "desc" ]],
        "pageLength": 25
    });
});
</script>
@endsection
