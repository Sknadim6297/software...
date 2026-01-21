@extends('admin.layouts.app')

@section('title', 'Leave Applications')
@section('page-title', 'BDM Leave Applications')

@section('content')
<div class="container-fluid">
    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.leave-salary.leaves.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">BDM</label>
                                <select name="bdm_id" class="form-control">
                                    <option value="">All BDMs</option>
                                    @foreach($bdms as $bdm)
                                        <option value="{{ $bdm->id }}" {{ request('bdm_id') == $bdm->id ? 'selected' : '' }}>
                                            {{ $bdm->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">From Date</label>
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">To Date</label>
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                                <a href="{{ route('admin.leave-salary.leaves.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-refresh"></i> Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Applications List -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Leave Applications</h4>
                    <div>
                        <a href="{{ route('admin.leave-salary.leaves.balances') }}" class="btn btn-info btn-sm me-2">
                            <i class="fa fa-list"></i> View Balances
                        </a>
                        <a href="{{ route('admin.leave-salary.leaves.monthly-report') }}" class="btn btn-success btn-sm">
                            <i class="fa fa-file"></i> Monthly Report
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($leaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>BDM Name</th>
                                        <th>Leave Type</th>
                                        <th>Date Range</th>
                                        <th>Days</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Applied On</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaves as $index => $leave)
                                    <tr>
                                        <td>{{ $leaves->firstItem() + $index }}</td>
                                        <td><strong>{{ $leave->bdm->name }}</strong></td>
                                        <td>
                                            <span class="badge badge-secondary">
                                                {{ $leave->getLeaveTypeLabel() }}
                                            </span>
                                        </td>
                                        <td>{{ $leave->date_range }}</td>
                                        <td>{{ $leave->number_of_days }}</td>
                                        <td>{{ Str::limit($leave->reason, 40) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $leave->getStatusBadgeColor() }}">
                                                {{ ucfirst($leave->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $leave->applied_at->format('M d, Y') }}</td>
                                        <td>
                                            @if($leave->isPending())
                                                <button 
                                                    class="btn btn-success btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#approveModal{{ $leave->id }}"
                                                >
                                                    <i class="fa fa-check"></i> Approve
                                                </button>
                                                <button 
                                                    class="btn btn-danger btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal{{ $leave->id }}"
                                                >
                                                    <i class="fa fa-times"></i> Reject
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    {{ ucfirst($leave->status) }}
                                                </button>
                                            @endif
                                            
                                            <a href="{{ route('admin.leave-salary.leaves.show', $leave->id) }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Approve Modal -->
                                    <div class="modal fade" id="approveModal{{ $leave->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.leave-salary.leaves.approve', $leave->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-success text-white">
                                                        <h5 class="modal-title">Approve Leave</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>BDM:</strong> {{ $leave->bdm->name }}</p>
                                                        <p><strong>Leave Type:</strong> {{ $leave->getLeaveTypeLabel() }}</p>
                                                        <p><strong>Date Range:</strong> {{ $leave->date_range }}</p>
                                                        <p><strong>Days:</strong> {{ $leave->number_of_days }}</p>
                                                        <p><strong>Reason:</strong> {{ $leave->reason }}</p>
                                                        <hr>
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
                                    <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('admin.leave-salary.leaves.reject', $leave->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">Reject Leave</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><strong>BDM:</strong> {{ $leave->bdm->name }}</p>
                                                        <p><strong>Leave Type:</strong> {{ $leave->getLeaveTypeLabel() }}</p>
                                                        <p><strong>Date Range:</strong> {{ $leave->date_range }}</p>
                                                        <hr>
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $leaves->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> No leave applications found.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
