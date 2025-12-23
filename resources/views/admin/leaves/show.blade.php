@extends('admin.layouts.app')

@section('title', 'Leave Application Details')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.leaves.index') }}">Leaves</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Leave Details</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Leave Application Details</h4>
                <a href="{{ route('admin.leaves.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-1"></i>Back to List
                </a>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Employee Name:</th>
                        <td>{{ $leave->bdm->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Employee Code:</th>
                        <td>{{ $leave->bdm->employee_code ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Leave Type:</th>
                        <td>
                            @if($leave->leave_type == 'casual')
                                <span class="badge badge-primary">Casual Leave</span>
                            @elseif($leave->leave_type == 'sick')
                                <span class="badge badge-warning">Sick Leave</span>
                            @else
                                <span class="badge badge-secondary">Unpaid Leave</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>From Date:</th>
                        <td>{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y (l)') }}</td>
                    </tr>
                    <tr>
                        <th>To Date:</th>
                        <td>{{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y (l)') }}</td>
                    </tr>
                    <tr>
                        <th>Total Days:</th>
                        <td><strong>{{ $leave->days }} day(s)</strong></td>
                    </tr>
                    <tr>
                        <th>Reason:</th>
                        <td>{{ $leave->reason }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($leave->status == 'pending')
                                <span class="badge badge-lg badge-warning">
                                    <i class="fa fa-clock me-1"></i>Pending
                                </span>
                            @elseif($leave->status == 'approved')
                                <span class="badge badge-lg badge-success">
                                    <i class="fa fa-check me-1"></i>Approved
                                </span>
                            @else
                                <span class="badge badge-lg badge-danger">
                                    <i class="fa fa-times me-1"></i>Rejected
                                </span>
                            @endif
                        </td>
                    </tr>
                    @if($leave->admin_remarks)
                    <tr>
                        <th>Admin Remarks:</th>
                        <td>{{ $leave->admin_remarks }}</td>
                    </tr>
                    @endif
                    @if($leave->admin_action_at)
                    <tr>
                        <th>Action Date:</th>
                        <td>{{ $leave->admin_action_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Applied On:</th>
                        <td>{{ $leave->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>

                @if($leave->status == 'pending')
                    <div class="mt-4">
                        <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="fa fa-check me-2"></i>Approve Leave
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fa fa-times me-2"></i>Reject Leave
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Employee Leave Balance</h5>
            </div>
            <div class="card-body">
                @if($leave->bdm->leaveBalance)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Casual Leave</span>
                            <strong class="text-primary">{{ $leave->bdm->leaveBalance->casual_leave }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sick Leave</span>
                            <strong class="text-warning">{{ $leave->bdm->leaveBalance->sick_leave }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Unpaid Leave</span>
                            <strong class="text-secondary">{{ $leave->bdm->leaveBalance->unpaid_leave }}</strong>
                        </div>
                    </div>
                @else
                    <p class="text-muted">No leave balance information available</p>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title">Leave History</h5>
            </div>
            <div class="card-body">
                @php
                    $recentLeaves = $leave->bdm->leaveApplications()->where('id', '!=', $leave->id)->latest()->take(5)->get();
                @endphp
                @forelse($recentLeaves as $recent)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">{{ \Carbon\Carbon::parse($recent->from_date)->format('d M Y') }}</small>
                            <span class="badge badge-sm 
                                @if($recent->status == 'approved') badge-success
                                @elseif($recent->status == 'pending') badge-warning
                                @else badge-danger
                                @endif
                            ">
                                {{ ucfirst($recent->status) }}
                            </span>
                        </div>
                        <div>{{ ucfirst($recent->leave_type) }} - {{ $recent->days }} day(s)</div>
                    </div>
                @empty
                    <p class="text-muted">No previous leave applications</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.leaves.approve', $leave->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Approve Leave Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to approve this leave application?</p>
                    <div class="alert alert-info">
                        <strong>Employee:</strong> {{ $leave->bdm->name }}<br>
                        <strong>Duration:</strong> {{ $leave->days }} day(s)<br>
                        <strong>Type:</strong> {{ ucfirst($leave->leave_type) }} Leave
                    </div>
                    <div class="form-group">
                        <label>Remarks (Optional)</label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="Add any remarks..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check me-2"></i>Approve Leave
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.leaves.reject', $leave->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Leave Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>Employee:</strong> {{ $leave->bdm->name }}<br>
                        <strong>Duration:</strong> {{ $leave->days }} day(s)<br>
                        <strong>Type:</strong> {{ ucfirst($leave->leave_type) }} Leave
                    </div>
                    <div class="form-group">
                        <label>Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="Enter reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-times me-2"></i>Reject Leave
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
