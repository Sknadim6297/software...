@extends('layouts.app')

@section('title', 'Target Detail')
@section('page-title', 'Target Detail - ' . $target->formatted_period)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Target Information
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Target Type:</strong>
                        <p>{{ ucfirst($target->target_type) }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Period:</strong>
                        <p>{{ $target->formatted_period }}</p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Start Date:</strong>
                        <p>{{ $target->start_date->format('M d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>End Date:</strong>
                        <p>{{ $target->end_date->format('M d, Y') }}</p>
                    </div>
                </div>
                
                <hr>
                
                <h6>Project Target Breakdown</h6>
                <table class="table table-sm table-bordered">
                    <tr>
                        <th>Base Target</th>
                        <td>{{ $target->project_target }} projects</td>
                    </tr>
                    <tr>
                        <th>Carried Forward</th>
                        <td class="{{ $target->carried_forward_projects > 0 ? 'text-danger' : '' }}">
                            {{ $target->carried_forward_projects }} projects
                        </td>
                    </tr>
                    <tr>
                        <th><strong>Total Target</strong></th>
                        <td><strong>{{ $target->total_project_target }} projects</strong></td>
                    </tr>
                    <tr>
                        <th>Achieved</th>
                        <td class="text-success"><strong>{{ $target->projects_achieved }} projects</strong></td>
                    </tr>
                    <tr>
                        <th>Achievement %</th>
                        <td>
                            @php
                                $projectPercentage = $target->total_project_target > 0 
                                    ? ($target->projects_achieved / $target->total_project_target) * 100 
                                    : 0;
                            @endphp
                            <strong class="{{ $projectPercentage >= 80 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($projectPercentage, 2) }}%
                            </strong>
                        </td>
                    </tr>
                </table>
                
                <h6 class="mt-4">Revenue Target Breakdown</h6>
                <table class="table table-sm table-bordered">
                    <tr>
                        <th>Base Target</th>
                        <td>₹{{ number_format($target->revenue_target, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Carried Forward</th>
                        <td class="{{ $target->carried_forward_revenue > 0 ? 'text-danger' : '' }}">
                            ₹{{ number_format($target->carried_forward_revenue, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <th><strong>Total Target</strong></th>
                        <td><strong>₹{{ number_format($target->total_revenue_target, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <th>Achieved</th>
                        <td class="text-success"><strong>₹{{ number_format($target->revenue_achieved, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <th>Achievement %</th>
                        <td>
                            @php
                                $revenuePercentage = $target->total_revenue_target > 0 
                                    ? ($target->revenue_achieved / $target->total_revenue_target) * 100 
                                    : 0;
                            @endphp
                            <strong class="{{ $revenuePercentage >= 80 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($revenuePercentage, 2) }}%
                            </strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-pie"></i> Overall Performance
            </div>
            <div class="card-body text-center">
                <h1 class="display-4 {{ $target->achievement_percentage >= 80 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($target->achievement_percentage, 1) }}%
                </h1>
                <p class="text-muted">Overall Achievement</p>
                
                <div class="progress mb-3" style="height: 30px;">
                    <div class="progress-bar {{ $target->achievement_percentage >= 80 ? 'bg-success' : 'bg-danger' }}" 
                         style="width: {{ min($target->achievement_percentage, 100) }}%">
                        {{ number_format($target->achievement_percentage, 1) }}%
                    </div>
                </div>
                
                <p class="mb-2">
                    <strong>Status:</strong>
                    @if($target->status === 'completed')
                        <span class="badge bg-success">Completed</span>
                    @elseif($target->status === 'failed')
                        <span class="badge bg-danger">Failed</span>
                    @else
                        <span class="badge bg-warning">Pending</span>
                    @endif
                </p>
                
                <p class="mb-2">
                    <strong>Target Met:</strong>
                    @if($target->target_met)
                        <span class="badge bg-success"><i class="fas fa-check"></i> Yes</span>
                    @else
                        <span class="badge bg-danger"><i class="fas fa-times"></i> No</span>
                    @endif
                </p>
                
                @if($target->achievement_percentage < 80)
                    <div class="alert alert-warning mt-3">
                        <small>
                            <i class="fas fa-exclamation-triangle"></i> 
                            Below 80% threshold
                        </small>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <i class="fas fa-lightbulb"></i> Note
            </div>
            <div class="card-body">
                <small>
                    <ul class="mb-0">
                        <li>Minimum 80% achievement required</li>
                        <li>Below 80% results in warning</li>
                        <li>3 consecutive failures = termination</li>
                        <li>Unmet targets carry forward to next month</li>
                    </ul>
                </small>
            </div>
        </div>
        
        <a href="{{ route('bdm.targets') }}" class="btn btn-secondary w-100 mt-3">
            <i class="fas fa-arrow-left"></i> Back to All Targets
        </a>
    </div>
</div>
@endsection
