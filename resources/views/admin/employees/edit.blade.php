@extends('admin.layouts.app')

@section('title', 'Edit Employee - ' . $employee->name)

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">Employees</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.employees.show', $employee->id) }}">{{ $employee->name }}</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Employee Profile - {{ $employee->name }}</h4>
                <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-2"></i>Back to Profile
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

                <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <h5 class="mb-3">Personal Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Enter full name" value="{{ old('name', $employee->name) }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Father's Name <span class="text-danger">*</span></label>
                                <input type="text" name="father_name" class="form-control" placeholder="Enter father's name" value="{{ old('father_name', $employee->father_name) }}" required>
                                @error('father_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $employee->date_of_birth ? $employee->date_of_birth->format('Y-m-d') : '') }}" required>
                                @error('date_of_birth')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Highest Education <span class="text-danger">*</span></label>
                                <input type="text" name="highest_education" class="form-control" placeholder="e.g., B.Tech, MBA" value="{{ old('highest_education', $employee->highest_education) }}" required>
                                @error('highest_education')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Profile Image</label>
                                <div class="mb-2" id="imagePreview">
                                    <img id="preview" src="{{ $employee->profile_image ? asset('storage/' . $employee->profile_image) : '' }}" width="100" height="100" style="object-fit: cover; {{ $employee->profile_image ? '' : 'display: none;' }}" class="rounded" alt="Current">
                                    <p class="text-muted mb-0" id="imageLabel" style="{{ $employee->profile_image ? '' : 'display: none;' }}">{{ $employee->profile_image ? 'Current Image' : 'Image Preview' }}</p>
                                </div>
                                <input type="file" name="profile_image" class="form-control" accept="image/jpeg,image/png,image/jpg" id="profileImageInput" onchange="previewImage(event)">
                                <small class="text-muted">Max size: 2MB. Format: JPG, PNG. Leave empty to keep current image.</small>
                                @error('profile_image')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3 mt-4">Contact Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="Enter email address" value="{{ old('email', $employee->email) }}" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" placeholder="Enter phone number" value="{{ old('phone', $employee->phone) }}" required>
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3 mt-4">Employment Details</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Employee Code</label>
                                <input type="text" class="form-control" value="{{ $employee->employee_code }}" disabled>
                                <small class="text-muted">Employee code cannot be changed</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Designation <span class="text-danger">*</span></label>
                                <select name="designation" class="form-control" required>
                                    <option value="">-- Select Designation --</option>
                                    <option value="BDM" {{ old('designation', $employee->designation ?? 'BDM') == 'BDM' ? 'selected' : '' }}>BDM (Business Development Manager)</option>
                                    <option value="Manager" {{ old('designation', $employee->designation) == 'Manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="Team Lead" {{ old('designation', $employee->designation) == 'Team Lead' ? 'selected' : '' }}>Team Lead</option>
                                    <option value="Senior Executive" {{ old('designation', $employee->designation) == 'Senior Executive' ? 'selected' : '' }}>Senior Executive</option>
                                    <option value="Executive" {{ old('designation', $employee->designation) == 'Executive' ? 'selected' : '' }}>Executive</option>
                                    <option value="HR" {{ old('designation', $employee->designation) == 'HR' ? 'selected' : '' }}>HR</option>
                                    <option value="Accountant" {{ old('designation', $employee->designation) == 'Accountant' ? 'selected' : '' }}>Accountant</option>
                                    <option value="Sales Executive" {{ old('designation', $employee->designation) == 'Sales Executive' ? 'selected' : '' }}>Sales Executive</option>
                                    <option value="Marketing Executive" {{ old('designation', $employee->designation) == 'Marketing Executive' ? 'selected' : '' }}>Marketing Executive</option>
                                    <option value="Other" {{ old('designation', $employee->designation) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('designation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Joining Date</label>
                                <input type="date" class="form-control" value="{{ $employee->joining_date->format('Y-m-d') }}" disabled>
                                <small class="text-muted">Joining date cannot be changed</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Current CTC (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="current_ctc" class="form-control" placeholder="Enter CTC amount" value="{{ old('current_ctc', $employee->current_ctc) }}" min="0" step="0.01" required>
                                @error('current_ctc')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="terminated" {{ old('status', $employee->status) == 'terminated' ? 'selected' : '' }}>Terminated</option>
                                </select>
                                @error('status')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fa fa-save me-2"></i>Update Employee Profile
                        </button>
                        <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-secondary">
                            <i class="fa fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const imageLabel = document.getElementById('imageLabel');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            imageLabel.style.display = 'block';
            imageLabel.textContent = 'New Image Preview';
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
