@extends('admin.layouts.app')

@section('title', 'All Attendance Records')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col p-md-0">
            <h4>All Attendance Records</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Filter Records</h4>
                </div>
                <div class="card-body">
                    <form method="GET" class="form-inline">
                        <div class="form-group mr-2 mb-2">
                            <select name="user_id" class="form-control">
                                <option value="">All Employees</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}" placeholder="Date">
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                                <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                                <option value="half_day" {{ request('status') == 'half_day' ? 'selected' : '' }}>Half Day</option>
                                <option value="leave" {{ request('status') == 'leave' ? 'selected' : '' }}>Leave</option>
                            </select>
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <label class="mr-2">
                                <input type="checkbox" name="is_late" value="1" {{ request('is_late') ? 'checked' : '' }}> Late Only
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2 mb-2">Filter</button>
                        <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary mb-2">Clear</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Employee</th>
                                    <th>Check-In</th>
                                    <th>Check-Out</th>
                                    <th>Status</th>
                                    <th>Late</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->attendance_date->format('d M, Y') }}</td>
                                        <td>{{ $attendance->user->name }}</td>
                                        <td>
                                            @if($attendance->check_in_time)
                                                {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->check_out_time)
                                                {{ \Carbon\Carbon::parse($attendance->check_out_time)->format('h:i A') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'half_day' ? 'warning' : ($attendance->status === 'leave' ? 'info' : 'danger')) }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($attendance->is_late)
                                                <span class="badge badge-danger">Late</span>
                                            @else
                                                <span class="badge badge-success">On-Time</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.attendance.edit', $attendance) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            @if(!$attendance->check_out_time)
                                                <form action="{{ route('admin.attendance.unlock-checkout', $attendance) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-info" title="Unlock">
                                                        <i class="fa fa-unlock"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No records found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
