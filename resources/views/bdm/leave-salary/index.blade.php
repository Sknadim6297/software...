@extends('layouts.app')

@section('title', 'Leave & Salary Slip')
@section('page-title', 'Leave & Salary Slip')

@section('content')
<div class="container-fluid">
    <!-- Leave Balance Section -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Leave Balance Overview</h4>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light">
                                <h3 class="text-primary mb-2">{{ $leaveBalance->casual_leave_allocated ?? 0 }}</h3>
                                <p class="text-muted mb-0">Total CL Allocated</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light">
                                <h3 class="text-success mb-2">{{ $leaveBalance->casual_leave_balance ?? 0 }}</h3>
                                <p class="text-muted mb-0">CL Remaining</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light">
                                <h3 class="text-info mb-2">{{ $leaveBalance->sick_leave_allocated ?? 0 }}</h3>
                                <p class="text-muted mb-0">Total SL Allocated</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3 bg-light">
                                <h3 class="text-warning mb-2">{{ $leaveBalance->sick_leave_balance ?? 0 }}</h3>
                                <p class="text-muted mb-0">SL Remaining</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 text-end">
                        <a href="{{ route('bdm.leave-salary.apply-form') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Apply for Leave
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Leave Applications -->
    @if($pendingLeaves->count() > 0)
    <div class="row mt-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Pending Leave Applications</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Days</th>
                                    <th>Reason</th>
                                    <th>Applied On</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingLeaves as $leave)
                                <tr>
                                    <td><span class="badge badge-secondary">{{ $leave->getLeaveTypeLabel() }}</span></td>
                                    <td>{{ $leave->from_date->format('M d, Y') }}</td>
                                    <td>{{ $leave->to_date->format('M d, Y') }}</td>
                                    <td>{{ $leave->number_of_days }}</td>
                                    <td>{{ Str::limit($leave->reason, 50) }}</td>
                                    <td>{{ $leave->applied_at->format('M d, Y') }}</td>
                                    <td><span class="badge badge-{{ $leave->getStatusBadgeColor() }}">{{ ucfirst($leave->status) }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Approved Leaves This Month -->
    @if($approvedLeavesThisMonth->count() > 0)
    <div class="row mt-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Approved Leaves This Month</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Date Range</th>
                                    <th>Days</th>
                                    <th>Reason</th>
                                    <th>Admin Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($approvedLeavesThisMonth as $leave)
                                <tr>
                                    <td><span class="badge badge-success">{{ $leave->getLeaveTypeLabel() }}</span></td>
                                    <td>{{ $leave->date_range }}</td>
                                    <td>{{ $leave->number_of_days }}</td>
                                    <td>{{ Str::limit($leave->reason, 40) }}</td>
                                    <td>{{ $leave->admin_remarks ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Monthly Salary Slips -->
    <div class="row mt-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Monthly Salary Slips</h4>
                </div>
                <div class="card-body">
                    @if($salarySlips->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Month</th>
                                        <th>Total Present Days</th>
                                        <th>Leave Taken (CL/SL/UL)</th>
                                        <th>Net Salary</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salarySlips as $salary)
                                    <tr>
                                        <td><strong>{{ $salary->formatted_month }}</strong></td>
                                        <td>{{ $salary->total_present_days }}</td>
                                        <td>
                                            <span class="badge badge-primary">CL: {{ $salary->casual_leave_taken }}</span>
                                            <span class="badge badge-info">SL: {{ $salary->sick_leave_taken }}</span>
                                            <span class="badge badge-warning">UL: {{ $salary->unpaid_leave_taken }}</span>
                                        </td>
                                        <td><strong>₹{{ number_format($salary->net_salary, 2) }}</strong></td>
                                        <td>
                                            @if($salary->is_regenerated)
                                                <span class="badge badge-warning">Regenerated</span>
                                            @else
                                                <span class="badge badge-success">Generated</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($salary->salary_slip_path)
                                                <a href="{{ route('bdm.leave-salary.salary-download', $salary->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-download"></i> Download
                                                </a>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="fa fa-clock"></i> Processing
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $salarySlips->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> No salary slips available yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- View All Leaves Link -->
    <div class="row mt-3">
        <div class="col-xl-12 text-end">
            <a href="{{ route('bdm.leave-salary.history') }}" class="btn btn-outline-primary">
                <i class="fa fa-history"></i> View All Leave History
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert-success, .alert-info').fadeOut('slow');
    }, 5000);
</script>
@endsection
