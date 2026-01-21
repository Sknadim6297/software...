@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">My Leave Requests</h4>
                    <a href="{{ route('leaves.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Apply for Leave
                    </a>
                </div>
                <div class="card-body">
                    @if($leaves->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="bg-light">
                                    <tr>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                        <th>Type</th>
                                        <th>Days</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Actions</th>
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
                                            <td>{{ Str::limit($leave->reason, 30) }}</td>
                                            <td>
                                                @if($leave->status === 'approved')
                                                    <span class="badge badge-success">Approved</span>
                                                @elseif($leave->status === 'rejected')
                                                    <span class="badge badge-danger">Rejected</span>
                                                @elseif($leave->status === 'pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ ucfirst($leave->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('leaves.show', $leave) }}" class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($leave->status === 'pending')
                                                    <form action="{{ route('leaves.cancel', $leave) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Cancel" onclick="return confirm('Are you sure?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $leaves->links() }}
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-inbox"></i> No leave requests found.
                            <a href="{{ route('leaves.create') }}" class="alert-link">Apply for leave</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
