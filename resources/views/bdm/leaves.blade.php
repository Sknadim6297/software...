@extends('layouts.app')

@section('title', 'Leave Management')
@section('page-title', 'Leave Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Leave Balance</h4>
                </div>
                <div class="card-body">
                    @if($isEligible)
                        <div class="row text-center">
                            <div class="col-6">
                                <h2 class="text-primary">{{ $leaveBalance->casual_leave_balance ?? 0 }}</h2>
                                <p class="text-muted">Casual Leave</p>
                                <small class="text-muted">Used this month: {{ $leaveBalance->casual_leave_used_this_month ?? 0 }}/1</small>
                            </div>
                            <div class="col-6">
                                <h2 class="text-info">{{ $leaveBalance->sick_leave_balance ?? 0 }}</h2>
                                <p class="text-muted">Sick Leave</p>
                                <small class="text-muted">Used this month: {{ $leaveBalance->sick_leave_used_this_month ?? 0 }}/1</small>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="alert alert-info">
                            <small>
                                <strong>Rules:</strong><br>
                                • Max 1 CL + 1 SL per month<br>
                                • CL: 15 days advance<br>
                                • SL: Before 7:30 AM same day
                            </small>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa fa-clock"></i> You need to complete 6 months to avail CL/SL.
                            <br><small>{{ abs($daysUntilEligible) }} days remaining</small>
                        </div>
                        <p><small>You can only apply for <strong>Unpaid Leave</strong> at this time.</small></p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Apply for Leave</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('bdm.leaves.apply') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                                <select name="leave_type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    @if($isEligible)
                                        <option value="casual">Casual Leave (CL)</option>
                                        <option value="sick">Sick Leave (SL)</option>
                                    @endif
                                    <option value="unpaid">Unpaid Leave</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Leave Date <span class="text-danger">*</span></label>
                                <input type="date" name="leave_date" class="form-control" required min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reason <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="3" required placeholder="Enter reason for leave"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-paper-plane"></i> Submit Application
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Leave Application History</h4>
                </div>
                <div class="card-body">
                    @if($leaveApplications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-responsive-md">
                                <thead>
                                    <tr>
                                        <th>Leave Date</th>
                                        <th>Type</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Applied On</th>
                                        <th>Admin Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaveApplications as $leave)
                                        <tr>
                                            <td><strong>{{ $leave->leave_date->format('M d, Y') }}</strong></td>
                                            <td>
                                                <span class="badge badge-secondary">{{ ucfirst($leave->leave_type) }}</span>
                                            </td>
                                            <td>{{ Str::limit($leave->reason, 40) }}</td>
                                            <td>
                                                @if($leave->status === 'approved')
                                                    <span class="badge badge-success">Approved</span>
                                                @elseif($leave->status === 'rejected')
                                                    <span class="badge badge-danger">Rejected</span>
                                                @else
                                                    <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ $leave->applied_at->format('M d, Y') }}</td>
                                            <td>{{ $leave->admin_remarks ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $leaveApplications->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> No leave applications yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
