@extends('layouts.app')

@section('title', 'Apply for Leave')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-calendar-plus"></i> Apply for Leave</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('leaves.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="from_date" class="font-weight-bold">From Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('from_date') is-invalid @enderror" id="from_date" name="from_date" required value="{{ old('from_date') }}">
                            @error('from_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="to_date" class="font-weight-bold">To Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('to_date') is-invalid @enderror" id="to_date" name="to_date" required value="{{ old('to_date') }}">
                            @error('to_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="leave_type" class="font-weight-bold">Leave Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('leave_type') is-invalid @enderror" id="leave_type" name="leave_type" required>
                                <option value="">-- Select Leave Type --</option>
                                <option value="full_day" {{ old('leave_type') === 'full_day' ? 'selected' : '' }}>Full Day</option>
                                <option value="half_day" {{ old('leave_type') === 'half_day' ? 'selected' : '' }}>Half Day</option>
                            </select>
                            @error('leave_type')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="reason" class="font-weight-bold">Reason <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="4" placeholder="Please provide a reason for your leave" required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Minimum 10 characters required</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-paper-plane"></i> Submit Leave Request
                            </button>
                            <a href="{{ route('leaves.index') }}" class="btn btn-secondary btn-block mt-2">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('from_date').addEventListener('change', function() {
    const fromDate = new Date(this.value);
    const toDateInput = document.getElementById('to_date');
    
    if (!toDateInput.value || new Date(toDateInput.value) < fromDate) {
        toDateInput.value = this.value;
    }
    toDateInput.min = this.value;
});
</script>
@endpush
@endsection
