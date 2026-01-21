@extends('layouts.app')

@section('title', 'Leave Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Leave Request Details</h4>
                    <a href="{{ route('leaves.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted">From Date:</label>
                            <p class="h6">{{ $leave->from_date->format('d M, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">To Date:</label>
                            <p class="h6">{{ $leave->to_date->format('d M, Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted">Leave Type:</label>
                            <p><span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">Total Days:</label>
                            <p class="h6">{{ $leave->getLeaveDaysCount() }} day(s)</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Reason:</label>
                        <p class="border p-3 bg-light">{{ $leave->reason }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted">Status:</label>
                        <p>
                            @if($leave->status === 'approved')
                                <span class="badge badge-success" style="font-size: 16px;">Approved</span>
                            @elseif($leave->status === 'rejected')
                                <span class="badge badge-danger" style="font-size: 16px;">Rejected</span>
                            @elseif($leave->status === 'pending')
                                <span class="badge badge-warning" style="font-size: 16px;">Pending</span>
                            @else
                                <span class="badge badge-secondary" style="font-size: 16px;">{{ ucfirst($leave->status) }}</span>
                            @endif
                        </p>
                    </div>

                    @if($leave->admin_notes)
                        <div class="alert alert-info">
                            <strong>Admin Notes:</strong><br>
                            {{ $leave->admin_notes }}
                        </div>
                    @endif

                    @if($leave->approved_by)
                        <div class="mb-3">
                            <label class="text-muted">Approved/Rejected By:</label>
                            <p>{{ $leave->approvedBy->name }} on {{ $leave->approved_at->format('d M, Y h:i A') }}</p>
                        </div>
                    @endif

                    @if($leave->status === 'pending')
                        <form action="{{ route('leaves.cancel', $leave) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this leave request?')">
                                <i class="fas fa-trash"></i> Cancel Request
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
