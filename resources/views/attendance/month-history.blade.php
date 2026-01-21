@extends('layouts.app')

@section('title', 'Attendance - Month History')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Month History</h4>
                    <div>
                        <form method="GET" class="form-inline" style="display:inline;">
                            <input type="month" name="date" class="form-control form-control-sm mr-2" value="{{ $date->format('Y-m') }}" onchange="this.form.submit()">
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="bg-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Check-In</th>
                                    <th>Check-Out</th>
                                    <th>Status</th>
                                    <th>Late</th>
                                    <th>Working Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $record)
                                    <tr>
                                        <td>
                                            <strong>{{ $record->attendance_date->format('d M') }}</strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $record->attendance_date->format('l') }}</small>
                                        </td>
                                        <td>
                                            @if($record->check_in_time)
                                                {{ \Carbon\Carbon::parse($record->check_in_time)->format('h:i A') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($record->check_out_time)
                                                {{ \Carbon\Carbon::parse($record->check_out_time)->format('h:i A') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $record->status === 'present' ? 'success' : ($record->status === 'half_day' ? 'warning' : ($record->status === 'leave' ? 'info' : 'danger')) }}">
                                                {{ ucfirst($record->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($record->is_late)
                                                <span class="badge badge-danger">Late</span>
                                            @else
                                                <span class="badge badge-success">On-Time</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($record->check_in_time && $record->check_out_time)
                                                {{ \Carbon\Carbon::parse($record->check_in_time)->diff(\Carbon\Carbon::parse($record->check_out_time))->format('%Hh %Im') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            No attendance records found
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
</div>
@endsection
