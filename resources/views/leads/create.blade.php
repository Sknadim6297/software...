@extends('layouts.app')

@section('title', 'Add New Lead - Konnectix BDM')

@section('page-title', 'Add New Lead')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Lead Information</h4>
                <a href="{{ route('leads.incoming') }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-2"></i> Back to Leads
                </a>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('leads.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                       id="customer_name" name="customer_name" value="{{ old('customer_name') }}" 
                                       placeholder="Enter customer full name" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" 
                                       placeholder="customer@example.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                       id="phone_number" name="phone_number" value="{{ old('phone_number') }}" 
                                       placeholder="+91 9876543210" maxlength="15" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="platform" class="form-label">Platform/Source <span class="text-danger">*</span></label>
                                <select class="form-control @error('platform') is-invalid @enderror" id="platform" name="platform" required>
                                    <option value="">Select Platform</option>
                                    <option value="website" {{ old('platform') == 'website' ? 'selected' : '' }}>Website</option>
                                    <option value="facebook" {{ old('platform') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                    <option value="instagram" {{ old('platform') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                    <option value="linkedin" {{ old('platform') == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                                    <option value="referral" {{ old('platform') == 'referral' ? 'selected' : '' }}>Referral</option>
                                    <option value="cold_call" {{ old('platform') == 'cold_call' ? 'selected' : '' }}>Cold Call</option>
                                    <option value="email" {{ old('platform') == 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="other" {{ old('platform') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('platform')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="project_type" class="form-label">Project Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('project_type') is-invalid @enderror" id="project_type" name="project_type" required>
                                    <option value="">Select Project Type</option>
                                    <option value="web_development" {{ old('project_type') == 'web_development' ? 'selected' : '' }}>Web Development</option>
                                    <option value="mobile_app" {{ old('project_type') == 'mobile_app' ? 'selected' : '' }}>Mobile App</option>
                                    <option value="ecommerce" {{ old('project_type') == 'ecommerce' ? 'selected' : '' }}>E-commerce</option>
                                    <option value="software_development" {{ old('project_type') == 'software_development' ? 'selected' : '' }}>Software Development</option>
                                    <option value="ui_ux_design" {{ old('project_type') == 'ui_ux_design' ? 'selected' : '' }}>UI/UX Design</option>
                                    <option value="digital_marketing" {{ old('project_type') == 'digital_marketing' ? 'selected' : '' }}>Digital Marketing</option>
                                    <option value="consultation" {{ old('project_type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                    <option value="other" {{ old('project_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('project_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="project_valuation" class="form-label">Project Valuation (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control @error('project_valuation') is-invalid @enderror" 
                                           id="project_valuation" name="project_valuation" value="{{ old('project_valuation') }}" 
                                           placeholder="50000" min="0" step="1000">
                                </div>
                                @error('project_valuation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="assigned_to" class="form-label">Assign To</label>
                                <select class="form-control @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
                                    <option value="">Select BDM</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ (old('assigned_to') == $user->id || (!old('assigned_to') && $user->id == auth()->id())) ? 'selected' : '' }}>
                                            {{ $user->name }} {{ $user->id == auth()->id() ? '(Me)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Initial Status</label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="contacted" {{ old('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="qualified" {{ old('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="remarks" class="form-label">Remarks/Notes</label>
                                <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                          id="remarks" name="remarks" rows="3"
                                          placeholder="Enter any additional notes or remarks about this lead...">{{ old('remarks') }}</textarea>
                                @error('remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group text-end">
                        <a href="{{ route('leads.incoming') }}" class="btn btn-secondary me-2">
                            <i class="fa fa-times me-2"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i> Save Lead
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-format phone number
    const phoneField = document.getElementById('phone_number');
    if (phoneField) {
        phoneField.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            if (value.length === 10 && !e.target.value.includes('+91')) {
                e.target.value = '+91 ' + value;
            }
        });
    }

    // Auto-format project valuation
    const valuationField = document.getElementById('project_valuation');
    if (valuationField) {
        valuationField.addEventListener('input', function(e) {
            let value = parseInt(e.target.value);
            if (value && value < 1000) {
                e.target.step = '100';
            } else if (value && value >= 1000) {
                e.target.step = '1000';
            }
        });
    }

    // Email validation
    const emailField = document.getElementById('email');
    if (emailField) {
        emailField.addEventListener('blur', function(e) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (e.target.value && !emailPattern.test(e.target.value)) {
                e.target.classList.add('is-invalid');
            } else {
                e.target.classList.remove('is-invalid');
            }
        });
    }

    // Phone number validation
    if (phoneField) {
        phoneField.addEventListener('blur', function(e) {
            const phonePattern = /^\+91\s[0-9]{10}$|^[0-9]{10}$/;
            if (e.target.value && !phonePattern.test(e.target.value)) {
                e.target.classList.add('is-invalid');
            } else {
                e.target.classList.remove('is-invalid');
            }
        });
    }

    // Form validation before submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = ['customer_name', 'email', 'phone_number', 'platform', 'project_type'];
            
            requiredFields.forEach(function(fieldName) {
                const field = document.getElementById(fieldName);
                if (field && !field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else if (field) {
                    field.classList.remove('is-invalid');
                }
            });
            
            // Email format validation
            const emailField = document.getElementById('email');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailField && emailField.value && !emailPattern.test(emailField.value)) {
                emailField.classList.add('is-invalid');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                // Find first invalid field and focus on it
                const firstInvalidField = document.querySelector('.is-invalid');
                if (firstInvalidField) {
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                alert('Please fix the highlighted fields before submitting.');
            }
        });
    }

    // Clear validation errors when user starts typing
    document.querySelectorAll('.form-control').forEach(function(field) {
        field.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});
</script>
@endpush