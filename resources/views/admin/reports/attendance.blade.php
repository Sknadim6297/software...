@extends('admin.layouts.app')

@section('title', 'Attendance Report')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Attendance Report</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Monthly Attendance Report</h4>
                <button onclick="window.print()" class="btn btn-primary btn-sm">
                    <i class="fa fa-print me-2"></i>Print Report
                </button>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('admin.reports.attendance') }}">
                            <div class="input-group">
                                <input type="month" name="month" class="form-control" value="{{ $month }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @php
                    $totalDays = Carbon\Carbon::parse($month)->daysInMonth;
                    $workingDays = $totalDays; // Assuming all days are working days, adjust as needed
                @endphp

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="widget-stat card bg-primary">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-users"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Total Employees</p>
                                        <h3 class="text-white">{{ $bdms->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="widget-stat card bg-success">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-calendar"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Working Days</p>
                                        <h3 class="text-white">{{ $workingDays }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="widget-stat card bg-warning">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-clock"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Total Leaves</p>
                                        <h3 class="text-white">{{ $bdms->sum(function($bdm) { return $bdm->leaveApplications->count(); }) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="widget-stat card bg-info">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-percent"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Avg Attendance</p>
                                        @php
                                            $totalLeaves = $bdms->sum(function($bdm) { return $bdm->leaveApplications->sum('days'); });
                                            $totalPossible = $bdms->count() * $workingDays;
                                            $avgAttendance = $totalPossible > 0 ? (($totalPossible - $totalLeaves) / $totalPossible) * 100 : 0;
                                        @endphp
                                        <h3 class="text-white">{{ number_format($avgAttendance, 1) }}%</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Table -->
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th><strong>#</strong></th>
                                <th><strong>EMPLOYEE</strong></th>
                                <th><strong>CODE</strong></th>
                                <th><strong>WORKING DAYS</strong></th>
                                <th><strong>PRESENT</strong></th>
                                <th><strong>LEAVES TAKEN</strong></th>
                                <th><strong>ATTENDANCE %</strong></th>
                                <th><strong>STATUS</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bdms as $bdm)
                                @php
                                    $leavesTaken = $bdm->leaveApplications->sum('days');
                                    $presentDays = $workingDays - $leavesTaken;
                                    $attendancePercent = $workingDays > 0 ? ($presentDays / $workingDays) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bdm->name }}</td>
                                    <td><strong>{{ $bdm->employee_code }}</strong></td>
                                    <td>{{ $workingDays }}</td>
                                    <td>{{ $presentDays }}</td>
                                    <td>{{ $leavesTaken }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar 
                                                @if($attendancePercent >= 95) bg-success
                                                @elseif($attendancePercent >= 85) bg-info
                                                @elseif($attendancePercent >= 75) bg-warning
                                                @else bg-danger
                                                @endif
                                            " role="progressbar" style="width: {{ $attendancePercent }}%">
                                                {{ number_format($attendancePercent, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($attendancePercent >= 95)
                                            <span class="badge badge-success">Excellent</span>
                                        @elseif($attendancePercent >= 85)
                                            <span class="badge badge-info">Good</span>
                                        @elseif($attendancePercent >= 75)
                                            <span class="badge badge-warning">Average</span>
                                        @else
                                            <span class="badge badge-danger">Poor</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                        No attendance data available for {{ Carbon\Carbon::parse($month)->format('F Y') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Leave Details -->
                @if($bdms->count() > 0)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Leave Summary by Employee</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Employee</th>
                                            <th>Casual Leave</th>
                                            <th>Sick Leave</th>
                                            <th>Unpaid Leave</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($bdms as $bdm)
                                            <tr>
                                                <td>{{ $bdm->name }} ({{ $bdm->employee_code }})</td>
                                                <td>{{ $bdm->leaveApplications->where('leave_type', 'casual')->sum('days') }}</td>
                                                <td>{{ $bdm->leaveApplications->where('leave_type', 'sick')->sum('days') }}</td>
                                                <td>{{ $bdm->leaveApplications->where('leave_type', 'unpaid')->sum('days') }}</td>
                                                <td><strong>{{ $bdm->leaveApplications->sum('days') }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
