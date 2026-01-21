@extends('layouts.app')

@section('title', 'Attendance - Today')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Today's Attendance</h4>
                    <span class="badge badge-light">{{ $todayAttendance?->attendance_date?->format('d M, Y') ?? now()->format('d M, Y') }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Check-In Section -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-primary">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">
                                        <i class="fas fa-sign-in-alt"></i> Check-In
                                    </h5>
                                    <p class="text-muted mb-3">Allowed until <strong>{{ $rules->check_in_deadline ?? '10:45 AM' }}</strong></p>
                                    
                                    @if($todayAttendance && $todayAttendance->check_in_time)
                                        <div class="alert alert-{{ $todayAttendance->is_late ? 'warning' : 'success' }}">
                                            <strong>{{ $todayAttendance->is_late ? 'Late Check-In' : 'Checked In' }}</strong><br>
                                            <span>{{ $todayAttendance->check_in_time->format('h:i A') }}</span>
                                        </div>
                                    @else
                                        @if($canCheckIn)
                                            <button type="button" class="btn btn-success btn-lg btn-block" id="checkInBtn">
                                                <i class="fas fa-check-circle"></i> Check In
                                            </button>
                                        @elseif($canLatCheckIn)
                                            <button type="button" class="btn btn-warning btn-lg btn-block" id="checkInBtn">
                                                <i class="fas fa-exclamation-triangle"></i> Late Check In
                                            </button>
                                            <small class="text-danger d-block mt-2">You are late. This will be marked as a late entry.</small>
                                        @else
                                            <div class="alert alert-danger">
                                                <i class="fas fa-ban"></i> Check-in window has closed
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Check-Out Section -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-danger">
                                <div class="card-body">
                                    <h5 class="card-title text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Check-Out
                                    </h5>
                                    <p class="text-muted mb-3">Allowed until <strong>{{ $rules->check_out_time ?? '8:30 PM' }}</strong></p>
                                    
                                    @if($todayAttendance && $todayAttendance->check_out_time)
                                        <div class="alert alert-success">
                                            <strong>Checked Out</strong><br>
                                            <span>{{ $todayAttendance->check_out_time->format('h:i A') }}</span>
                                        </div>
                                    @else
                                        @if($todayAttendance && $todayAttendance->check_in_time && !$todayAttendance->check_out_time)
                                            @if($canCheckOut)
                                                <button type="button" class="btn btn-danger btn-lg btn-block" id="checkOutBtn">
                                                    <i class="fas fa-check-circle"></i> Check Out
                                                </button>
                                            @else
                                                <div class="alert alert-info">
                                                    <i class="fas fa-clock"></i> Check-out will be available after {{ $rules->check_out_time }}
                                                </div>
                                            @endif
                                        @else
                                            <div class="alert alert-secondary">
                                                <i class="fas fa-info-circle"></i> Check-in first to enable check-out
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status & Summary -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Today's Status</h5>
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="status-box">
                                                <h6 class="text-muted">Status</h6>
                                                <p class="h5 mb-0">
                                                    @if($pendingLeave)
                                                        <span class="badge badge-info">On Leave</span>
                                                    @elseif($todayAttendance)
                                                        <span class="badge badge-{{ $todayAttendance->status === 'present' ? 'success' : ($todayAttendance->status === 'half_day' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($todayAttendance->status) }}
                                                        </span>
                                                    @else
                                                        <span class="badge badge-secondary">Not Marked</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="status-box">
                                                <h6 class="text-muted">Working Hours</h6>
                                                <p class="h5 mb-0">
                                                    @if($todayAttendance && $todayAttendance->check_in_time && $todayAttendance->check_out_time)
                                                        {{ \Carbon\Carbon::parse($todayAttendance->check_in_time)->diff(\Carbon\Carbon::parse($todayAttendance->check_out_time))->format('%Hh %Im') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="status-box">
                                                <h6 class="text-muted">Current Time</h6>
                                                <p class="h5 mb-0" id="currentTime">{{ now()->format('h:i A') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="status-box">
                                                <h6 class="text-muted">Monthly Summary</h6>
                                                <p class="h6 mb-0">
                                                    <small>Present: {{ $monthlySummary->present_days ?? 0 }}</small><br>
                                                    <small>Late: {{ $monthlySummary->late_count ?? 0 }}</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="btn-group btn-block" role="group">
                                <a href="{{ route('attendance.select-date') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-calendar"></i> Select Date
                                </a>
                                <a href="{{ route('attendance.month-history') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list"></i> Month History
                                </a>
                                <a href="{{ route('attendance.monthly-summary') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-chart-bar"></i> Monthly Summary
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Update current time every second
setInterval(() => {
    const now = new Date();
    document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit',
        second: '2-digit',
        hour12: true 
    });
}, 1000);

// Check-In
document.getElementById('checkInBtn')?.addEventListener('click', function() {
    if(confirm('Are you sure you want to check in?')) {
        fetch('{{ route("attendance.check-in") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
});

// Check-Out
document.getElementById('checkOutBtn')?.addEventListener('click', function() {
    if(confirm('Are you sure you want to check out?')) {
        fetch('{{ route("attendance.check-out") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
});
</script>

<style>
.border-left-primary {
    border-left: 4px solid #007bff !important;
}
.border-left-danger {
    border-left: 4px solid #dc3545 !important;
}
.status-box {
    padding: 15px;
    background: white;
    border-radius: 8px;
    margin-bottom: 10px;
}
.btn-group-vertical {
    width: 100%;
}
.btn-group > .btn {
    flex: 1;
}
</style>
@endpush
@endsection
