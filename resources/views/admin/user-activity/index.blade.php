@extends('layouts.admin')

@section('title', 'User Activity - Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">User Activity Report</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.user-activity.index') }}" class="mb-4">
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

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date & Time</th>
                                    <th>User</th>
                                    <th>Activity Type</th>
                                    <th>Description</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paginatedActivities as $activity)
                                <tr>
                                    <td>{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $activity->user->name ?? 'N/A' }} ({{ $activity->user->email ?? 'N/A' }})</td>
                                    <td>
                                        <span class="badge badge-{{ $activity->type == 'order' ? 'primary' : ($activity->type == 'payment' ? 'success' : 'info') }}">
                                            {{ ucfirst($activity->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $activity->description }}</td>
                                    <td>{{ $activity->details }}</td>
                                    <td>
                                        <span class="badge badge-{{ $activity->status == 'success' || $activity->status == 'completed' ? 'success' : ($activity->status == 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No activities found for the selected period.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $paginatedActivities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
