@extends('layouts.app')

@section('title', 'Create Maintenance Contract')

@section('page-title', 'Create Maintenance Contract')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Maintenance Contract for {{ $project->project_name }}</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Project Completed!</strong> Please provide maintenance contract details.
                </div>
                
                <form action="{{ route('projects.maintenance-contract.store', $project) }}" method="POST">
                    @csrf
                    
                    <h5 class="mb-3">Contract Details</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contract Type <span class="text-danger">*</span></label>
                            <select name="contract_type" id="contract_type" class="form-control" required>
                                <option value="">Select Contract Type</option>
                                <option value="Free">Free</option>
                                <option value="Chargeable">Chargeable</option>
                            </select>
                            @error('contract_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3" id="free_months_div" style="display: none;">
                            <label class="form-label">Free Months <span class="text-danger">*</span></label>
                            <input type="number" name="free_months" class="form-control" min="1">
                            @error('free_months')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3" id="charges_div" style="display: none;">
                            <label class="form-label">Charges <span class="text-danger">*</span></label>
                            <input type="number" name="charges" class="form-control" step="0.01" min="0">
                            @error('charges')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3" id="frequency_div" style="display: none;">
                            <label class="form-label">Charge Frequency <span class="text-danger">*</span></label>
                            <select name="charge_frequency" class="form-control">
                                <option value="">Select Frequency</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Quarterly">Quarterly</option>
                                <option value="Annually">Annually</option>
                            </select>
                            @error('charge_frequency')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <h5 class="mt-4 mb-3">Domain Details (Optional)</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Domain Purchase Date</label>
                            <input type="date" name="domain_purchase_date" class="form-control">
                            @error('domain_purchase_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Domain Amount</label>
                            <input type="number" name="domain_amount" class="form-control" step="0.01" min="0">
                            @error('domain_amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Domain Renewal Date</label>
                            <input type="date" name="domain_renewal_date" class="form-control">
                            @error('domain_renewal_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <h5 class="mt-4 mb-3">Hosting Details (Optional)</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Hosting Purchase Date</label>
                            <input type="date" name="hosting_purchase_date" class="form-control">
                            @error('hosting_purchase_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Hosting Amount</label>
                            <input type="number" name="hosting_amount" class="form-control" step="0.01" min="0">
                            @error('hosting_amount')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Hosting Renewal Date</label>
                            <input type="date" name="hosting_renewal_date" class="form-control">
                            @error('hosting_renewal_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Create Maintenance Contract</button>
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contractType = document.getElementById('contract_type');
    const freeMonthsDiv = document.getElementById('free_months_div');
    const chargesDiv = document.getElementById('charges_div');
    const frequencyDiv = document.getElementById('frequency_div');
    
    contractType.addEventListener('change', function() {
        if (this.value === 'Free') {
            freeMonthsDiv.style.display = 'block';
            chargesDiv.style.display = 'none';
            frequencyDiv.style.display = 'none';
        } else if (this.value === 'Chargeable') {
            freeMonthsDiv.style.display = 'none';
            chargesDiv.style.display = 'block';
            frequencyDiv.style.display = 'block';
        } else {
            freeMonthsDiv.style.display = 'none';
            chargesDiv.style.display = 'none';
            frequencyDiv.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection
