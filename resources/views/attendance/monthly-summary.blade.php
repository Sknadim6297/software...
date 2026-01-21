@extends('layouts.app')

@section('title', 'Attendance - Monthly Summary')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Monthly Summary</h4>
                    <span class="badge badge-light">{{ $date->format('F Y') }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card bg-success text-white">
                                <i class="fas fa-check-circle"></i>
                                <h5>Present Days</h5>
                                <p class="h3 mb-0">{{ $summary->present_days ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-danger text-white">
                                <i class="fas fa-times-circle"></i>
                                <h5>Absent Days</h5>
                                <p class="h3 mb-0">{{ $summary->absent_days ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-warning text-white">
                                <i class="fas fa-star-half-alt"></i>
                                <h5>Half Days</h5>
                                <p class="h3 mb-0">{{ $summary->half_days ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card bg-info text-white">
                                <i class="fas fa-calendar-check"></i>
                                <h5>Approved Leaves</h5>
                                <p class="h3 mb-0">{{ $summary->approved_leaves ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Late Marks</h5>
                                    <p class="h4 text-danger mb-0">{{ $summary->late_count ?? 0 }}</p>
                                    <small class="text-muted">
                                        @if(($summary->late_count ?? 0) >= 4)
                                            <span class="text-danger">⚠️ 4+ lates: Half-day auto-assigned</span>
                                        @elseif(($summary->late_count ?? 0) >= 3)
                                            <span class="text-warning">⚠️ 3 lates: Warning issued</span>
                                        @else
                                            <span class="text-success">✓ Within limits</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Attendance Rate</h5>
                                    <p class="h4 mb-0">
                                        @php
                                            $total = ($summary->present_days ?? 0) + ($summary->absent_days ?? 0) + ($summary->half_days ?? 0);
                                            $rate = $total > 0 ? round((($summary->present_days ?? 0) / $total) * 100) : 0;
                                        @endphp
                                        <span class="text-{{ $rate >= 80 ? 'success' : ($rate >= 60 ? 'warning' : 'danger') }}">{{ $rate }}%</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($leaves->count() > 0)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="mb-3">Approved Leaves</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>From Date</th>
                                            <th>To Date</th>
                                            <th>Type</th>
                                            <th>Days</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($leaves as $leave)
                                            <tr>
                                                <td>{{ $leave->from_date->format('d M, Y') }}</td>
                                                <td>{{ $leave->to_date->format('d M, Y') }}</td>
                                                <td>
                                                    <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}</span>
                                                </td>
                                                <td>{{ $leave->getLeaveDaysCount() }}</td>
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
</div>

<style>
.stat-card {
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.stat-card i {
    font-size: 32px;
    margin-bottom: 10px;
    opacity: 0.8;
}
</style>
@endsection
