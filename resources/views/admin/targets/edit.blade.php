@extends('admin.layouts.app')

@section('title', 'Edit Target')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.targets.index') }}">Targets</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.targets.show', $target->id) }}">{{ $target->period }}</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Target</h4>
                <a href="{{ route('admin.targets.show', $target->id) }}" class="btn btn-secondary btn-sm">
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

                <form action="{{ route('admin.targets.update', $target->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Employee</label>
                                <input type="text" class="form-control" value="{{ $target->bdm->name }} ({{ $target->bdm->employee_code }})" disabled>
                                <small class="text-muted">Employee cannot be changed</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Target Type <span class="text-danger">*</span></label>
                                <select name="target_type" class="form-control" required>
                                    <option value="monthly" {{ old('target_type', $target->target_type) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="quarterly" {{ old('target_type', $target->target_type) == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="annual" {{ old('target_type', $target->target_type) == 'annual' ? 'selected' : '' }}>Annual</option>
                                </select>
                                @error('target_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Period <span class="text-danger">*</span></label>
                                <input type="text" name="period" class="form-control" placeholder="e.g., Jan 2024, Q1 2024, FY 2024" value="{{ old('period', $target->period) }}" required>
                                @error('period')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Revenue Target (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="revenue_target" class="form-control" value="{{ old('revenue_target', $target->revenue_target) }}" min="0" step="0.01" required>
                                @error('revenue_target')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Project Target <span class="text-danger">*</span></label>
                                <input type="number" name="project_target" class="form-control" value="{{ old('project_target', $target->project_target) }}" min="0" required>
                                @error('project_target')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $target->start_date) }}" required>
                                @error('start_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $target->end_date) }}" required>
                                @error('end_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3 mt-3">Achievement (Optional)</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Revenue Achieved (₹)</label>
                                <input type="number" name="revenue_achieved" class="form-control" value="{{ old('revenue_achieved', $target->revenue_achieved) }}" min="0" step="0.01">
                                @error('revenue_achieved')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Projects Achieved</label>
                                <input type="number" name="projects_achieved" class="form-control" value="{{ old('projects_achieved', $target->projects_achieved) }}" min="0">
                                @error('projects_achieved')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fa fa-save me-2"></i>Update Target
                        </button>
                        <a href="{{ route('admin.targets.show', $target->id) }}" class="btn btn-secondary">
                            <i class="fa fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
