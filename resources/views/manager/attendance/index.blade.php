@extends('layouts.manager')

@section('title', 'Attendance Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Attendance Management</h4>
                    <div class="card-tools">
                        <a href="{{ route('manager.attendance.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add Attendance
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="user_id">Staff</label>
                                <select name="user_id" id="user_id" class="form-control">
                                    <option value="">All Staff</option>
                                    @foreach($staff as $member)
                                    <option value="{{ $member->id }}" {{ request('user_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
                                    <option value="half_day" {{ request('status') == 'half_day' ? 'selected' : '' }}>Half Day</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                                <a href="{{ route('manager.attendance.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                            </div>
                        </div>
                    </form>

                    <!-- Attendance Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Staff</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                    <td>{{ $attendance->user->name }}</td>
                                    <td>{{ $attendance->check_in_time ? $attendance->check_in_time->format('H:i') : '-' }}</td>
                                    <td>{{ $attendance->check_out_time ? $attendance->check_out_time->format('H:i') : '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'absent' ? 'danger' : ($attendance->status == 'late' ? 'warning' : 'info')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $attendance->notes ?: '-' }}</td>
                                    <td>
                                        <a href="{{ route('manager.attendance.edit', $attendance) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('manager.attendance.destroy', $attendance) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No attendance records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    {{ $attendances->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
