@extends('admin.layouts.app')

@section('title', 'Target Report')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Target Report</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Monthly Target Achievement Report</h4>
                <button onclick="window.print()" class="btn btn-primary btn-sm">
                    <i class="fa fa-print me-2"></i>Print Report
                </button>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('admin.reports.target') }}">
                            <div class="input-group">
                                <input type="month" name="month" class="form-control" value="{{ $month }}">
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
                                        <p class="mb-1">Total BDMs</p>
                                        <h3 class="text-white">{{ $targets->count() }}</h3>
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
                                        <i class="la la-check-circle"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Targets Met</p>
                                        <h3 class="text-white">{{ $targets->where('achievement_percentage', '>=', 100)->count() }}</h3>
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
                                        <i class="la la-dollar"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Total Revenue Target</p>
                                        <h3 class="text-white">₹{{ number_format($targets->sum('revenue_target') / 100000, 2) }}L</h3>
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
                                        <i class="la la-chart-line"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Revenue Achieved</p>
                                        <h3 class="text-white">₹{{ number_format($targets->sum('revenue_achieved') / 100000, 2) }}L</h3>
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
                                <th><strong>REVENUE TARGET</strong></th>
                                <th><strong>REVENUE ACHIEVED</strong></th>
                                <th><strong>PROJECT TARGET</strong></th>
                                <th><strong>PROJECT ACHIEVED</strong></th>
                                <th><strong>ACHIEVEMENT %</strong></th>
                                <th><strong>STATUS</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($targets as $target)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $target->bdm->name ?? 'N/A' }}</td>
                                    <td><strong>{{ $target->bdm->employee_code ?? 'N/A' }}</strong></td>
                                    <td>₹{{ number_format($target->revenue_target, 2) }}</td>
                                    <td>₹{{ number_format($target->revenue_achieved, 2) }}</td>
                                    <td>{{ $target->project_target }}</td>
                                    <td>{{ $target->projects_achieved }}</td>
                                    <td>
                                        <span class="badge badge-lg 
                                            @if($target->achievement_percentage >= 100) badge-success
                                            @elseif($target->achievement_percentage >= 75) badge-info
                                            @elseif($target->achievement_percentage >= 50) badge-warning
                                            @else badge-danger
                                            @endif
                                        ">
                                            {{ number_format($target->achievement_percentage, 1) }}%
                                        </span>
                                    </td>
                                    <td>
                                        @if($target->achievement_percentage >= 100)
                                            <span class="badge badge-success">Target Met</span>
                                        @else
                                            <span class="badge badge-warning">In Progress</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                        No target data available for {{ Carbon\Carbon::parse($month)->format('F Y') }}
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
