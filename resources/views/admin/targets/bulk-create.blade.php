@extends('admin.layouts.app')

@section('title', 'Bulk Create Targets')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.targets.index') }}">Targets</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Bulk Create</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Bulk Create Targets for Multiple BDMs</h4>
                <a href="{{ route('admin.targets.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-2"></i>Back to Targets
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

                <div class="alert alert-info">
                    <i class="fa fa-info-circle me-2"></i>
                    This form allows you to create the same target for multiple BDMs at once. Select the BDMs and set common target parameters.
                </div>

                <form action="{{ route('admin.targets.bulk-store') }}" method="POST">
                    @csrf
                    
                    <h5 class="mb-3">Select BDMs</h5>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="mb-2">
                                    <button type="button" class="btn btn-sm btn-primary me-2" onclick="selectAll()">Select All</button>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="deselectAll()">Deselect All</button>
                                </div>
                                <div class="row">
                                    @foreach($bdms as $bdm)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input bdm-checkbox" type="checkbox" name="bdm_ids[]" value="{{ $bdm->id }}" id="bdm{{ $bdm->id }}" {{ in_array($bdm->id, old('bdm_ids', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="bdm{{ $bdm->id }}">
                                                    {{ $bdm->name }} ({{ $bdm->employee_code }})
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('bdm_ids')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3">Target Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Target Type <span class="text-danger">*</span></label>
                                <select name="target_type" class="form-control" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="monthly" {{ old('target_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                    <option value="quarterly" {{ old('target_type') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                    <option value="annual" {{ old('target_type') == 'annual' ? 'selected' : '' }}>Annual</option>
                                </select>
                                @error('target_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Period <span class="text-danger">*</span></label>
                                <input type="text" name="period" class="form-control" placeholder="e.g., Jan 2024, Q1 2024, FY 2024" value="{{ old('period') }}" required>
                                @error('period')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Revenue Target (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="revenue_target" class="form-control" placeholder="Enter revenue target" value="{{ old('revenue_target') }}" min="0" step="0.01" required>
                                @error('revenue_target')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Project Target <span class="text-danger">*</span></label>
                                <input type="number" name="project_target" class="form-control" placeholder="Number of projects" value="{{ old('project_target') }}" min="0" required>
                                @error('project_target')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                                @error('start_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                                @error('end_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fa fa-save me-2"></i>Create Targets for Selected BDMs
                        </button>
                        <a href="{{ route('admin.targets.index') }}" class="btn btn-secondary">
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
function selectAll() {
    document.querySelectorAll('.bdm-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAll() {
    document.querySelectorAll('.bdm-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
}
</script>
@endpush
@endsection
