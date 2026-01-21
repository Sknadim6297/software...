@extends('admin.layouts.app')

@section('title', 'BDM Leave Applications')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">BDM Leave Management</li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">BDM Leave Applications</h4>
                <div>
                    <a href="{{ route('admin.leaves.balances') }}" class="btn btn-info btn-sm me-2">
                        <i class="fa fa-list me-2"></i>Leave Balances
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('admin.leaves.index') }}" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Leave Type</label>
                                <select name="leave_type" class="form-control">
                                    <option value="">All Types</option>
                                    <option value="casual" {{ request('leave_type') == 'casual' ? 'selected' : '' }}>Casual Leave</option>
                                    <option value="sick" {{ request('leave_type') == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                                    <option value="unpaid" {{ request('leave_type') == 'unpaid' ? 'selected' : '' }}>Unpaid Leave</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">BDM</label>
                                <select name="bdm_id" class="form-control">
                                    <option value="">All BDMs</option>
                                    @foreach($bdms as $bdm)
                                        <option value="{{ $bdm->id }}" {{ request('bdm_id') == $bdm->id ? 'selected' : '' }}>
                                            {{ $bdm->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fa fa-filter me-1"></i>Filter
                                </button>
                                <a href="{{ route('admin.leaves.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-refresh me-1"></i>Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>BDM Name</th>
                                <th>Leave Date</th>
                                <th>Leave Type</th>
                                <th>Reason</th>
                                <th>Applied On</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $leave)
                                <tr>
                                    <td>{{ $leave->bdm->user->name ?? 'N/A' }}</td>
                                    <td><strong>{{ $leave->leave_date->format('d M, Y') }}</strong></td>
                                    <td>
                                        @if($leave->leave_type == 'casual')
                                            <span class="badge badge-info">Casual Leave</span>
                                        @elseif($leave->leave_type == 'sick')
                                            <span class="badge badge-warning">Sick Leave</span>
                                        @else
                                            <span class="badge badge-secondary">Unpaid Leave</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($leave->reason, 40) }}</td>
                                    <td>{{ $leave->applied_at->format('d M, Y') }}</td>
                                    <td>
                                        @if($leave->status === 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($leave->status === 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.leaves.show', $leave->id) }}" class="btn btn-sm btn-info">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if($leave->status === 'pending')
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $leave->id }}">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $leave->id }}">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        @endif

                                        <!-- Approve Modal -->
                                        <div class="modal fade" id="approveModal{{ $leave->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.leaves.approve', $leave->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Approve Leave</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Admin Remarks (Optional)</label>
                                                                <textarea name="admin_remarks" class="form-control" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-success">Approve</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $leave->id }}">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.leaves.reject', $leave->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Leave</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                                <textarea name="admin_remarks" class="form-control" rows="3" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Reject</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No leave applications found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $leaves->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
