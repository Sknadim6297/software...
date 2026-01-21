@extends('layouts.app')

@section('title', 'Attendance - Select Date')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Select Date Attendance</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('attendance.select-date') }}">
                        <div class="form-group">
                            <label for="date" class="font-weight-bold">Select Date</label>
                            <input type="date" class="form-control" id="date" name="date" required value="{{ request('date', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">View Attendance</button>
                    </form>

                    @if(isset($attendance))
                        <hr>
                        <div class="attendance-details">
                            <h5 class="mb-3">Attendance for {{ $date->format('d M, Y') }} ({{ $date->format('l') }})</h5>
                            
                            @if($attendance)
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <label>Check-In Time:</label>
                                            <p class="h5">
                                                @if($attendance->check_in_time)
                                                    {{ \Carbon\Carbon::parse($attendance->check_in_time)->format('h:i A') }}
                                                    @if($attendance->is_late)
                                                        <span class="badge badge-danger ml-2">Late</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">Not checked in</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <label>Check-Out Time:</label>
                                            <p class="h5">
                                                @if($attendance->check_out_time)
                                                    {{ \Carbon\Carbon::parse($attendance->check_out_time)->format('h:i A') }}
                                                @else
                                                    <span class="text-muted">Not checked out</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <label>Status:</label>
                                            <p>
                                                <span class="badge badge-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'half_day' ? 'warning' : ($attendance->status === 'leave' ? 'info' : 'danger')) }}" style="font-size: 16px;">
                                                    {{ ucfirst($attendance->status) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-box">
                                            <label>Working Hours:</label>
                                            <p class="h5">
                                                @if($attendance->check_in_time && $attendance->check_out_time)
                                                    {{ \Carbon\Carbon::parse($attendance->check_in_time)->diff(\Carbon\Carbon::parse($attendance->check_out_time))->format('%Hh %Im') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                @if($attendance->notes)
                                    <div class="alert alert-info mt-3">
                                        <strong>Notes:</strong> {{ $attendance->notes }}
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-warning">
                                    {{ $message }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-box {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 15px;
}
.info-box label {
    font-weight: 600;
    color: #666;
    margin-bottom: 5px;
    display: block;
}
</style>
@endsection
