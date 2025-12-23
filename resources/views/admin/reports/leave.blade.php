@extends('admin.layouts.app')

@section('title', 'Leave Report')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Leave Report</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Monthly Leave Report</h4>
                <button onclick="window.print()" class="btn btn-primary btn-sm">
                    <i class="fa fa-print me-2"></i>Print Report
                </button>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('admin.reports.leave') }}">
                            <div class="input-group">
                                <input type="month" name="month" class="form-control" value="{{ $month }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="widget-stat card bg-primary">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-file"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Total Applications</p>
                                        <h3 class="text-white">{{ $leaves->count() }}</h3>
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
                                        <i class="la la-check"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Approved</p>
                                        <h3 class="text-white">{{ $leaves->where('status', 'approved')->count() }}</h3>
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
                                        <p class="mb-1">Pending</p>
                                        <h3 class="text-white">{{ $leaves->where('status', 'pending')->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="widget-stat card bg-danger">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-times"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Rejected</p>
                                        <h3 class="text-white">{{ $leaves->where('status', 'rejected')->count() }}</h3>
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
                                <th><strong>LEAVE TYPE</strong></th>
                                <th><strong>FROM DATE</strong></th>
                                <th><strong>TO DATE</strong></th>
                                <th><strong>DAYS</strong></th>
                                <th><strong>REASON</strong></th>
                                <th><strong>STATUS</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $leave)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $leave->bdm->name ?? 'N/A' }}</td>
                                    <td><strong>{{ $leave->bdm->employee_code ?? 'N/A' }}</strong></td>
                                    <td>
                                        @if($leave->leave_type == 'casual')
                                            <span class="badge badge-primary">Casual</span>
                                        @elseif($leave->leave_type == 'sick')
                                            <span class="badge badge-warning">Sick</span>
                                        @else
                                            <span class="badge badge-secondary">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>{{ Carbon\Carbon::parse($leave->from_date)->format('d M Y') }}</td>
                                    <td>{{ Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</td>
                                    <td>{{ $leave->days }}</td>
                                    <td>{{ Illuminate\Support\Str::limit($leave->reason, 40) }}</td>
                                    <td>
                                        @if($leave->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($leave->status == 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                        No leave data available for {{ Carbon\Carbon::parse($month)->format('F Y') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Leave Type Summary -->
                @if($leaves->count() > 0)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Leave Type Summary</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Leave Type</th>
                                            <th>Total Days</th>
                                            <th>Applications</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Casual Leave</td>
                                            <td>{{ $leaves->where('leave_type', 'casual')->sum('days') }}</td>
                                            <td>{{ $leaves->where('leave_type', 'casual')->count() }}</td>
                                        </tr>
                                        <tr>
                                            <td>Sick Leave</td>
                                            <td>{{ $leaves->where('leave_type', 'sick')->sum('days') }}</td>
                                            <td>{{ $leaves->where('leave_type', 'sick')->count() }}</td>
                                        </tr>
                                        <tr>
                                            <td>Unpaid Leave</td>
                                            <td>{{ $leaves->where('leave_type', 'unpaid')->sum('days') }}</td>
                                            <td>{{ $leaves->where('leave_type', 'unpaid')->count() }}</td>
                                        </tr>
                                        <tr class="table-active">
                                            <td><strong>Total</strong></td>
                                            <td><strong>{{ $leaves->sum('days') }}</strong></td>
                                            <td><strong>{{ $leaves->count() }}</strong></td>
                                        </tr>
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
