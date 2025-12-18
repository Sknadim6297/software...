@extends('layouts.app')

@section('title', 'Target Management')
@section('page-title', 'Target Management')

@section('content')
@if($currentMonthTarget)
    <div class="card mb-4">
        <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);">
            <h5 class="mb-0 text-white"><i class="fas fa-bullseye"></i> Current Month Target - {{ $currentMonthTarget->formatted_period }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Project Target</h6>
                    <div class="progress mb-2" style="height: 30px;">
                        @php
                            $projectPercentage = $currentMonthTarget->total_project_target > 0 
                                ? ($currentMonthTarget->projects_achieved / $currentMonthTarget->total_project_target) * 100 
                                : 0;
                        @endphp
                        <div class="progress-bar bg-success" style="width: {{ min($projectPercentage, 100) }}%">
                            {{ number_format($projectPercentage, 1) }}%
                        </div>
                    </div>
                    <p class="mb-0">
                        <strong>{{ $currentMonthTarget->projects_achieved }}</strong> / {{ $currentMonthTarget->total_project_target }} Projects
                    </p>
                    @if($currentMonthTarget->carried_forward_projects > 0)
                        <small class="text-danger">
                            ({{ $currentMonthTarget->carried_forward_projects }} carried forward from previous month)
                        </small>
                    @endif
                </div>
                <div class="col-md-6">
                    <h6>Revenue Target</h6>
                    <div class="progress mb-2" style="height: 30px;">
                        @php
                            $revenuePercentage = $currentMonthTarget->total_revenue_target > 0 
                                ? ($currentMonthTarget->revenue_achieved / $currentMonthTarget->total_revenue_target) * 100 
                                : 0;
                        @endphp
                        <div class="progress-bar bg-info" style="width: {{ min($revenuePercentage, 100) }}%">
                            {{ number_format($revenuePercentage, 1) }}%
                        </div>
                    </div>
                    <p class="mb-0">
                        <strong>₹{{ number_format($currentMonthTarget->revenue_achieved, 2) }}</strong> / ₹{{ number_format($currentMonthTarget->total_revenue_target, 2) }}
                    </p>
                    @if($currentMonthTarget->carried_forward_revenue > 0)
                        <small class="text-danger">
                            (₹{{ number_format($currentMonthTarget->carried_forward_revenue, 2) }} carried forward from previous month)
                        </small>
                    @endif
                </div>
            </div>
            
            <hr>
            
            <div class="row text-center">
                <div class="col-md-3">
                    <h3 class="mb-0">{{ number_format($currentMonthTarget->achievement_percentage, 1) }}%</h3>
                    <small class="text-muted">Overall Achievement</small>
                </div>
                <div class="col-md-3">
                    <h3 class="mb-0 {{ $currentMonthTarget->achievement_percentage >= 80 ? 'text-success' : 'text-danger' }}">
                        {{ $currentMonthTarget->achievement_percentage >= 80 ? '✓' : '✗' }}
                    </h3>
                    <small class="text-muted">80% Threshold</small>
                </div>
                <div class="col-md-3">
                    <h3 class="mb-0">{{ $currentMonthTarget->end_date->diffInDays(now()) }}</h3>
                    <small class="text-muted">Days Remaining</small>
                </div>
                <div class="col-md-3">
                    <h3 class="mb-0">
                        @if($currentMonthTarget->status === 'completed')
                            <span class="badge bg-success">Completed</span>
                        @elseif($currentMonthTarget->status === 'failed')
                            <span class="badge bg-danger">Failed</span>
                        @else
                            <span class="badge bg-warning">Pending</span>
                        @endif
                    </h3>
                    <small class="text-muted">Status</small>
                </div>
            </div>
            
            @if($currentMonthTarget->achievement_percentage < 80)
                <div class="alert alert-warning mt-3 mb-0">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Warning:</strong> Your current achievement is below the 80% threshold. Please work towards meeting your targets to avoid warnings.
                </div>
            @endif
        </div>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <i class="fas fa-chart-line"></i> All Targets History
    </div>
    <div class="card-body">
        @if($targets->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Type</th>
                            <th>Project Target</th>
                            <th>Revenue Target</th>
                            <th>Achievement</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($targets as $target)
                            <tr>
                                <td><strong>{{ $target->formatted_period }}</strong></td>
                                <td>
                                    <span class="badge bg-secondary">{{ ucfirst($target->target_type) }}</span>
                                </td>
                                <td>
                                    {{ $target->projects_achieved }} / {{ $target->total_project_target }}
                                    @if($target->carried_forward_projects > 0)
                                        <br><small class="text-muted">(+{{ $target->carried_forward_projects }} CF)</small>
                                    @endif
                                </td>
                                <td>
                                    ₹{{ number_format($target->revenue_achieved, 0) }} / ₹{{ number_format($target->total_revenue_target, 0) }}
                                    @if($target->carried_forward_revenue > 0)
                                        <br><small class="text-muted">(+₹{{ number_format($target->carried_forward_revenue, 0) }} CF)</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar {{ $target->achievement_percentage >= 80 ? 'bg-success' : 'bg-danger' }}" 
                                             style="width: {{ min($target->achievement_percentage, 100) }}%">
                                            {{ number_format($target->achievement_percentage, 1) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($target->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($target->status === 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('bdm.targets.detail', $target->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $targets->links() }}
        @else
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle"></i> No targets assigned yet.
            </div>
        @endif
    </div>
</div>
@endsection
