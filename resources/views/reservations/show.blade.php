@extends('layouts.customer')

@section('title', 'Reservation Details')

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
                            <h4 class="card-title">Reservation Details</h4>
                            <p class="card-subtitle">View your reservation information</p>
                        </div>
                        <div>
                            <a href="{{ route('customer.reservations.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left"></i> Back to My Reservations
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

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Reservation #{{ $reservation->id }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Customer Name:</strong></label>
                                                <p>{{ $reservation->name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Email:</strong></label>
                                                <p>{{ $reservation->email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Phone:</strong></label>
                                                <p>{{ $reservation->phone }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Number of Guests:</strong></label>
                                                <p>{{ $reservation->guests }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Reservation Date:</strong></label>
                                                <p>{{ $reservation->date->format('l, d F Y') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Reservation Time:</strong></label>
                                                <p>{{ $reservation->time }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Status:</strong></label>
                                                <p>
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
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Assigned Table:</strong></label>
                                                <p>
                                                    @if($reservation->table)
                                                        {{ $reservation->table->table_number }} (Capacity: {{ $reservation->table->capacity }})
                                                    @else
                                                        <span class="text-muted">Not assigned yet</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @if($reservation->notes)
                                    <div class="form-group">
                                        <label><strong>Notes:</strong></label>
                                        <p>{{ $reservation->notes }}</p>
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Created At:</strong></label>
                                                <p>{{ $reservation->created_at->format('d M Y H:i') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label><strong>Last Updated:</strong></label>
                                                <p>{{ $reservation->updated_at->format('d M Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Actions</h6>
                                </div>
                                <div class="card-body">
                                    @if(in_array($reservation->status, ['pending', 'confirmed']))
                                        <form method="POST" action="{{ route('reservations.cancel', $reservation) }}" class="mb-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to cancel this reservation?')">
                                                <i class="mdi mdi-close"></i> Cancel Reservation
                                            </button>
                                        </form>
                                    @endif

                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="mdi mdi-information"></i>
                                            @if($reservation->status == 'pending')
                                                Your reservation is being reviewed by our staff.
                                            @elseif($reservation->status == 'confirmed')
                                                Your reservation has been confirmed. Please arrive on time.
                                            @elseif($reservation->status == 'cancelled')
                                                This reservation has been cancelled.
                                            @elseif($reservation->status == 'completed')
                                                This reservation has been completed.
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>

                            @if($reservation->status == 'confirmed')
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6>Important Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="mdi mdi-information"></i>
                                        <strong>Please note:</strong>
                                        <ul class="mb-0 mt-2">
                                            <li>Arrive 10-15 minutes before your reservation time</li>
                                            <li>Bring this reservation number for check-in</li>
                                            <li>Contact us if you need to make changes</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.form-group label {
    font-weight: 600;
    color: #495057;
}

.badge {
    font-size: 0.75rem;
}

.btn {
    border-radius: 5px;
}

.alert {
    border-radius: 5px;
}

@media (max-width: 768px) {
    .row {
        margin: 0;
    }

    .col-md-8, .col-md-4 {
        padding: 0 15px;
    }
}
</style>
@endsection
