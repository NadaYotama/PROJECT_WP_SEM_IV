@extends('layouts.customer')

@section('title', 'My Reservations')

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
                        <div>
                            <h4 class="card-title">My Reservations</h4>
                            <p class="card-subtitle">View and manage your table reservations</p>
                        </div>
                        <div>
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary mr-2">
                                <i class="mdi mdi-arrow-left"></i> Back to Dashboard
                            </a>
                            <a href="{{ route('customer.reservations.create') }}" class="btn btn-primary">
                                <i class="mdi mdi-plus"></i> Make New Reservation
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-alert-circle"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Reservations Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th><i class="mdi mdi-calendar"></i> Date</th>
                                    <th><i class="mdi mdi-clock"></i> Time</th>
                                    <th><i class="mdi mdi-account-group"></i> Guests</th>
                                    <th><i class="mdi mdi-table-chair"></i> Table</th>
                                    <th><i class="mdi mdi-information"></i> Status</th>
                                    <th><i class="mdi mdi-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservations as $reservation)
                                    <tr>
                                        <td>
                                            <strong>{{ $reservation->date->format('d M Y') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $reservation->date->format('l') }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $reservation->time->format('H:i') }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $reservation->guests }} guests</span>
                                        </td>
                                        <td>
                                            @if($reservation->table)
                                                <strong>Table {{ $reservation->table->table_number }}</strong>
                                                <br>
                                                <small class="text-muted">Capacity: {{ $reservation->table->capacity }}</small>
                                            @else
                                                <span class="text-muted">Table not assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($reservation->status)
                                                @case('pending')
                                                    <span class="badge badge-warning">
                                                        <i class="mdi mdi-clock-outline"></i> Pending
                                                    </span>
                                                    @break
                                                @case('confirmed')
                                                    <span class="badge badge-success">
                                                        <i class="mdi mdi-check-circle"></i> Confirmed
                                                    </span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge badge-danger">
                                                        <i class="mdi mdi-close-circle"></i> Cancelled
                                                    </span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge badge-primary">
                                                        <i class="mdi mdi-check-all"></i> Completed
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">{{ ucfirst($reservation->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('reservations.show', $reservation) }}"
                                                   class="btn btn-sm btn-outline-info"
                                                   title="View Details">
                                                    <i class="mdi mdi-eye"></i>
                                                </a>

                                                @if(in_array($reservation->status, ['pending', 'confirmed']))
                                                    <form method="POST"
                                                          action="{{ route('reservations.cancel', $reservation) }}"
                                                          class="d-inline"
                                                          onsubmit="return confirm('Are you sure you want to cancel this reservation?')">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Cancel Reservation">
                                                            <i class="mdi mdi-close"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="mdi mdi-calendar-blank-outline" style="font-size: 4rem; color: #ccc;"></i>
                                                <h5 class="mt-3 text-muted">No reservations found</h5>
                                                <p class="text-muted">You haven't made any reservations yet.</p>
                                                <a href="{{ route('reservation.index') }}" class="btn btn-primary mt-3">
                                                    <i class="mdi mdi-plus"></i> Make Your First Reservation
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($reservations->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reservations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- Reservation Statistics -->
    <!-- ============================================================== -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Reservations</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $reservations->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="mdi mdi-calendar-multiple fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Confirmed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $reservations->where('status', 'confirmed')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="mdi mdi-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $reservations->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="mdi mdi-clock-outline fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Upcoming</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $reservations->where('status', 'confirmed')->where('date', '>=', today())->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="mdi mdi-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.empty-state {
    padding: 2rem 0;
}

.empty-state i {
    display: block;
    margin: 0 auto;
}

.card {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.table th {
    border-top: none;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
}

.btn-group .btn {
    margin-right: 5px;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .btn-group {
        display: flex;
        flex-direction: column;
    }

    .btn-group .btn {
        margin-right: 0;
        margin-bottom: 5px;
    }
}
</style>
@endsection
