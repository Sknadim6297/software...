@extends('admin.layouts.app')

@section('title', 'Attendance Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col p-md-0">
            <h4>Attendance Dashboard</h4>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Attendance</li>
            </ol>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card bg-primary">
                <div class="card-body p-4">
                    <div class="media">
                        <span class="mr-3">
                            <i class="flaticon-381-user-7 text-white" style="font-size: 50px;"></i>
                        </span>
                        <div class="media-body text-white">
                            <p class="mb-1">Total Employees</p>
                            <h3 class="text-white">{{ $totalEmployees }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card bg-success">
                <div class="card-body p-4">
                    <div class="media">
                        <span class="mr-3">
                            <i class="flaticon-381-success text-white" style="font-size: 50px;"></i>
                        </span>
                        <div class="media-body text-white">
                            <p class="mb-1">Present Today</p>
                            <h3 class="text-white">{{ $presentToday }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card bg-danger">
                <div class="card-body p-4">
                    <div class="media">
                        <span class="mr-3">
                            <i class="flaticon-381-error text-white" style="font-size: 50px;"></i>
                        </span>
                        <div class="media-body text-white">
                            <p class="mb-1">Absent Today</p>
                            <h3 class="text-white">{{ $absentToday }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card bg-warning">
                <div class="card-body p-4">
                    <div class="media">
                        <span class="mr-3">
                            <i class="flaticon-381-clock text-white" style="font-size: 50px;"></i>
                        </span>
                        <div class="media-body text-white">
                            <p class="mb-1">Half-Day Today</p>
                            <h3 class="text-white">{{ $halfDayToday }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Pending Checkouts -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Pending Checkouts ({{ $pendingCheckout->count() }})</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Check-In</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingCheckout as $attendance)
                                    <tr>
                                        <td>{{ $attendance->user->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') }}</td>
                                        <td>
                                            @if($attendance->is_late)
                                                <span class="badge badge-warning">Late</span>
                                            @else
                                                <span class="badge badge-success">On-Time</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.attendance.unlock-checkout', $attendance) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-info" title="Unlock Checkout">
                                                    <i class="fa fa-unlock"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No pending checkouts</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Late Employees Today -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Late Employees Today ({{ $lateEmployees->count() }})</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Check-In</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lateEmployees as $attendance)
                                    <tr>
                                        <td>{{ $attendance->user->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $attendance->status === 'half_day' ? 'danger' : 'warning' }}">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.attendance.remove-penalty', $attendance) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Remove Penalty" onclick="return confirm('Remove late penalty?')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No late employees today</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Late Tracking -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Monthly Late Tracking</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Email</th>
                                    <th>Late Count (This Month)</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyLateTracking as $record)
                                    <tr>
                                        <td>{{ $record->user->name }}</td>
                                        <td>{{ $record->user->email }}</td>
                                        <td>
                                            <span class="badge badge-{{ $record->late_count >= 4 ? 'danger' : ($record->late_count >= 3 ? 'warning' : 'info') }}" style="font-size: 14px;">
                                                {{ $record->late_count }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($record->late_count >= 4)
                                                <span class="badge badge-danger">Auto Half-Day Applied</span>
                                            @elseif($record->late_count >= 3)
                                                <span class="badge badge-warning">Warning Sent</span>
                                            @else
                                                <span class="badge badge-success">Normal</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.attendance.employee-history', $record->user) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-eye"></i> View History
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No late records this month</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Quick Actions</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('admin.attendance.index') }}" class="btn btn-primary btn-block">
                                <i class="fa fa-list"></i> All Attendance Records
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-success btn-block" onclick="openAddManualModal()">
                                <i class="fa fa-plus"></i> Add Manual Entry
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.attendance.settings') }}" class="btn btn-info btn-block">
                                <i class="fa fa-cog"></i> Attendance Settings
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.attendance.holidays') }}" class="btn btn-warning btn-block">
                                <i class="fa fa-calendar"></i> Manage Holidays
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Manual Attendance Modal -->
<div class="modal fade" id="addManualAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="addManualAttendanceLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addManualAttendanceLabel">Add Manual Attendance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.attendance.add-manual') }}" method="POST" id="addManualForm">
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <label for="user_id">Employee <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-control" required>
                            <option value="">-- Select Employee --</option>
                            @foreach(\App\Models\User::whereHas('bdm')->get() as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->bdm->employee_code ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="attendance_date">Date <span class="text-danger">*</span></label>
                        <input type="date" name="attendance_date" id="attendance_date" class="form-control" required value="{{ old('attendance_date', date('Y-m-d')) }}">
                    </div>
                    <div class="form-group">
                        <label for="check_in_time">Check-In Time</label>
                        <input type="time" name="check_in_time" id="check_in_time" class="form-control" value="{{ old('check_in_time') }}">
                    </div>
                    <div class="form-group">
                        <label for="check_out_time">Check-Out Time</label>
                        <input type="time" name="check_out_time" id="check_out_time" class="form-control" value="{{ old('check_out_time') }}">
                    </div>
                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="">-- Select Status --</option>
                            <option value="present" {{ old('status') === 'present' ? 'selected' : '' }}>Present</option>
                            <option value="absent" {{ old('status') === 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="half_day" {{ old('status') === 'half_day' ? 'selected' : '' }}>Half Day</option>
                            <option value="leave" {{ old('status') === 'leave' ? 'selected' : '' }}>Leave</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Add Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddManualModal() {
    // Reset form
    document.getElementById('addManualForm').reset();
    document.getElementById('attendance_date').value = new Date().toISOString().split('T')[0];
    
    // Open modal - Compatible with both Bootstrap 4 & 5
    const modal = document.getElementById('addManualAttendanceModal');
    if (modal) {
        // Try Bootstrap 4 method
        if (jQuery && jQuery.fn.modal) {
            jQuery('#addManualAttendanceModal').modal('show');
        } 
        // Fallback for Bootstrap 5
        else if (window.bootstrap && window.bootstrap.Modal) {
            const bsModal = new window.bootstrap.Modal(modal);
            bsModal.show();
        }
    }
}

// Handle form submission feedback
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('addManualForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
        });
    }
});
</script>

@endsection
