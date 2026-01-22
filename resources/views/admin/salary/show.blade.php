@extends('admin.layouts.app')

@section('title', 'Salary Details')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col p-md-0">
            <h4>Salary Details</h4>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.salary.index') }}">Salary</a></li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        Salary Slip - {{ $salary->bdm->name ?? 'N/A' }}
                        @if($salary->bdm && $salary->bdm->designation)
                            <span class="badge badge-primary">{{ $salary->bdm->designation }}</span>
                        @endif
                        <span class="badge badge-info">{{ $salary->formatted_month }}</span>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Employee Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Name:</th>
                                    <td>{{ $salary->bdm->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $salary->bdm->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Employee Code:</th>
                                    <td>{{ $salary->bdm->employee_code ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Designation:</th>
                                    <td><span class="badge badge-primary">{{ $salary->bdm->designation ?? 'BDM' }}</span></td>
                                </tr>
                                <tr>
                                    <th>Month:</th>
                                    <td>{{ $salary->formatted_month }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Attendance Summary</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Present Days:</th>
                                    <td><span class="badge badge-success">{{ $salary->total_present_days ?? 0 }}</span></td>
                                </tr>
                                <tr>
                                    <th>Casual Leave:</th>
                                    <td><span class="badge badge-info">{{ $salary->casual_leave_taken ?? 0 }}</span></td>
                                </tr>
                                <tr>
                                    <th>Sick Leave:</th>
                                    <td><span class="badge badge-warning">{{ $salary->sick_leave_taken ?? 0 }}</span></td>
                                </tr>
                                <tr>
                                    <th>Unpaid Leave:</th>
                                    <td><span class="badge badge-danger">{{ $salary->unpaid_leave_taken ?? 0 }}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h5>Salary Breakdown</h5>
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Component</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Basic Salary</strong></td>
                                        <td class="text-right">₹{{ number_format($salary->basic_salary, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>HRA</td>
                                        <td class="text-right">₹{{ number_format($salary->hra ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Other Allowances</td>
                                        <td class="text-right">₹{{ number_format($salary->other_allowances ?? 0, 2) }}</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td><strong>Gross Salary</strong></td>
                                        <td class="text-right"><strong>₹{{ number_format($salary->gross_salary, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Leave Deduction</td>
                                        <td class="text-right text-danger">- ₹{{ number_format($salary->leave_deduction ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Other Deductions</td>
                                        <td class="text-right text-danger">- ₹{{ number_format($salary->deductions - ($salary->leave_deduction ?? 0), 2) }}</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td><strong>Total Deductions</strong></td>
                                        <td class="text-right"><strong class="text-danger">- ₹{{ number_format($salary->deductions, 2) }}</strong></td>
                                    </tr>
                                    <tr class="table-success">
                                        <td><strong>Net Salary</strong></td>
                                        <td class="text-right"><strong class="text-success">₹{{ number_format($salary->net_salary, 2) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($salary->attendance_notes)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Attendance Notes</h5>
                            <div class="alert alert-info">
                                {{ $salary->attendance_notes }}
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($salary->remarks)
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5>Remarks</h5>
                            <div class="alert alert-warning">
                                {{ $salary->remarks }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <a href="{{ route('admin.salary.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                            @if($salary->salary_slip_path)
                            <a href="{{ asset('storage/' . $salary->salary_slip_path) }}" class="btn btn-primary" target="_blank">
                                <i class="fa fa-download"></i> Download Salary Slip
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
