@extends('admin.layouts.app')

@section('title', 'Add Salary Record')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.salaries.index') }}">Salaries</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Add Salary</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Add New Salary Record</h4>
                <a href="{{ route('admin.salaries.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-2"></i>Back to Salaries
                </a>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Validation Errors:</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('admin.salaries.store') }}" method="POST" enctype="multipart/form-data" id="salaryForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Select Employee <span class="text-danger">*</span></label>
                                <select name="bdm_id" class="form-control" required>
                                    <option value="">-- Select Employee --</option>
                                    @foreach($bdms as $bdm)
                                        <option value="{{ $bdm->id }}" {{ old('bdm_id') == $bdm->id ? 'selected' : '' }}>
                                            {{ $bdm->name }} ({{ $bdm->employee_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('bdm_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Month/Year <span class="text-danger">*</span></label>
                                <input type="month" name="month_year" class="form-control" value="{{ old('month_year') }}" required>
                                @error('month_year')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3 mt-3">Salary Components</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Basic Salary (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="basic_salary" id="basic_salary" class="form-control" placeholder="Enter basic salary" value="{{ old('basic_salary') }}" min="0" step="0.01" required>
                                @error('basic_salary')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">HRA (₹)</label>
                                <input type="number" name="hra" id="hra" class="form-control" placeholder="Enter HRA" value="{{ old('hra', 0) }}" min="0" step="0.01">
                                @error('hra')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Other Allowances (₹)</label>
                                <input type="number" name="other_allowances" id="other_allowances" class="form-control" placeholder="Enter other allowances" value="{{ old('other_allowances', 0) }}" min="0" step="0.01">
                                @error('other_allowances')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Deductions (₹)</label>
                                <input type="number" name="deductions" id="deductions" class="form-control" placeholder="Enter deductions" value="{{ old('deductions', 0) }}" min="0" step="0.01">
                                @error('deductions')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Net Salary (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="net_salary" id="net_salary" class="form-control" placeholder="Net salary will be calculated" value="{{ old('net_salary') }}" min="0" step="0.01" required readonly>
                                @error('net_salary')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Salary Slip (PDF)</label>
                                <input type="file" name="salary_slip" class="form-control" accept=".pdf">
                                <small class="text-muted">Upload salary slip in PDF format (Max: 2MB)</small>
                                @error('salary_slip')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Remarks</label>
                                <textarea name="remarks" class="form-control" rows="3" placeholder="Add any remarks...">{{ old('remarks') }}</textarea>
                                @error('remarks')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fa fa-save me-2"></i>Create Salary Record
                        </button>
                        <a href="{{ route('admin.salaries.index') }}" class="btn btn-secondary">
                            <i class="fa fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const basicSalary = document.getElementById('basic_salary');
    const hra = document.getElementById('hra');
    const otherAllowances = document.getElementById('other_allowances');
    const deductions = document.getElementById('deductions');
    const netSalary = document.getElementById('net_salary');

    function calculateNetSalary() {
        const basic = parseFloat(basicSalary.value) || 0;
        const hraAmount = parseFloat(hra.value) || 0;
        const allowances = parseFloat(otherAllowances.value) || 0;
        const deduct = parseFloat(deductions.value) || 0;

        const gross = basic + hraAmount + allowances;
        const net = gross - deduct;

        netSalary.value = net.toFixed(2);
    }

    basicSalary.addEventListener('input', calculateNetSalary);
    hra.addEventListener('input', calculateNetSalary);
    otherAllowances.addEventListener('input', calculateNetSalary);
    deductions.addEventListener('input', calculateNetSalary);
});
</script>
@endpush
@endsection
