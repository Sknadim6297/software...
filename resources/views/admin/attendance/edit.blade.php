@extends('admin.layouts.app')

@section('title', 'Edit Attendance')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col p-md-0">
            <h4>Edit Attendance</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Attendance Record for {{ $attendance->user->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.attendance.update', $attendance) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="attendance_date" class="form-label font-weight-bold">Attendance Date</label>
                            <input type="text" class="form-control" id="attendance_date" value="{{ $attendance->attendance_date->format('Y-m-d') }}" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label for="check_in_time" class="form-label font-weight-bold">Check-In Time</label>
                            <input type="time" class="form-control @error('check_in_time') is-invalid @enderror" id="check_in_time" name="check_in_time" value="{{ old('check_in_time', $attendance->check_in_time) }}">
                            @error('check_in_time')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="check_out_time" class="form-label font-weight-bold">Check-Out Time</label>
                            <input type="time" class="form-control @error('check_out_time') is-invalid @enderror" id="check_out_time" name="check_out_time" value="{{ old('check_out_time', $attendance->check_out_time) }}">
                            @error('check_out_time')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="status" class="form-label font-weight-bold">Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="present" {{ old('status', $attendance->status) == 'present' ? 'selected' : '' }}>Present</option>
                                <option value="absent" {{ old('status', $attendance->status) == 'absent' ? 'selected' : '' }}>Absent</option>
                                <option value="half_day" {{ old('status', $attendance->status) == 'half_day' ? 'selected' : '' }}>Half Day</option>
                                <option value="leave" {{ old('status', $attendance->status) == 'leave' ? 'selected' : '' }}>Leave</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="notes" class="form-label font-weight-bold">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $attendance->notes) }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
                                <i class="fa fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
