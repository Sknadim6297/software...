@extends('admin.layouts.app')

@section('title', 'Employee Attendance History - ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col p-md-0">
            <h4>Employee Attendance History</h4>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.attendance.dashboard') }}">Attendance</a></li>
                <li class="breadcrumb-item active">{{ $user->name }}</li>
            </ol>
        </div>
    </div>

    <!-- Employee Information -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="flaticon-381-user-7"></i> {{ $user->name }}
                        @if($user->bdm)
                            <span class="badge badge-primary ml-2">{{ $user->bdm->employee_code }}</span>
                        @endif
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Email:</strong> {{ $user->email }}
                        </div>
                        @if($user->bdm)
                            <div class="col-md-3">
                                <strong>Phone:</strong> {{ $user->bdm->phone }}
                            </div>
                            <div class="col-md-3">
                                <strong>Department:</strong> BDM
                            </div>
                            <div class="col-md-3">
                                <strong>Status:</strong> 
                                <span class="badge badge-{{ $user->bdm->status === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($user->bdm->status) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Summary -->
    @if($summary)
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="flaticon-381-calendar"></i> 
                        {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }} Summary
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 text-center">
                            <div class="mb-3">
                                <h3 class="text-success">{{ $summary->present_days ?? 0 }}</h3>
                                <p class="text-muted">Present</p>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="mb-3">
                                <h3 class="text-danger">{{ $summary->absent_days ?? 0 }}</h3>
                                <p class="text-muted">Absent</p>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="mb-3">
                                <h3 class="text-warning">{{ $summary->half_days ?? 0 }}</h3>
                                <p class="text-muted">Half Days</p>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="mb-3">
                                <h3 class="text-info">{{ $summary->approved_leaves ?? 0 }}</h3>
                                <p class="text-muted">Leaves</p>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="mb-3">
                                <h3 class="text-secondary">{{ $summary->late_count ?? 0 }}</h3>
                                <p class="text-muted">Late Marks</p>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="mb-3">
                                <h3 class="text-primary">{{ $summary->working_days ?? 0 }}</h3>
                                <p class="text-muted">Working Days</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Attendance Records -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="flaticon-381-list"></i> Attendance Records
                        <small class="text-muted ml-2">
                            {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}
                        </small>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Check-In</th>
                                    <th>Check-Out</th>
                                    <th>Status</th>
                                    <th>Late</th>
                                    <th>Notes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                    <tr>
                                        <td>
                                            <strong>{{ $attendance->attendance_date->format('d M, Y') }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $attendance->attendance_date->format('l') }}</small>
                                        </td>
                                        <td>
                                            @if($attendance->check_in_time)
                                                {{ $attendance->check_in_time->format('h:i A') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->check_out_time)
                                                {{ $attendance->check_out_time->format('h:i A') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->status === 'present')
                                                <span class="badge badge-success">Present</span>
                                            @elseif($attendance->status === 'absent')
                                                <span class="badge badge-danger">Absent</span>
                                            @elseif($attendance->status === 'half_day')
                                                <span class="badge badge-warning">Half Day</span>
                                            @elseif($attendance->status === 'leave')
                                                <span class="badge badge-info">Leave</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->is_late)
                                                <span class="badge badge-warning">
                                                    <i class="fa fa-exclamation-circle"></i> Late
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->notes)
                                                <small>{{ $attendance->notes }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.attendance.update', $attendance) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fa fa-info-circle mr-2"></i> No attendance records found for this month
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="row mt-3">
        <div class="col-lg-12">
            <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Records
            </a>
        </div>
    </div>
</div>

<style>
    .card {
        margin-bottom: 20px;
    }
    .badge {
        padding: 5px 10px;
        font-size: 12px;
    }
</style>
@endsection
