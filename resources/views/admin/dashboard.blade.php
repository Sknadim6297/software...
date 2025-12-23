@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    </ol>
</div>

<!-- row -->
<div class="row">
    <div class="col-xl-3 col-xxl-3 col-sm-6">
        <div class="card bg-warning invoice-card">
            <div class="card-body d-flex">
                <div class="icon me-3">
                    <svg width="33px" height="32px">
                        <path fill-rule="evenodd" fill="rgb(255, 255, 255)"
                            d="M31.963,30.931 C31.818,31.160 31.609,31.342 31.363,31.456 C31.175,31.544 30.972,31.590 30.767,31.590 C30.429,31.590 30.102,31.463 29.845,31.230 L25.802,27.643 C25.689,27.317 25.689,27.317 25.689,27.317 L25.689,27.317 L25.689,21.409 C25.689,21.409 30.429,25.681 31.245,26.408 C31.396,26.545 31.396,26.545 31.396,26.545 C31.668,26.783 31.820,27.117 31.820,27.473 C31.820,27.832 31.668,28.168 31.396,28.406 L31.396,28.406 C31.396,28.406 31.396,28.406 31.396,28.406 C31.396,28.406 31.963,30.931 31.963,30.931 ZM25.689,21.409 C25.689,21.409 25.689,21.409 25.689,21.409 L25.689,21.409 L25.689,21.409 Z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-white invoice-num">{{ $totalBDMs }}</h2>
                    <span class="text-white fs-18">Total BDMs</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-xxl-3 col-sm-6">
        <div class="card bg-success invoice-card">
            <div class="card-body d-flex">
                <div class="icon me-3">
                    <svg width="33px" height="32px">
                        <path fill-rule="evenodd" fill="rgb(255, 255, 255)"
                            d="M31.963,30.931 C31.818,31.160 31.609,31.342 31.363,31.456 C31.175,31.544 30.972,31.590 30.767,31.590 C30.429,31.590 30.102,31.463 29.845,31.230 L25.802,27.643 C25.689,27.317 25.689,27.317 25.689,27.317 L25.689,27.317 L25.689,21.409 C25.689,21.409 30.429,25.681 31.245,26.408 C31.396,26.545 31.396,26.545 31.396,26.545 C31.668,26.783 31.820,27.117 31.820,27.473 C31.820,27.832 31.668,28.168 31.396,28.406 L31.396,28.406 C31.396,28.406 31.396,28.406 31.396,28.406 C31.396,28.406 31.963,30.931 31.963,30.931 ZM25.689,21.409 C25.689,21.409 25.689,21.409 25.689,21.409 L25.689,21.409 L25.689,21.409 Z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-white invoice-num">{{ $activeBDMs }}</h2>
                    <span class="text-white fs-18">Active BDMs</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-xxl-3 col-sm-6">
        <div class="card bg-info invoice-card">
            <div class="card-body d-flex">
                <div class="icon me-3">
                    <svg width="33px" height="32px">
                        <path fill-rule="evenodd" fill="rgb(255, 255, 255)"
                            d="M31.963,30.931 C31.818,31.160 31.609,31.342 31.363,31.456 C31.175,31.544 30.972,31.590 30.767,31.590 C30.429,31.590 30.102,31.463 29.845,31.230 L25.802,27.643 C25.689,27.317 25.689,27.317 25.689,27.317 L25.689,27.317 L25.689,21.409 C25.689,21.409 30.429,25.681 31.245,26.408 C31.396,26.545 31.396,26.545 31.396,26.545 C31.668,26.783 31.820,27.117 31.820,27.473 C31.820,27.832 31.668,28.168 31.396,28.406 L31.396,28.406 C31.396,28.406 31.396,28.406 31.396,28.406 C31.396,28.406 31.963,30.931 31.963,30.931 ZM25.689,21.409 C25.689,21.409 25.689,21.409 25.689,21.409 L25.689,21.409 L25.689,21.409 Z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-white invoice-num">{{ $bdmsOnLeave }}</h2>
                    <span class="text-white fs-18">On Leave Today</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-xxl-3 col-sm-6">
        <div class="card bg-danger invoice-card">
            <div class="card-body d-flex">
                <div class="icon me-3">
                    <svg width="33px" height="32px">
                        <path fill-rule="evenodd" fill="rgb(255, 255, 255)"
                            d="M31.963,30.931 C31.818,31.160 31.609,31.342 31.363,31.456 C31.175,31.544 30.972,31.590 30.767,31.590 C30.429,31.590 30.102,31.463 29.845,31.230 L25.802,27.643 C25.689,27.317 25.689,27.317 25.689,27.317 L25.689,27.317 L25.689,21.409 C25.689,21.409 30.429,25.681 31.245,26.408 C31.396,26.545 31.396,26.545 31.396,26.545 C31.668,26.783 31.820,27.117 31.820,27.473 C31.820,27.832 31.668,28.168 31.396,28.406 L31.396,28.406 C31.396,28.406 31.396,28.406 31.396,28.406 C31.396,28.406 31.963,30.931 31.963,30.931 ZM25.689,21.409 C25.689,21.409 25.689,21.409 25.689,21.409 L25.689,21.409 L25.689,21.409 Z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-white invoice-num">{{ $terminatedBDMs }}</h2>
                    <span class="text-white fs-18">Terminated</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Pending Leave Approvals -->
    <div class="col-xl-4 col-lg-6">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">Pending Leave Approvals</h4>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <h2 class="mb-0">{{ $pendingLeaves }}</h2>
                    <span class="ms-3 fs-14 text-warning">
                        <i class="fa fa-clock me-1"></i> Awaiting Action
                    </span>
                </div>
                <a href="{{ route('admin.leaves.index', ['status' => 'pending']) }}" class="btn btn-primary btn-sm">
                    View All
                </a>
            </div>
        </div>
    </div>
    
    <!-- Current Month Target -->
    <div class="col-xl-4 col-lg-6">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">Current Month Target</h4>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fs-14">Target: ₹{{ number_format($totalTarget, 2) }}</span>
                        <span class="fs-14 text-success">Achieved: ₹{{ number_format($totalAchieved, 2) }}</span>
                    </div>
                    @php
                        $percentage = $totalTarget > 0 ? ($totalAchieved / $totalTarget) * 100 : 0;
                    @endphp
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ min($percentage, 100) }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="text-center mt-2">
                        <span class="fs-14">{{ number_format($percentage, 1) }}% Achieved</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Current Month Salary -->
    <div class="col-xl-4 col-lg-6">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">Current Month Salary</h4>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <h2 class="mb-0">₹{{ number_format($currentMonthSalary, 2) }}</h2>
                </div>
                <a href="{{ route('admin.salaries.index') }}" class="btn btn-success btn-sm">
                    View Details
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Recent BDMs -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">Recent BDMs</h4>
            </div>
            <div class="card-body">
                @if($recentBDMs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>NAME</strong></th>
                                    <th><strong>CODE</strong></th>
                                    <th><strong>JOINING DATE</strong></th>
                                    <th><strong>STATUS</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBDMs as $bdm)
                                    <tr>
                                        <td>{{ $bdm->name }}</td>
                                        <td>{{ $bdm->employee_code }}</td>
                                        <td>{{ $bdm->joining_date->format('d M Y') }}</td>
                                        <td>
                                            @if($bdm->status == 'active')
                                                <span class="badge light badge-success">Active</span>
                                            @elseif($bdm->status == 'inactive')
                                                <span class="badge light badge-warning">Inactive</span>
                                            @else
                                                <span class="badge light badge-danger">Terminated</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No BDMs found.</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Recent Leave Requests -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="card-title">Recent Leave Requests</h4>
            </div>
            <div class="card-body">
                @if($recentLeaves->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th><strong>BDM</strong></th>
                                    <th><strong>TYPE</strong></th>
                                    <th><strong>DAYS</strong></th>
                                    <th><strong>STATUS</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLeaves as $leave)
                                    <tr>
                                        <td>{{ $leave->bdm->name }}</td>
                                        <td>
                                            @if($leave->leave_type == 'casual')
                                                <span class="badge light badge-info">CL</span>
                                            @elseif($leave->leave_type == 'sick')
                                                <span class="badge light badge-warning">SL</span>
                                            @else
                                                <span class="badge light badge-secondary">UPL</span>
                                            @endif
                                        </td>
                                        <td>1</td>
                                        <td>
                                            @if($leave->status == 'approved')
                                                <span class="badge light badge-success">Approved</span>
                                            @elseif($leave->status == 'rejected')
                                                <span class="badge light badge-danger">Rejected</span>
                                            @else
                                                <span class="badge light badge-warning">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No leave requests found.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
