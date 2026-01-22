@extends('admin.layouts.app')

@section('title', 'Add New Employee')

@section('content')
<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.employees.index') }}">Employees</a></li>
        <li class="breadcrumb-item active"><a href="javascript:void(0)">Add New Employee</a></li>
    </ol>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create New Employee Profile</h4>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-2"></i>Back to Employees
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

                <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <h5 class="mb-3">Personal Information</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Enter full name" value="{{ old('name') }}" required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Father's Name <span class="text-danger">*</span></label>
                                <input type="text" name="father_name" class="form-control" placeholder="Enter father's name" value="{{ old('father_name') }}" required>
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
                                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" required>
                                @error('date_of_birth')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Highest Education <span class="text-danger">*</span></label>
                                <input type="text" name="highest_education" class="form-control" placeholder="e.g., B.Tech, MBA" value="{{ old('highest_education') }}" required>
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
                                <div class="mb-2" id="imagePreview" style="display: none;">
                                    <img id="preview" src="" width="100" height="100" style="object-fit: cover;" class="rounded" alt="Preview">
                                    <p class="text-muted mb-0">Image Preview</p>
                                </div>
                                <input type="file" name="profile_image" class="form-control" accept="image/jpeg,image/png,image/jpg" id="profileImageInput" onchange="previewImage(event)">
                                <small class="text-muted">Max size: 2MB. Format: JPG, PNG</small>
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
                                <input type="email" name="email" class="form-control" placeholder="Enter email address" value="{{ old('email') }}" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone" class="form-control" placeholder="Enter phone number" value="{{ old('phone') }}" required>
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
                                <label class="form-label">Designation <span class="text-danger">*</span></label>
                                <select name="designation" class="form-control" required>
                                    <option value="">-- Select Designation --</option>
                                    <option value="BDM" {{ old('designation') == 'BDM' ? 'selected' : '' }}>BDM (Business Development Manager)</option>
                                    <option value="Manager" {{ old('designation') == 'Manager' ? 'selected' : '' }}>Manager</option>
                                    <option value="Team Lead" {{ old('designation') == 'Team Lead' ? 'selected' : '' }}>Team Lead</option>
                                    <option value="Senior Executive" {{ old('designation') == 'Senior Executive' ? 'selected' : '' }}>Senior Executive</option>
                                    <option value="Executive" {{ old('designation') == 'Executive' ? 'selected' : '' }}>Executive</option>
                                    <option value="HR" {{ old('designation') == 'HR' ? 'selected' : '' }}>HR</option>
                                    <option value="Accountant" {{ old('designation') == 'Accountant' ? 'selected' : '' }}>Accountant</option>
                                    <option value="Sales Executive" {{ old('designation') == 'Sales Executive' ? 'selected' : '' }}>Sales Executive</option>
                                    <option value="Marketing Executive" {{ old('designation') == 'Marketing Executive' ? 'selected' : '' }}>Marketing Executive</option>
                                    <option value="Other" {{ old('designation') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('designation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Joining Date <span class="text-danger">*</span></label>
                                <input type="date" name="joining_date" class="form-control" value="{{ old('joining_date') }}" required>
                                @error('joining_date')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label class="form-label">Current CTC (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="current_ctc" class="form-control" placeholder="Enter CTC amount" value="{{ old('current_ctc') }}" min="0" step="0.01" required>
                                @error('current_ctc')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h5 class="mb-3 mt-4">Login Credentials</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                                <small class="text-muted">Minimum 8 characters</small>
                                @error('password')
                                    <small class="text-danger d-block">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fa fa-save me-2"></i>Create Employee Profile
                        </button>
                        <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
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
    const imagePreview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            imagePreview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
