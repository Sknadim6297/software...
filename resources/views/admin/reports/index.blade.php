@extends('admin.layouts.app')

@section('title', 'Reports Dashboard')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Reports</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Reports Dashboard</h4>
            </div>
            <div class="card-body">
                <p class="mb-4">Generate and view various reports related to employee performance, attendance, salaries, and targets.</p>
                
                <div class="row">
                    <!-- Target Report -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="flaticon-381-diploma fa-3x text-primary"></i>
                                </div>
                                <h5 class="card-title">Target Report</h5>
                                <p class="card-text text-muted">View monthly target achievements and performance metrics</p>
                                <a href="{{ route('admin.reports.target') }}" class="btn btn-primary">
                                    <i class="fa fa-chart-line me-2"></i>View Report
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Salary Report -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="flaticon-381-price-tag fa-3x text-success"></i>
                                </div>
                                <h5 class="card-title">Salary Report</h5>
                                <p class="card-text text-muted">Monthly salary disbursement and payroll reports</p>
                                <a href="{{ route('admin.reports.salary') }}" class="btn btn-success">
                                    <i class="fa fa-money-bill me-2"></i>View Report
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Report -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="flaticon-381-calendar-1 fa-3x text-warning"></i>
                                </div>
                                <h5 class="card-title">Leave Report</h5>
                                <p class="card-text text-muted">Monthly leave applications and approval status</p>
                                <a href="{{ route('admin.reports.leave') }}" class="btn btn-warning">
                                    <i class="fa fa-calendar me-2"></i>View Report
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Report -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="flaticon-381-trophy fa-3x text-info"></i>
                                </div>
                                <h5 class="card-title">Performance Report</h5>
                                <p class="card-text text-muted">Employee performance analytics and ratings</p>
                                <a href="{{ route('admin.reports.performance') }}" class="btn btn-info">
                                    <i class="fa fa-chart-bar me-2"></i>View Report
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Report -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-secondary">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="flaticon-381-user-9 fa-3x text-secondary"></i>
                                </div>
                                <h5 class="card-title">Attendance Report</h5>
                                <p class="card-text text-muted">Monthly attendance tracking and analysis</p>
                                <a href="{{ route('admin.reports.attendance') }}" class="btn btn-secondary">
                                    <i class="fa fa-clipboard-check me-2"></i>View Report
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Custom Reports -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-dark">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="flaticon-381-settings-1 fa-3x text-dark"></i>
                                </div>
                                <h5 class="card-title">Custom Reports</h5>
                                <p class="card-text text-muted">Generate custom reports with specific filters</p>
                                <button class="btn btn-dark" disabled>
                                    <i class="fa fa-cog me-2"></i>Coming Soon
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Quick Stats</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
                        <div class="widget-stat card bg-primary">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-users"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Total Employees</p>
                                        <h3 class="text-white">{{ \App\Models\BDM::count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
                        <div class="widget-stat card bg-success">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-check"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Active Employees</p>
                                        <h3 class="text-white">{{ \App\Models\BDM::where('status', 'active')->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
                        <div class="widget-stat card bg-warning">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-clock"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Pending Leaves</p>
                                        <h3 class="text-white">{{ \App\Models\BDMLeaveApplication::where('status', 'pending')->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
                        <div class="widget-stat card bg-info">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-chart-line"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">This Month Targets</p>
                                        <h3 class="text-white">{{ \App\Models\BDMTarget::where('period', \Carbon\Carbon::now()->format('Y-m'))->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
