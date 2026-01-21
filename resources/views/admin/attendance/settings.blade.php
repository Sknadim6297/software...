@extends('admin.layouts.app')

@section('title', 'Attendance Settings')

@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col p-md-0">
            <h4>Attendance Settings</h4>
        </div>
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.attendance.dashboard') }}">Attendance</a></li>
                <li class="breadcrumb-item active">Settings</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Attendance Rules Configuration</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.attendance.update-settings') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="check_in_deadline" class="font-weight-bold">Check-In Deadline <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('check_in_deadline') is-invalid @enderror" id="check_in_deadline" name="check_in_deadline" value="{{ old('check_in_deadline', substr($rules->check_in_deadline ?? '10:45', 0, 5)) }}" required>
                            @error('check_in_deadline')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Latest time employees can check-in without being marked late</small>
                        </div>

                        <div class="form-group">
                            <label for="check_out_time" class="font-weight-bold">Check-Out Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control @error('check_out_time') is-invalid @enderror" id="check_out_time" name="check_out_time" value="{{ old('check_out_time', substr($rules->check_out_time ?? '20:30', 0, 5)) }}" required>
                            @error('check_out_time')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Time after which employees cannot check-out (requires admin unlock)</small>
                        </div>

                        <div class="form-group">
                            <label for="late_marks_for_warning" class="font-weight-bold">Late Marks for Warning <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('late_marks_for_warning') is-invalid @enderror" id="late_marks_for_warning" name="late_marks_for_warning" value="{{ old('late_marks_for_warning', $rules->late_marks_for_warning ?? 3) }}" min="1" required>
                            @error('late_marks_for_warning')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Number of late marks before automatic warning email is sent</small>
                        </div>

                        <div class="form-group">
                            <label for="late_marks_for_half_day" class="font-weight-bold">Late Marks for Half-Day <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('late_marks_for_half_day') is-invalid @enderror" id="late_marks_for_half_day" name="late_marks_for_half_day" value="{{ old('late_marks_for_half_day', $rules->late_marks_for_half_day ?? 4) }}" min="1" required>
                            @error('late_marks_for_half_day')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Number of late marks before automatic half-day assignment</small>
                        </div>

                        <hr>
                        <h5>Additional Settings</h5>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="block_mobile_login_on_late" name="block_mobile_login_on_late" value="1" {{ old('block_mobile_login_on_late', $rules->block_mobile_login_on_late ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="block_mobile_login_on_late">
                                Block mobile login on excessive late marks
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="auto_assign_half_day" name="auto_assign_half_day" value="1" {{ old('auto_assign_half_day', $rules->auto_assign_half_day ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="auto_assign_half_day">
                                Automatically assign half-day on 4th late mark
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="enable_leave_balance" name="enable_leave_balance" value="1" {{ old('enable_leave_balance', $rules->enable_leave_balance ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="enable_leave_balance">
                                Enable leave balance tracking
                            </label>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-save"></i> Save Settings
                            </button>
                            <a href="{{ route('admin.attendance.dashboard') }}" class="btn btn-secondary btn-lg">
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
