@extends('layouts.app')

@section('title', 'Salary Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-file-invoice-dollar"></i>
                        Salary - {{ \Carbon\Carbon::createFromDate($salary->year, $salary->month, 1)->format('F Y') }}
                    </h4>
                    <a href="{{ route('salary.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Earnings -->
                        <div class="col-md-6">
                            <div class="card border-0 bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title text-success mb-3">
                                        <i class="fas fa-arrow-up"></i> Earnings
                                    </h5>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Base Salary:</small>
                                        </div>
                                        <div class="col-6 text-right">
                                            <strong>₹{{ number_format($salary->base_salary, 2) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deductions -->
                        <div class="col-md-6">
                            <div class="card border-0 bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title text-danger mb-3">
                                        <i class="fas fa-arrow-down"></i> Deductions
                                    </h5>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Late Penalty:</small>
                                        </div>
                                        <div class="col-6 text-right">
                                            <strong>₹{{ number_format($salary->late_deduction, 2) }}</strong>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Half-Day Deduction:</small>
                                        </div>
                                        <div class="col-6 text-right">
                                            <strong>₹{{ number_format($salary->half_day_deduction, 2) }}</strong>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Absent Deduction:</small>
                                        </div>
                                        <div class="col-6 text-right">
                                            <strong>₹{{ number_format($salary->absent_deduction, 2) }}</strong>
                                        </div>
                                    </div>
                                    @if($salary->other_deductions > 0)
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Other Deductions:</small>
                                        </div>
                                        <div class="col-6 text-right">
                                            <strong>₹{{ number_format($salary->other_deductions, 2) }}</strong>
                                        </div>
                                    </div>
                                    @endif
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted"><strong>Total Deductions:</strong></small>
                                        </div>
                                        <div class="col-6 text-right">
                                            <strong class="text-danger">₹{{ number_format($salary->total_deductions, 2) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Summary -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card border-0 bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">
                                        <i class="fas fa-chart-bar"></i> Attendance Summary
                                    </h5>
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <h6 class="text-muted">Present Days</h6>
                                            <p class="h4 text-success mb-0">{{ $salary->present_days }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <h6 class="text-muted">Absent Days</h6>
                                            <p class="h4 text-danger mb-0">{{ $salary->absent_days }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <h6 class="text-muted">Half Days</h6>
                                            <p class="h4 text-warning mb-0">{{ $salary->half_days }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <h6 class="text-muted">Late Count</h6>
                                            <p class="h4 text-info mb-0">{{ $salary->late_count }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Net Salary -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Net Salary</h5>
                                    <p class="h2 mb-0">₹{{ number_format($salary->net_salary, 2) }}</p>
                                    <small>Gross Salary: ₹{{ number_format($salary->gross_salary, 2) }}</small>
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
