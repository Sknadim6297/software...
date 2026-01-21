@extends('layouts.app')

@section('title', 'Leave History')
@section('page-title', 'Leave Application History')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">All Leave Applications</h4>
                    <a href="{{ route('bdm.leave-salary.apply-form') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Apply New Leave
                    </a>
                </div>
                <div class="card-body">
                    @if($leaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Leave Type</th>
                                        <th>Date Range</th>
                                        <th>Days</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Applied On</th>
                                        <th>Admin Action</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaves as $index => $leave)
                                    <tr>
                                        <td>{{ $leaves->firstItem() + $index }}</td>
                                        <td>
                                            <span class="badge badge-secondary">
                                                {{ $leave->getLeaveTypeLabel() }}
                                            </span>
                                        </td>
                                        <td>
                                            <strong>{{ $leave->date_range }}</strong>
                                        </td>
                                        <td>{{ $leave->number_of_days }}</td>
                                        <td>{{ Str::limit($leave->reason, 50) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $leave->getStatusBadgeColor() }}">
                                                {{ ucfirst($leave->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $leave->applied_at->format('M d, Y h:i A') }}</td>
                                        <td>
                                            @if($leave->admin_action_at)
                                                {{ $leave->admin_action_at->format('M d, Y h:i A') }}
                                            @else
                                                <span class="text-muted">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($leave->admin_remarks)
                                                <button 
                                                    class="btn btn-sm btn-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#remarksModal{{ $leave->id }}"
                                                >
                                                    View Remarks
                                                </button>

                                                <!-- Remarks Modal -->
                                                <div class="modal fade" id="remarksModal{{ $leave->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Admin Remarks</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p><strong>Leave Date:</strong> {{ $leave->date_range }}</p>
                                                                <p><strong>Status:</strong> 
                                                                    <span class="badge badge-{{ $leave->getStatusBadgeColor() }}">
                                                                        {{ ucfirst($leave->status) }}
                                                                    </span>
                                                                </p>
                                                                <p><strong>Remarks:</strong></p>
                                                                <p>{{ $leave->admin_remarks }}</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $leaves->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> No leave applications found. 
                            <a href="{{ route('bdm.leave-salary.apply-form') }}">Apply for leave now</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-xl-12">
            <a href="{{ route('bdm.leave-salary.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
