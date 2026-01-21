@extends('admin.layouts.app')

@section('title', 'Salary Settings')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col p-md-0">
            <h4>Salary Settings - {{ $user->name }}</h4>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.salary.index') }}">Salary</a></li>
                <li class="breadcrumb-item active">Settings</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Configure Salary for {{ $user->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.salary.update-settings', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="base_salary" class="font-weight-bold">Base Salary (Monthly) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₹</span>
                                </div>
                                <input type="number" step="0.01" class="form-control @error('base_salary') is-invalid @enderror" id="base_salary" name="base_salary" value="{{ old('base_salary', $settings->base_salary ?? 0) }}" required>
                            </div>
                            @error('base_salary')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="late_penalty_per_mark" class="font-weight-bold">Late Penalty (Per Mark) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₹</span>
                                </div>
                                <input type="number" step="0.01" class="form-control @error('late_penalty_per_mark') is-invalid @enderror" id="late_penalty_per_mark" name="late_penalty_per_mark" value="{{ old('late_penalty_per_mark', $settings->late_penalty_per_mark ?? 0) }}" required>
                            </div>
                            @error('late_penalty_per_mark')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Amount deducted for each late mark</small>
                        </div>

                        <div class="form-group">
                            <label for="half_day_deduction_percentage" class="font-weight-bold">Half-Day Deduction (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('half_day_deduction_percentage') is-invalid @enderror" id="half_day_deduction_percentage" name="half_day_deduction_percentage" value="{{ old('half_day_deduction_percentage', $settings->half_day_deduction_percentage ?? 50) }}" min="0" max="100" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            @error('half_day_deduction_percentage')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Percentage of daily rate deducted for half-day</small>
                        </div>

                        <div class="form-group">
                            <label for="absent_deduction_percentage" class="font-weight-bold">Absent Day Deduction (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control @error('absent_deduction_percentage') is-invalid @enderror" id="absent_deduction_percentage" name="absent_deduction_percentage" value="{{ old('absent_deduction_percentage', $settings->absent_deduction_percentage ?? 100) }}" min="0" max="100" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            @error('absent_deduction_percentage')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Percentage of daily rate deducted for absent day</small>
                        </div>

                        <hr>

                        <div class="alert alert-info">
                            <strong>Calculation Example:</strong><br>
                            Base Salary: ₹{{ number_format($settings->base_salary ?? 30000, 2) }}<br>
                            Daily Rate: ₹{{ number_format(($settings->base_salary ?? 30000) / 30, 2) }}<br>
                            Half-Day Deduction: ₹{{ number_format((($settings->base_salary ?? 30000) / 30) * (($settings->half_day_deduction_percentage ?? 50) / 100), 2) }}
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-save"></i> Save Settings
                            </button>
                            <a href="{{ route('admin.salary.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
