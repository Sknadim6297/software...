@extends('layouts.app')

@section('title', 'BDM Dashboard')
@section('page-title', 'BDM Dashboard')

@section('content')
<div class="row">
    <!-- Profile Card -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($bdm->profile_image)
                    <img src="{{ asset('storage/' . $bdm->profile_image) }}" alt="Profile" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 120px; height: 120px;">
                        <i class="fas fa-user fa-3x text-white"></i>
                    </div>
                @endif
                <h5>{{ $bdm->name }}</h5>
                <p class="text-muted mb-1">{{ $bdm->employee_code }}</p>
                <p class="text-muted mb-3">{{ $bdm->email }}</p>
                
                @if($bdm->status === 'warned')
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <strong>Warning {{ $bdm->warning_count }}/3</strong>
                        <br><small>{{ $bdm->last_warning_date->format('M d, Y') }}</small>
                    </div>
                @endif
                
                <a href="{{ route('bdm.profile') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> View Profile
                </a>
            </div>
        </div>
    </div>
    
    <!-- Current Target Card -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bullseye"></i> Current Month Target
            </div>
            <div class="card-body">
                @if($currentTarget)
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Project Target</h6>
                            <div class="progress mb-3" style="height: 25px;">
                                @php
                                    $projectPercentage = $currentTarget->total_project_target > 0 
                                        ? ($currentTarget->projects_achieved / $currentTarget->total_project_target) * 100 
                                        : 0;
                                @endphp
                                <div class="progress-bar bg-success" style="width: {{ min($projectPercentage, 100) }}%">
                                    {{ number_format($projectPercentage, 1) }}%
                                </div>
                            </div>
                            <p class="mb-0">
                                <strong>{{ $currentTarget->projects_achieved }}</strong> / {{ $currentTarget->total_project_target }} Projects
                            </p>
                            @if($currentTarget->carried_forward_projects > 0)
                                <small class="text-muted">
                                    ({{ $currentTarget->carried_forward_projects }} carried forward)
                                </small>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>Revenue Target</h6>
                            <div class="progress mb-3" style="height: 25px;">
                                @php
                                    $revenuePercentage = $currentTarget->total_revenue_target > 0 
                                        ? ($currentTarget->revenue_achieved / $currentTarget->total_revenue_target) * 100 
                                        : 0;
                                @endphp
                                <div class="progress-bar bg-info" style="width: {{ min($revenuePercentage, 100) }}%">
                                    {{ number_format($revenuePercentage, 1) }}%
                                </div>
                            </div>
                            <p class="mb-0">
                                <strong>₹{{ number_format($currentTarget->revenue_achieved, 2) }}</strong> / ₹{{ number_format($currentTarget->total_revenue_target, 2) }}
                            </p>
                            @if($currentTarget->carried_forward_revenue > 0)
                                <small class="text-muted">
                                    (₹{{ number_format($currentTarget->carried_forward_revenue, 2) }} carried forward)
                                </small>
                            @endif
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h3 class="mb-0">{{ number_format($currentTarget->achievement_percentage, 1) }}%</h3>
                            <small class="text-muted">Overall Achievement</small>
                        </div>
                        <div class="col-md-4">
                            <h3 class="mb-0 {{ $currentTarget->target_met ? 'text-success' : 'text-danger' }}">
                                @if($currentTarget->target_met)
                                    <i class="fas fa-check-circle"></i>
                                @else
                                    <i class="fas fa-times-circle"></i>
                                @endif
                            </h3>
                            <small class="text-muted">{{ $currentTarget->target_met ? 'Target Met' : 'Target Pending' }}</small>
                        </div>
                        <div class="col-md-4">
                            <h3 class="mb-0">{{ $currentTarget->end_date->diffInDays(now()) }}</h3>
                            <small class="text-muted">Days Remaining</small>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="{{ route('bdm.targets') }}" class="btn btn-primary">
                            <i class="fas fa-chart-line"></i> View All Targets
                        </a>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> No target assigned for current month.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <!-- Leave Balance Card -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-calendar-check"></i> Leave Balance
            </div>
            <div class="card-body">
                @if($bdm->isEligibleForLeaves())
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h4 class="mb-0">{{ $leaveBalance->casual_leave_balance ?? 0 }}</h4>
                            <small class="text-muted">Casual Leaves</small>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $leaveBalance->sick_leave_balance ?? 0 }}</h4>
                            <small class="text-muted">Sick Leaves</small>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mb-0">
                        <small>
                            Used this month: {{ $leaveBalance->casual_leave_used_this_month ?? 0 }} CL, {{ $leaveBalance->sick_leave_used_this_month ?? 0 }} SL
                        </small>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-clock"></i> You are not eligible for leaves yet. 
                        <br><small>Please wait 6 months from joining date.</small>
                    </div>
                @endif
                
                <div class="text-center mt-3">
                    <a href="{{ route('bdm.leaves') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Apply Leave
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Leaves Card -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-history"></i> Recent Leave Applications
            </div>
            <div class="card-body">
                @if($recentLeaves->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Leave Date</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Applied On</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLeaves as $leave)
                                    <tr>
                                        <td>{{ $leave->leave_date->format('M d, Y') }}</td>
                                        <td>{{ ucfirst($leave->leave_type) }}</td>
                                        <td>
                                            @if($leave->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($leave->status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $leave->applied_at->format('M d, Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle"></i> No recent leave applications.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Notifications Alert -->
@if($unreadNotifications > 0)
    <div class="alert alert-warning mt-3">
        <i class="fas fa-bell"></i> You have <strong>{{ $unreadNotifications }}</strong> unread notification(s).
        <a href="{{ route('bdm.notifications') }}" class="alert-link">View Now</a>
    </div>
@endif
@endsection
