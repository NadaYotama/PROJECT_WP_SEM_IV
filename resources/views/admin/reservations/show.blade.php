@extends('layouts.admin')

@section('title', 'Reservation Details')

@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 d-flex no-block align-items-center">
                <h4 class="page-title">Reservation Details</h4>
                <div class="ml-auto text-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.reservations.index') }}">Reservations</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Details</li>
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
                        <h5>Reservation #{{ $reservation->id }}</h5>
                        <div>
                            <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
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
                                                <span class="badge badge-{{ $reservation->status == 'confirmed' ? 'success' : ($reservation->status == 'pending' ? 'warning' : ($reservation->status == 'completed' ? 'info' : 'danger')) }}">
                                                    {{ ucfirst($reservation->status) }}
                                                </span>
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
                                                    <span class="text-muted">Not assigned</span>
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
                            <div class="col-md-4">
                                <div class="card border">
                                    <div class="card-header">
                                        <h6>Actions</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($reservation->status == 'pending')
                                            <form method="POST" action="{{ route('admin.reservations.updateStatus', $reservation) }}" class="mb-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="btn btn-success btn-block">Confirm Reservation</button>
                                            </form>
                                        @elseif($reservation->status == 'confirmed')
                                            <form method="POST" action="{{ route('admin.reservations.updateStatus', $reservation) }}" class="mb-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="btn btn-primary btn-block">Mark as Completed</button>
                                            </form>
                                        @endif

                                        @if(in_array($reservation->status, ['pending', 'confirmed']))
                                            <form method="POST" action="{{ route('admin.reservations.cancel', $reservation) }}" class="mb-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to cancel this reservation?')">Cancel Reservation</button>
                                            </form>
                                        @endif

                                        @if(!$reservation->table && $reservation->status == 'confirmed')
                                            <button class="btn btn-info btn-block" onclick="assignTable({{ $reservation->id }})">Assign Table</button>
                                        @endif
                                    </div>
                                </div>

                                @if($reservation->user)
                                <div class="card border mt-3">
                                    <div class="card-header">
                                        <h6>Customer Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Name:</strong> {{ $reservation->user->name }}</p>
                                        <p><strong>Email:</strong> {{ $reservation->user->email }}</p>
                                        <p><strong>Phone:</strong> {{ $reservation->user->phone ?? 'Not provided' }}</p>
                                        <p><strong>Role:</strong> {{ ucfirst($reservation->user->role) }}</p>
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
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
</div>

<!-- Assign Table Modal -->
<div class="modal fade" id="assignTableModal" tabindex="-1" role="dialog" aria-labelledby="assignTableModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignTableModalLabel">Assign Table</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="assignTableForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="table_id">Select Table</label>
                        <select name="table_id" id="table_id" class="form-control" required>
                            <option value="">Choose a table...</option>
                            <!-- Tables will be loaded here -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Table</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function assignTable(reservationId) {
    // Load available tables
    fetch(`/admin/tables/available?date={{ $reservation->date }}&time={{ $reservation->time }}&guests={{ $reservation->guests }}`)
        .then(response => response.json())
        .then(data => {
            const tableSelect = document.getElementById('table_id');
            tableSelect.innerHTML = '<option value="">Choose a table...</option>';

            data.forEach(table => {
                const option = document.createElement('option');
                option.value = table.id;
                option.textContent = `Table ${table.table_number} (Capacity: ${table.capacity})`;
                tableSelect.appendChild(option);
            });

            // Update form action
            document.getElementById('assignTableForm').action = `/admin/reservations/${reservationId}/assign-table`;

            // Show modal
            $('#assignTableModal').modal('show');
        })
        .catch(error => {
            console.error('Error loading tables:', error);
            alert('Error loading available tables. Please try again.');
        });
}
</script>
@endsection
