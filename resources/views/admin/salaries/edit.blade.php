@extends('admin.layouts.app')

@section('title', 'Edit Salary Record')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.salaries.index') }}">Salaries</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.salaries.show', $salary->id) }}">{{ $salary->month_year }}</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Salary Record</h4>
                <a href="{{ route('admin.salaries.show', $salary->id) }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-2"></i>Back to Details
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

                <form action="{{ route('admin.salaries.update', $salary->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Employee</label>
                                <input type="text" class="form-control" value="{{ $salary->bdm->name }} ({{ $salary->bdm->employee_code }})" disabled>
                                <small class="text-muted">Employee cannot be changed</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Month/Year <span class="text-danger">*</span></label>
                                <input type="month" name="month_year" class="form-control" value="{{ old('month_year', $salary->month_year) }}" required>
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
                                <input type="number" name="basic_salary" id="basic_salary" class="form-control" value="{{ old('basic_salary', $salary->basic_salary) }}" min="0" step="0.01" required>
                                @error('basic_salary')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">HRA (₹)</label>
                                <input type="number" name="hra" id="hra" class="form-control" value="{{ old('hra', $salary->hra) }}" min="0" step="0.01">
                                @error('hra')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Other Allowances (₹)</label>
                                <input type="number" name="other_allowances" id="other_allowances" class="form-control" value="{{ old('other_allowances', $salary->other_allowances) }}" min="0" step="0.01">
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
                                <input type="number" name="deductions" id="deductions" class="form-control" value="{{ old('deductions', $salary->deductions) }}" min="0" step="0.01">
                                @error('deductions')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Net Salary (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="net_salary" id="net_salary" class="form-control" value="{{ old('net_salary', $salary->net_salary) }}" min="0" step="0.01" required readonly>
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
                                @if($salary->salary_slip_path)
                                    <div class="mb-2">
                                        <a href="{{ route('admin.salaries.download', $salary->id) }}" class="btn btn-sm btn-success">
                                            <i class="fa fa-download"></i> Current Slip
                                        </a>
                                    </div>
                                @endif
                                <input type="file" name="salary_slip" class="form-control" accept=".pdf">
                                <small class="text-muted">Upload new salary slip to replace existing (Max: 2MB)</small>
                                @error('salary_slip')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Remarks</label>
                                <textarea name="remarks" class="form-control" rows="3" placeholder="Add any remarks...">{{ old('remarks', $salary->remarks) }}</textarea>
                                @error('remarks')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fa fa-save me-2"></i>Update Salary Record
                        </button>
                        <a href="{{ route('admin.salaries.show', $salary->id) }}" class="btn btn-secondary">
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
