@extends('layouts.app')

@section('title', 'Apply for Leave')
@section('page-title', 'Apply for Leave')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Submit Leave Application</h4>
                </div>
                <div class="card-body">
                    <!-- Leave Balance Info -->
                    <div class="alert alert-info">
                        <h5><i class="fa fa-info-circle"></i> Your Leave Balance</h5>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <strong>Casual Leave (CL):</strong> {{ $leaveBalance->casual_leave_balance ?? 0 }} days remaining
                            </div>
                            <div class="col-md-6">
                                <strong>Sick Leave (SL):</strong> {{ $leaveBalance->sick_leave_balance ?? 0 }} days remaining
                            </div>
                        </div>
                    </div>

                    <!-- Leave Application Form -->
                    <form action="{{ route('bdm.leave-salary.apply') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                                <select name="leave_type" class="form-control @error('leave_type') is-invalid @enderror" required>
                                    <option value="">-- Select Leave Type --</option>
                                    <option value="casual" {{ old('leave_type') == 'casual' ? 'selected' : '' }}>
                                        Casual Leave (CL) - {{ $leaveBalance->casual_leave_balance ?? 0 }} available
                                    </option>
                                    <option value="sick" {{ old('leave_type') == 'sick' ? 'selected' : '' }}>
                                        Sick Leave (SL) - {{ $leaveBalance->sick_leave_balance ?? 0 }} available
                                    </option>
                                    <option value="unpaid" {{ old('leave_type') == 'unpaid' ? 'selected' : '' }}>
                                        Unpaid Leave
                                    </option>
                                </select>
                                @error('leave_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">From Date <span class="text-danger">*</span></label>
                                <input 
                                    type="date" 
                                    name="from_date" 
                                    class="form-control @error('from_date') is-invalid @enderror" 
                                    value="{{ old('from_date') }}"
                                    min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                                    required
                                >
                                @error('from_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave must be applied for tomorrow or later</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">To Date <span class="text-danger">*</span></label>
                                <input 
                                    type="date" 
                                    name="to_date" 
                                    class="form-control @error('to_date') is-invalid @enderror" 
                                    value="{{ old('to_date') }}"
                                    min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                                    required
                                >
                                @error('to_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Reason <span class="text-danger">*</span></label>
                                <textarea 
                                    name="reason" 
                                    class="form-control @error('reason') is-invalid @enderror" 
                                    rows="4" 
                                    placeholder="Please provide a detailed reason for your leave application (minimum 10 characters)"
                                    required
                                    minlength="10"
                                    maxlength="500"
                                >{{ old('reason') }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimum 10 characters required</small>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <h6><i class="fa fa-exclamation-triangle"></i> Important Rules:</h6>
                            <ul class="mb-0">
                                <li>Leave applications must be submitted at least 1 day in advance</li>
                                <li>Once submitted, leave applications cannot be edited</li>
                                <li>Only admin/HR can approve or reject your leave request</li>
                                <li>Approved leaves will automatically reflect in your monthly salary slip</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('bdm.leave-salary.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-paper-plane"></i> Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Automatically set to_date when from_date is selected
    document.querySelector('input[name="from_date"]').addEventListener('change', function() {
        const toDateInput = document.querySelector('input[name="to_date"]');
        if (!toDateInput.value) {
            toDateInput.value = this.value;
        }
        toDateInput.min = this.value;
    });
</script>
@endsection
