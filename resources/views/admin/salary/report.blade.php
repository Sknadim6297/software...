@extends('admin.layouts.app')

@section('title', 'Salary Report')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col p-md-0">
            <h4>Salary Report</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Filter Report</h4>
                </div>
                <div class="card-body">
                    <form method="GET" class="form-inline">
                        <div class="form-group mr-2 mb-2">
                            <select name="year" class="form-control">
                                @for($y = now()->year - 5; $y <= now()->year; $y++)
                                    <option value="{{ $y }}" {{ request('year', now()->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <select name="month" class="form-control">
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ request('month', now()->month) == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($report)
    <div class="row">
        <!-- Summary Cards -->
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card bg-primary">
                <div class="card-body p-4">
                    <div class="media">
                        <span class="mr-3">
                            <i class="flaticon-381-user-7 text-white" style="font-size: 50px;"></i>
                        </span>
                        <div class="media-body text-white">
                            <p class="mb-1">Total Employees</p>
                            <h3 class="text-white">{{ $report->total_employees ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card bg-success">
                <div class="card-body p-4">
                    <div class="media">
                        <span class="mr-3">
                            <i class="flaticon-381-dollar text-white" style="font-size: 50px;"></i>
                        </span>
                        <div class="media-body text-white">
                            <p class="mb-1">Total Base Salary</p>
                            <h3 class="text-white">₹{{ number_format($report->total_base_salary ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card bg-warning">
                <div class="card-body p-4">
                    <div class="media">
                        <span class="mr-3">
                            <i class="flaticon-381-minus text-white" style="font-size: 50px;"></i>
                        </span>
                        <div class="media-body text-white">
                            <p class="mb-1">Total Deductions</p>
                            <h3 class="text-white">₹{{ number_format($report->total_deductions ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card bg-info">
                <div class="card-body p-4">
                    <div class="media">
                        <span class="mr-3">
                            <i class="flaticon-381-dollar text-white" style="font-size: 50px;"></i>
                        </span>
                        <div class="media-body text-white">
                            <p class="mb-1">Total Net Salary</p>
                            <h3 class="text-white">₹{{ number_format($report->total_net_salary ?? 0, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="widget-stat card">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Report Summary</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <td><strong>Month:</strong></td>
                                    <td>{{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Employees:</strong></td>
                                    <td>{{ $report->total_employees ?? 0 }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Average Salary:</strong></td>
                                    <td>₹{{ number_format($report->average_salary ?? 0, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">Financial Summary</h5>
                            <table class="table table-bordered">
                                <tr>
                                    <td><strong>Total Base Salary:</strong></td>
                                    <td>₹{{ number_format($report->total_base_salary ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Deductions:</strong></td>
                                    <td>₹{{ number_format($report->total_deductions ?? 0, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Net Salary:</strong></td>
                                    <td>₹{{ number_format($report->total_net_salary ?? 0, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($topEarners->count() > 0)
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Top 10 Earners</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Employee Name</th>
                                    <th>Base Salary</th>
                                    <th>Deductions</th>
                                    <th>Net Salary</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topEarners as $index => $earner)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $earner->bdm->user->name ?? 'N/A' }}</td>
                                    <td>₹{{ number_format($earner->basic_salary ?? 0, 2) }}</td>
                                    <td>₹{{ number_format($earner->deductions ?? 0, 2) }}</td>
                                    <td><strong>₹{{ number_format($earner->net_salary ?? 0, 2) }}</strong></td>
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

    @else
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info" role="alert">
                <strong>No data available</strong> for the selected period.
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
