@extends('admin.layouts.app')

@section('title', 'Leave Details')
@section('page-title', 'Leave Application Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Leave Application Information</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>BDM Name:</strong>
                            <p>{{ $leave->bdm->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Employee Code:</strong>
                            <p>{{ $leave->bdm->employee_code }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Leave Type:</strong>
                            <p><span class="badge badge-secondary">{{ $leave->getLeaveTypeLabel() }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <p><span class="badge badge-{{ $leave->getStatusBadgeColor() }}">{{ ucfirst($leave->status) }}</span></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>From Date:</strong>
                            <p>{{ $leave->from_date->format('d M, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>To Date:</strong>
                            <p>{{ $leave->to_date->format('d M, Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Number of Days:</strong>
                            <p>{{ $leave->number_of_days }} {{ Str::plural('day', $leave->number_of_days) }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Applied On:</strong>
                            <p>{{ $leave->applied_at->format('d M, Y h:i A') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Reason:</strong>
                            <p class="border rounded p-3 bg-light">{{ $leave->reason }}</p>
                        </div>
                    </div>

                    @if($leave->admin_action_at)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Action Taken On:</strong>
                            <p>{{ $leave->admin_action_at->format('d M, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Action By:</strong>
                            <p>Admin</p>
                        </div>
                    </div>
                    @endif

                    @if($leave->admin_remarks)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Admin Remarks:</strong>
                            <p class="border rounded p-3 {{ $leave->isApproved() ? 'bg-success-light' : 'bg-danger-light' }}">
                                {{ $leave->admin_remarks }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title text-white mb-0">Actions</h4>
                </div>
                <div class="card-body">
                    @if($leave->isPending())
                        <div class="d-grid gap-2">
                            <button 
                                class="btn btn-success btn-lg" 
                                data-bs-toggle="modal" 
                                data-bs-target="#approveModal"
                            >
                                <i class="fa fa-check"></i> Approve Leave
                            </button>
                            <button 
                                class="btn btn-danger btn-lg" 
                                data-bs-toggle="modal" 
                                data-bs-target="#rejectModal"
                            >
                                <i class="fa fa-times"></i> Reject Leave
                            </button>
                        </div>

                        <!-- Approve Modal -->
                        <div class="modal fade" id="approveModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.leave-salary.leaves.approve', $leave->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-success text-white">
                                            <h5 class="modal-title text-white">Approve Leave</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to approve this leave application?</p>
                                            <div class="alert alert-info">
                                                <strong>Leave Details:</strong><br>
                                                Type: {{ $leave->getLeaveTypeLabel() }}<br>
                                                Days: {{ $leave->number_of_days }}<br>
                                                Date: {{ $leave->date_range }}
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Remarks (Optional)</label>
                                                <textarea name="remarks" class="form-control" rows="3" placeholder="Add any remarks..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">
                                                <i class="fa fa-check"></i> Approve Leave
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.leave-salary.leaves.reject', $leave->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title text-white">Reject Leave</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to reject this leave application?</p>
                                            <div class="mb-3">
                                                <label class="form-label">Remarks (Required) <span class="text-danger">*</span></label>
                                                <textarea name="remarks" class="form-control" rows="3" placeholder="Provide reason for rejection..." required></textarea>
                                                <small class="text-muted">Remarks are mandatory when rejecting a leave</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa fa-times"></i> Reject Leave
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-{{ $leave->getStatusBadgeColor() }}">
                            <h5><i class="fa fa-info-circle"></i> Leave {{ ucfirst($leave->status) }}</h5>
                            <p>This leave has already been {{ $leave->status }}.</p>
                            @if($leave->admin_action_at)
                                <small>Action taken on {{ $leave->admin_action_at->format('d M, Y h:i A') }}</small>
                            @endif
                        </div>
                    @endif

                    <hr>

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('admin.leave-salary.leaves.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>

            <!-- Leave Balance Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">{{ $leave->bdm->name }}'s Leave Balance</h5>
                </div>
                <div class="card-body">
                    @php
                        $balance = $leave->bdm->leaveBalance ?? new \App\Models\BDMLeaveBalance();
                    @endphp
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <h4 class="text-primary">{{ $balance->casual_leave_balance ?? 0 }}</h4>
                            <small class="text-muted">CL Available</small>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-info">{{ $balance->sick_leave_balance ?? 0 }}</h4>
                            <small class="text-muted">SL Available</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
