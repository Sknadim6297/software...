@extends('admin.layouts.app')

@section('title', 'Target Details')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.targets.index') }}">Targets</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Target Details</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Target Details - {{ $target->period }}</h4>
                <div>
                    <a href="{{ route('admin.targets.edit', $target->id) }}" class="btn btn-primary btn-sm me-2">
                        <i class="fa fa-edit me-1"></i>Edit
                    </a>
                    <a href="{{ route('admin.targets.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i>Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">Employee Name:</th>
                        <td>{{ $target->bdm->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Employee Code:</th>
                        <td>{{ $target->bdm->employee_code ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Target Type:</th>
                        <td>
                            @if($target->target_type == 'monthly')
                                <span class="badge badge-primary">Monthly</span>
                            @elseif($target->target_type == 'quarterly')
                                <span class="badge badge-info">Quarterly</span>
                            @else
                                <span class="badge badge-success">Annual</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Period:</th>
                        <td>{{ $target->period }}</td>
                    </tr>
                    <tr>
                        <th>Duration:</th>
                        <td>{{ \Carbon\Carbon::parse($target->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($target->end_date)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Revenue Target:</th>
                        <td>₹{{ number_format($target->revenue_target, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Revenue Achieved:</th>
                        <td><strong class="text-success">₹{{ number_format($target->revenue_achieved, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <th>Project Target:</th>
                        <td>{{ $target->project_target }} projects</td>
                    </tr>
                    <tr>
                        <th>Projects Achieved:</th>
                        <td><strong class="text-success">{{ $target->projects_achieved }} projects</strong></td>
                    </tr>
                    <tr>
                        <th>Achievement Percentage:</th>
                        <td>
                            @php
                                $percentage = $target->achievement_percentage ?? 0;
                            @endphp
                            <span class="badge badge-lg 
                                @if($percentage >= 100) badge-success
                                @elseif($percentage >= 75) badge-info
                                @elseif($percentage >= 50) badge-warning
                                @else badge-danger
                                @endif
                            ">
                                {{ number_format($percentage, 1) }}%
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            @if($percentage >= 100)
                                <span class="badge badge-success">Target Met</span>
                            @elseif(\Carbon\Carbon::parse($target->end_date)->isPast())
                                <span class="badge badge-danger">Not Met</span>
                            @else
                                <span class="badge badge-warning">In Progress</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created At:</th>
                        <td>{{ $target->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Progress Overview</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Revenue Progress</h6>
                    <div class="progress mb-2" style="height: 25px;">
                        @php
                            $revenuePercent = $target->revenue_target > 0 ? ($target->revenue_achieved / $target->revenue_target) * 100 : 0;
                        @endphp
                        <div class="progress-bar 
                            @if($revenuePercent >= 100) bg-success
                            @elseif($revenuePercent >= 75) bg-info
                            @elseif($revenuePercent >= 50) bg-warning
                            @else bg-danger
                            @endif
                        " role="progressbar" style="width: {{ min($revenuePercent, 100) }}%">
                            {{ number_format($revenuePercent, 1) }}%
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small>₹{{ number_format($target->revenue_achieved, 0) }}</small>
                        <small>₹{{ number_format($target->revenue_target, 0) }}</small>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted mb-2">Project Progress</h6>
                    <div class="progress mb-2" style="height: 25px;">
                        @php
                            $projectPercent = $target->project_target > 0 ? ($target->projects_achieved / $target->project_target) * 100 : 0;
                        @endphp
                        <div class="progress-bar 
                            @if($projectPercent >= 100) bg-success
                            @elseif($projectPercent >= 75) bg-info
                            @elseif($projectPercent >= 50) bg-warning
                            @else bg-danger
                            @endif
                        " role="progressbar" style="width: {{ min($projectPercent, 100) }}%">
                            {{ number_format($projectPercent, 1) }}%
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small>{{ $target->projects_achieved }} projects</small>
                        <small>{{ $target->project_target }} projects</small>
                    </div>
                </div>

                <div class="alert alert-{{ $percentage >= 100 ? 'success' : 'warning' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <span><strong>Overall Achievement</strong></span>
                        <strong>{{ number_format($percentage, 1) }}%</strong>
                    </div>
                </div>

                <button type="button" class="btn btn-primary btn-block mt-3" data-bs-toggle="modal" data-bs-target="#updateAchievementModal">
                    <i class="fa fa-chart-line me-2"></i>Update Achievement
                </button>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title">Time Progress</h5>
            </div>
            <div class="card-body">
                @php
                    $totalDays = \Carbon\Carbon::parse($target->start_date)->diffInDays(\Carbon\Carbon::parse($target->end_date));
                    $elapsed = \Carbon\Carbon::parse($target->start_date)->diffInDays(\Carbon\Carbon::now());
                    $timePercent = $totalDays > 0 ? min(($elapsed / $totalDays) * 100, 100) : 0;
                @endphp
                <div class="progress mb-3" style="height: 20px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $timePercent }}%">
                        {{ number_format($timePercent, 0) }}%
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <small>Start: {{ \Carbon\Carbon::parse($target->start_date)->format('d M Y') }}</small>
                    <small>End: {{ \Carbon\Carbon::parse($target->end_date)->format('d M Y') }}</small>
                </div>
                <div class="text-center">
                    @php
                        $remaining = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($target->end_date), false);
                    @endphp
                    @if($remaining > 0)
                        <span class="badge badge-info">{{ $remaining }} days remaining</span>
                    @elseif($remaining == 0)
                        <span class="badge badge-warning">Last day!</span>
                    @else
                        <span class="badge badge-danger">Ended {{ abs($remaining) }} days ago</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Achievement Modal -->
<div class="modal fade" id="updateAchievementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.targets.update-achievement', $target->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Update Achievement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Revenue Achieved (₹) <span class="text-danger">*</span></label>
                        <input type="number" name="revenue_achieved" class="form-control" value="{{ $target->revenue_achieved }}" min="0" step="0.01" required>
                        <small class="text-muted">Target: ₹{{ number_format($target->revenue_target, 2) }}</small>
                    </div>
                    <div class="form-group">
                        <label>Projects Achieved <span class="text-danger">*</span></label>
                        <input type="number" name="projects_achieved" class="form-control" value="{{ $target->projects_achieved }}" min="0" required>
                        <small class="text-muted">Target: {{ $target->project_target }} projects</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Achievement</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
