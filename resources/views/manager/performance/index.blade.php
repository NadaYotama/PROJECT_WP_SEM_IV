@extends('layouts.manager')

@section('title', 'Staff Performance')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Staff Performance Management</h4>
                    <div class="card-tools">
                        <form method="GET" class="d-inline">
                            <div class="input-group">
                                <select name="month" class="form-control">
                                    @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                    </option>
                                    @endfor
                                </select>
                                <select name="year" class="form-control">
                                    @for($y = now()->year - 2; $y <= now()->year; $y++)
                                    <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                    @endfor
                                </select>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Performance Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $performanceStats['total_staff'] }}</h4>
                                    <p>Total Staff</p>
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $performanceStats['present_today'] }}</h4>
                                    <p>Present Today</p>
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $performanceStats['late_today'] }}</h4>
                                    <p>Late Today</p>
                                    <i class="fas fa-clock fa-2x"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $performanceStats['absent_today'] }}</h4>
                                    <p>Absent Today</p>
                                    <i class="fas fa-times-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Staff Name</th>
                                    <th>Role</th>
                                    <th>Working Days</th>
                                    <th>Present</th>
                                    <th>Late</th>
                                    <th>Absent</th>
                                    <th>Half Day</th>
                                    <th>Attendance Rate</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staffPerformance as $performance)
                                <tr>
                                    <td>{{ $performance['name'] }}</td>
                                    <td><span class="badge badge-info">{{ ucfirst($performance['role']) }}</span></td>
                                    <td>{{ $performance['working_days'] }}</td>
                                    <td><span class="badge badge-success">{{ $performance['present'] }}</span></td>
                                    <td><span class="badge badge-warning">{{ $performance['late'] }}</span></td>
                                    <td><span class="badge badge-danger">{{ $performance['absent'] }}</span></td>
                                    <td><span class="badge badge-secondary">{{ $performance['half_day'] }}</span></td>
                                    <td>
                                        <div class="progress" style="width: 100px;">
                                            <div class="progress-bar {{ $performance['attendance_rate'] >= 90 ? 'bg-success' : ($performance['attendance_rate'] >= 75 ? 'bg-warning' : 'bg-danger') }}"
                                                 role="progressbar"
                                                 style="width: {{ $performance['attendance_rate'] }}%"
                                                 aria-valuenow="{{ $performance['attendance_rate'] }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                                {{ $performance['attendance_rate'] }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($performance['attendance_rate'] >= 90)
                                            <span class="badge badge-success">Excellent</span>
                                        @elseif($performance['attendance_rate'] >= 75)
                                            <span class="badge badge-warning">Good</span>
                                        @else
                                            <span class="badge badge-danger">Poor</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No staff performance data available.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
