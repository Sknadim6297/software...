@extends('admin.layouts.app')

@section('title', 'Salary Report')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.reports.index') }}">Reports</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Salary Report</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Monthly Salary Disbursement Report</h4>
                <button onclick="window.print()" class="btn btn-primary btn-sm">
                    <i class="fa fa-print me-2"></i>Print Report
                </button>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <form method="GET" action="{{ route('admin.reports.salary') }}">
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
                    <div class="col-xl-4 col-lg-6 col-sm-6">
                        <div class="widget-stat card bg-primary">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-users"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Total Employees</p>
                                        <h3 class="text-white">{{ $salaries->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-sm-6">
                        <div class="widget-stat card bg-success">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-dollar"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Total Gross Salary</p>
                                        <h3 class="text-white">₹{{ number_format($salaries->sum('gross_salary') / 100000, 2) }}L</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-sm-6">
                        <div class="widget-stat card bg-info">
                            <div class="card-body p-4">
                                <div class="media">
                                    <span class="me-3">
                                        <i class="la la-money"></i>
                                    </span>
                                    <div class="media-body text-white">
                                        <p class="mb-1">Total Net Salary</p>
                                        <h3 class="text-white">₹{{ number_format($totalSalary / 100000, 2) }}L</h3>
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
                                <th><strong>BASIC SALARY</strong></th>
                                <th><strong>HRA</strong></th>
                                <th><strong>ALLOWANCES</strong></th>
                                <th><strong>GROSS SALARY</strong></th>
                                <th><strong>DEDUCTIONS</strong></th>
                                <th><strong>NET SALARY</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salaries as $salary)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $salary->bdm->name ?? 'N/A' }}</td>
                                    <td><strong>{{ $salary->bdm->employee_code ?? 'N/A' }}</strong></td>
                                    <td>₹{{ number_format($salary->basic_salary, 2) }}</td>
                                    <td>₹{{ number_format($salary->hra, 2) }}</td>
                                    <td>₹{{ number_format($salary->other_allowances, 2) }}</td>
                                    <td>₹{{ number_format($salary->gross_salary, 2) }}</td>
                                    <td class="text-danger">₹{{ number_format($salary->deductions, 2) }}</td>
                                    <td><strong>₹{{ number_format($salary->net_salary, 2) }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fa fa-inbox fa-3x mb-3 d-block"></i>
                                        No salary data available for {{ Carbon\Carbon::parse($month)->format('F Y') }}
                                    </td>
                                </tr>
                            @endforelse
                            @if($salaries->count() > 0)
                                <tr class="table-active">
                                    <td colspan="6" class="text-end"><strong>TOTAL:</strong></td>
                                    <td><strong>₹{{ number_format($salaries->sum('gross_salary'), 2) }}</strong></td>
                                    <td class="text-danger"><strong>₹{{ number_format($salaries->sum('deductions'), 2) }}</strong></td>
                                    <td><strong>₹{{ number_format($totalSalary, 2) }}</strong></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
