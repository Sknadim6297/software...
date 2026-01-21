@extends('layouts.app')

@section('title', 'Current Month Salary Preview')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-calculator"></i>
                        Current Month Salary Preview ({{ now()->format('F Y') }})
                    </h4>
                    <a href="{{ route('salary.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($message))
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> {{ $message }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> This is a preview based on your current month's attendance. Final salary will be processed by admin at month end.
                        </div>

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
                                            <i class="fas fa-arrow-down"></i> Deductions (Estimated)
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
                                                <small class="text-muted">Half-Day:</small>
                                            </div>
                                            <div class="col-6 text-right">
                                                <strong>₹{{ number_format($salary->half_day_deduction, 2) }}</strong>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted">Absent:</small>
                                            </div>
                                            <div class="col-6 text-right">
                                                <strong>₹{{ number_format($salary->absent_deduction, 2) }}</strong>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-6">
                                                <small class="text-muted"><strong>Total:</strong></small>
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
                                            <i class="fas fa-chart-bar"></i> Attendance Summary (Till Date)
                                        </h5>
                                        <div class="row text-center">
                                            <div class="col-md-3">
                                                <h6 class="text-muted">Present</h6>
                                                <p class="h4 text-success mb-0">{{ $salary->present_days }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <h6 class="text-muted">Absent</h6>
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
                                    <div class="card-body text-center">
                                        <h5 class="card-title mb-3">Estimated Net Salary</h5>
                                        <p class="h1 mb-0">₹{{ number_format($salary->net_salary, 2) }}</p>
                                        <small>Subject to final approval and processing</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
