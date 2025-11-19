@extends('layouts.app')

@section('title', 'Add Customer - Konnectix')

@section('page-title', 'Add New Customer')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Customer Information</h4>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Validation Errors:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="basic-form">
                    <form action="{{ route('customers.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                    name="customer_name" value="{{ old('customer_name') }}" placeholder="Enter customer name" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                    name="company_name" value="{{ old('company_name') }}" placeholder="Enter company name">
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('number') is-invalid @enderror" 
                                    name="number" value="{{ old('number') }}" placeholder="Enter phone number" required>
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Alternate Number</label>
                                <input type="text" class="form-control @error('alternate_number') is-invalid @enderror" 
                                    name="alternate_number" value="{{ old('alternate_number') }}" placeholder="Enter alternate number">
                                @error('alternate_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" placeholder="Enter email address">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Project Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('project_type') is-invalid @enderror" name="project_type" id="projectType" onchange="updateValuationRange()" required>
                                    <option value="">Select Project Type</option>
                                    @php $projectTypes = App\Models\Customer::getProjectTypes(); @endphp
                                    @foreach($projectTypes as $key => $type)
                                        <option value="{{ $key }}" {{ old('project_type') == $key ? 'selected' : '' }}
                                                data-min="{{ $type['default_valuation_range']['min'] }}"
                                                data-max="{{ $type['default_valuation_range']['max'] }}">
                                            {{ $type['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Project Valuation (₹) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('project_valuation') is-invalid @enderror" 
                                    name="project_valuation" id="projectValuation" value="{{ old('project_valuation') }}" 
                                    placeholder="Enter project value" min="0" step="0.01" required>
                                <div class="form-text" id="valuationRange">Select project type to see suggested range</div>
                                @error('project_valuation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Project Start Date</label>
                                <input type="date" class="form-control @error('project_start_date') is-invalid @enderror" 
                                    name="project_start_date" value="{{ old('project_start_date') }}">
                                @error('project_start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Payment Terms <span class="text-danger">*</span></label>
                                <select class="form-control @error('payment_terms') is-invalid @enderror" name="payment_terms" id="paymentTerms" required>
                                    <option value="">Select Project Type First</option>
                                </select>
                                @error('payment_terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lead Source</label>
                                <select class="form-control @error('lead_source') is-invalid @enderror" name="lead_source">
                                    <option value="">Select Lead Source</option>
                                    <option value="website" {{ old('lead_source') == 'website' ? 'selected' : '' }}>Website</option>
                                    <option value="facebook" {{ old('lead_source') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                    <option value="instagram" {{ old('lead_source') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                    <option value="linkedin" {{ old('lead_source') == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                                    <option value="google" {{ old('lead_source') == 'google' ? 'selected' : '' }}>Google</option>
                                    <option value="justdial" {{ old('lead_source') == 'justdial' ? 'selected' : '' }}>Justdial</option>
                                    <option value="referral" {{ old('lead_source') == 'referral' ? 'selected' : '' }}>Referral</option>
                                    <option value="cold_call" {{ old('lead_source') == 'cold_call' ? 'selected' : '' }}>Cold Call</option>
                                    <option value="email" {{ old('lead_source') == 'email' ? 'selected' : '' }}>Email</option>
                                    <option value="other" {{ old('lead_source') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('lead_source')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                name="address" rows="3" placeholder="Enter address">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Remarks/Notes</label>
                            <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                name="remarks" rows="3" placeholder="Enter any additional remarks or notes">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">GST Number</label>
                                <input type="text" class="form-control @error('gst_number') is-invalid @enderror" 
                                    name="gst_number" value="{{ old('gst_number') }}" placeholder="Enter GST number">
                                @error('gst_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">State Code</label>
                                <input type="text" class="form-control @error('state_code') is-invalid @enderror" 
                                    name="state_code" value="{{ old('state_code') }}" placeholder="e.g., 27">
                                @error('state_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">State Name</label>
                                <input type="text" class="form-control @error('state_name') is-invalid @enderror" 
                                    name="state_name" value="{{ old('state_name') }}" placeholder="e.g., Maharashtra">
                                @error('state_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check custom-checkbox mb-3">
                                <input type="checkbox" class="form-check-input" id="active" name="active" 
                                    value="1" {{ old('active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="active">Active Customer</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('customers.index') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Customer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Project types data from backend
    const projectTypesData = @json(App\Models\Customer::getProjectTypes());
    
    function updateValuationRange() {
        const projectType = document.getElementById('projectType').value;
        const valuationInput = document.getElementById('projectValuation');
        const valuationRange = document.getElementById('valuationRange');
        const paymentTerms = document.getElementById('paymentTerms');
        
        if (projectType && projectTypesData[projectType]) {
            const typeData = projectTypesData[projectType];
            
            // Update valuation range
            const min = typeData.default_valuation_range.min;
            const max = typeData.default_valuation_range.max;
            valuationInput.min = min;
            valuationInput.max = max;
            valuationRange.innerHTML = `Suggested range: ₹${min.toLocaleString()} - ₹${max.toLocaleString()}`;
            valuationRange.className = 'form-text text-info';
            
            // Update payment terms
            paymentTerms.innerHTML = '<option value="">Select Payment Terms</option>';
            typeData.payment_terms.forEach(term => {
                const option = document.createElement('option');
                option.value = term;
                option.textContent = term;
                paymentTerms.appendChild(option);
            });
            paymentTerms.disabled = false;
            
        } else {
            valuationRange.innerHTML = 'Select project type to see suggested range';
            valuationRange.className = 'form-text text-muted';
            paymentTerms.innerHTML = '<option value="">Select Project Type First</option>';
            paymentTerms.disabled = true;
            valuationInput.min = 0;
            valuationInput.max = '';
        }
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateValuationRange();
        
        // If there's an old value for project type (validation error), trigger update
        const oldProjectType = '{{ old("project_type") }}';
        const oldPaymentTerms = '{{ old("payment_terms") }}';
        
        if (oldProjectType) {
            updateValuationRange();
            
            // Set the old payment terms value if it exists
            if (oldPaymentTerms) {
                setTimeout(() => {
                    const paymentSelect = document.getElementById('paymentTerms');
                    paymentSelect.value = oldPaymentTerms;
                }, 100);
            }
        }
    });
    
    // Add validation for valuation range
    document.getElementById('projectValuation').addEventListener('input', function() {
        const value = parseFloat(this.value);
        const min = parseFloat(this.min);
        const max = parseFloat(this.max);
        const rangeTip = document.getElementById('valuationRange');
        
        if (value && min && max) {
            if (value < min || value > max) {
                rangeTip.innerHTML = `⚠️ Value outside suggested range: ₹${min.toLocaleString()} - ₹${max.toLocaleString()}`;
                rangeTip.className = 'form-text text-warning';
            } else {
                rangeTip.innerHTML = `✅ Within suggested range: ₹${min.toLocaleString()} - ₹${max.toLocaleString()}`;
                rangeTip.className = 'form-text text-success';
            }
        }
    });
</script>
@endpush
