@extends('admin.layouts.app')

@section('title', 'Performance Report')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Performance Report</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Employee Performance Report</h4>
                <button onclick="window.print()" class="btn btn-primary btn-sm">
                    <i class="fa fa-print me-2"></i>Print Report
                </button>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('admin.reports.performance') }}">
                            <div class="input-group">
                                <select name="period" class="form-control">
                                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="quarterly" {{ $period == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="annual" {{ $period == 'annual' ? 'selected' : '' }}>Annual</option>
                                </select>
                                <input type="month" name="date" class="form-control" value="{{ $date }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="widget-stat card bg-primary">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-users"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Active BDMs</p>
                                        <h3 class="text-white">{{ $bdms->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="widget-stat card bg-success">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-trophy"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Top Performers</p>
                                        <h3 class="text-white">{{ $bdms->filter(function($bdm) { return $bdm->targets->where('achievement_percentage', '>=', 100)->count() > 0; })->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="widget-stat card bg-warning">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-chart-line"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Avg Achievement</p>
                                        <h3 class="text-white">{{ $bdms->count() > 0 ? number_format($bdms->flatMap->targets->avg('achievement_percentage'), 1) : 0 }}%</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6">
                        <div class="widget-stat card bg-info">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-dollar"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Total Revenue</p>
                                        <h3 class="text-white">₹{{ number_format($bdms->flatMap->targets->sum('revenue_achieved') / 100000, 2) }}L</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Report Table -->
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th><strong>#</strong></th>
                                <th><strong>EMPLOYEE</strong></th>
                                <th><strong>CODE</strong></th>
                                <th><strong>TARGETS</strong></th>
                                <th><strong>REVENUE</strong></th>
                                <th><strong>PROJECTS</strong></th>
                                <th><strong>ACHIEVEMENT</strong></th>
                                <th><strong>RATING</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bdms as $bdm)
                                @php
                                    $targetData = $bdm->targets->first();
                                    $achievement = $targetData ? $targetData->achievement_percentage : 0;
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bdm->name }}</td>
                                    <td><strong>{{ $bdm->employee_code }}</strong></td>
                                    <td>{{ $bdm->total_targets ?? 0 }}</td>
                                    <td>₹{{ $targetData ? number_format($targetData->revenue_achieved, 2) : '0.00' }}</td>
                                    <td>{{ $targetData ? $targetData->projects_achieved : 0 }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar 
                                                @if($achievement >= 100) bg-success
                                                @elseif($achievement >= 75) bg-info
                                                @elseif($achievement >= 50) bg-warning
                                                @else bg-danger
                                                @endif
                                            " role="progressbar" style="width: {{ min($achievement, 100) }}%">
                                                {{ number_format($achievement, 0) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($achievement >= 100)
                                            <span class="badge badge-success">Excellent</span>
                                        @elseif($achievement >= 75)
                                            <span class="badge badge-info">Good</span>
                                        @elseif($achievement >= 50)
                                            <span class="badge badge-warning">Average</span>
                                        @else
                                            <span class="badge badge-danger">Needs Improvement</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                        No performance data available for {{ Carbon\Carbon::parse($date)->format('F Y') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
