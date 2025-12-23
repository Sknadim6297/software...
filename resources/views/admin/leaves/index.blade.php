@extends('admin.layouts.app')

@section('title', 'Leave Applications')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Leave Management</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Leave Applications</h4>
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

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('admin.leaves.index') }}" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Leave Type</label>
                                <select name="leave_type" class="form-control">
                                    <option value="all" {{ request('leave_type') == 'all' ? 'selected' : '' }}>All Types</option>
                                    <option value="casual" {{ request('leave_type') == 'casual' ? 'selected' : '' }}>Casual Leave</option>
                                    <option value="sick" {{ request('leave_type') == 'sick' ? 'selected' : '' }}>Sick Leave</option>
                                    <option value="unpaid" {{ request('leave_type') == 'unpaid' ? 'selected' : '' }}>Unpaid Leave</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn btn-primary d-block">
                                    <i class="fa fa-filter me-2"></i>Apply Filters
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th><strong>#</strong></th>
                                <th><strong>EMPLOYEE</strong></th>
                                <th><strong>LEAVE TYPE</strong></th>
                                <th><strong>FROM DATE</strong></th>
                                <th><strong>TO DATE</strong></th>
                                <th><strong>DAYS</strong></th>
                                <th><strong>REASON</strong></th>
                                <th><strong>STATUS</strong></th>
                                <th><strong>ACTIONS</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $leave)
                                <tr>
                                    <td>{{ $loop->iteration + ($leaves->currentPage() - 1) * $leaves->perPage() }}</td>
                                    <td>
                                        <strong>{{ $leave->bdm->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $leave->bdm->employee_code ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @if($leave->leave_type == 'casual')
                                            <span class="badge badge-primary">Casual</span>
                                        @elseif($leave->leave_type == 'sick')
                                            <span class="badge badge-warning">Sick</span>
                                        @else
                                            <span class="badge badge-secondary">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</td>
                                    <td>{{ $leave->days }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($leave->reason, 30) }}</td>
                                    <td>
                                        @if($leave->status == 'pending')
                                            <span class="badge light badge-warning">
                                                <i class="fa fa-clock text-warning me-1"></i>Pending
                                            </span>
                                        @elseif($leave->status == 'approved')
                                            <span class="badge light badge-success">
                                                <i class="fa fa-check text-success me-1"></i>Approved
                                            </span>
                                        @else
                                            <span class="badge light badge-danger">
                                                <i class="fa fa-times text-danger me-1"></i>Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-success light sharp" data-bs-toggle="dropdown" aria-expanded="false">
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"/>
                                                        <circle fill="#000000" cx="5" cy="12" r="2"/>
                                                        <circle fill="#000000" cx="12" cy="12" r="2"/>
                                                        <circle fill="#000000" cx="19" cy="12" r="2"/>
                                                    </g>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('admin.leaves.show', $leave->id) }}">
                                                    <i class="fa fa-eye me-2"></i>View Details
                                                </a>
                                                @if($leave->status == 'pending')
                                                    <button type="button" class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $leave->id }}">
                                                        <i class="fa fa-check me-2"></i>Approve
                                                    </button>
                                                    <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $leave->id }}">
                                                        <i class="fa fa-times me-2"></i>Reject
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal{{ $leave->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.leaves.approve', $leave->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Approve Leave</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Remarks (Optional)</label>
                                                        <textarea name="remarks" class="form-control" rows="3" placeholder="Add any remarks..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Approve Leave</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $leave->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('admin.leaves.reject', $leave->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Leave</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Reason for Rejection <span class="text-danger">*</span></label>
                                                        <textarea name="remarks" class="form-control" rows="3" placeholder="Enter reason for rejection..." required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject Leave</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                        No leave applications found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($leaves->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Showing {{ $leaves->firstItem() }} to {{ $leaves->lastItem() }} of {{ $leaves->total() }} entries
                        </div>
                        <div>
                            {{ $leaves->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
