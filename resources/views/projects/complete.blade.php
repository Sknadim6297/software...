@extends('layouts.app')

@section('title', 'Complete Project')
@section('page-title', 'Complete Project Details')

@section('content')
<div class="row">
    <div class="col-xl-10 offset-xl-1">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-success me-2"></i>Project Completion - {{ $project->project_name }}
                </h4>
                <p class="mb-0 text-muted">All payments received! Please fill in the final details to complete the project.</p>
            </div>
            <div class="card-body">
                <form action="{{ route('projects.store-completion', $project->id) }}" method="POST" id="completionForm">
                    @csrf

                    <!-- Domain Details -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="flaticon-381-globe me-2"></i>Domain Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Domain Name</label>
                                    <input type="text" class="form-control" name="domain_name" placeholder="example.com">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Purchase Date</label>
                                    <input type="date" class="form-control" name="domain_purchase_date">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Domain Amount (₹)</label>
                                    <input type="number" class="form-control" name="domain_amount" min="0" step="0.01" placeholder="0.00">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Renewal Cycle</label>
                                    <select class="form-control" name="domain_renewal_cycle">
                                        <option value="">Select</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Yearly">Yearly</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Renewal Date</label>
                                    <input type="date" class="form-control" name="domain_renewal_date">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hosting Details -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="flaticon-381-server me-2"></i>Hosting Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Hosting Provider</label>
                                    <input type="text" class="form-control" name="hosting_provider" placeholder="e.g., Hostinger, AWS, GoDaddy">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Purchase Date</label>
                                    <input type="date" class="form-control" name="hosting_purchase_date">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Hosting Amount (₹)</label>
                                    <input type="number" class="form-control" name="hosting_amount" min="0" step="0.01" placeholder="0.00">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Renewal Cycle</label>
                                    <select class="form-control" name="hosting_renewal_cycle">
                                        <option value="">Select</option>
                                        <option value="Monthly">Monthly</option>
                                        <option value="Yearly">Yearly</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Renewal Date</label>
                                    <input type="date" class="form-control" name="hosting_renewal_date">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Maintenance Contract -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="flaticon-381-settings me-2"></i>Maintenance Contract</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Enable Maintenance Contract? <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="maintenance_enabled" id="maintenanceYes" value="1" required>
                                        <label class="form-check-label" for="maintenanceYes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="maintenance_enabled" id="maintenanceNo" value="0" checked>
                                        <label class="form-check-label" for="maintenanceNo">No</label>
                                    </div>
                                </div>
                            </div>

                            <div id="maintenanceFields" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Maintenance Type <span class="text-danger">*</span></label>
                                        <select class="form-control" name="maintenance_type" id="maintenanceType">
                                            <option value="">Select Type</option>
                                            <option value="Free">Free</option>
                                            <option value="Chargeable">Chargeable</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Free Maintenance Fields -->
                                <div id="freeMaintenanceFields" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Number of Free Months <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="maintenance_months" min="1" placeholder="e.g., 6">
                                        </div>
                                    </div>
                                </div>

                                <!-- Chargeable Maintenance Fields -->
                                <div id="chargeableMaintenanceFields" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Maintenance Charge (₹) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="maintenance_charge" min="0" step="0.01" placeholder="0.00">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Billing Cycle <span class="text-danger">*</span></label>
                                            <select class="form-control" name="maintenance_billing_cycle">
                                                <option value="">Select Cycle</option>
                                                <option value="Monthly">Monthly</option>
                                                <option value="Quarterly">Quarterly</option>
                                                <option value="Annually">Annually</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <strong><i class="flaticon-381-information me-2"></i>Note:</strong>
                                    <ul class="mb-0">
                                        <li>A maintenance contract will be automatically created</li>
                                        <li>For chargeable maintenance, an invoice will be generated automatically</li>
                                        <li>All details will be shared with the admin</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-secondary">
                            <i class="flaticon-381-back-1 me-1"></i>Back
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="flaticon-381-check me-1"></i>Complete Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const maintenanceEnabled = document.querySelectorAll('[name="maintenance_enabled"]');
    const maintenanceFields = document.getElementById('maintenanceFields');
    const maintenanceType = document.getElementById('maintenanceType');
    const freeFields = document.getElementById('freeMaintenanceFields');
    const chargeableFields = document.getElementById('chargeableMaintenanceFields');

    // Toggle maintenance fields
    maintenanceEnabled.forEach(radio => {
        radio.addEventListener('change', function() {
            maintenanceFields.style.display = this.value === '1' ? 'block' : 'none';
        });
    });

    // Toggle free/chargeable fields
    maintenanceType.addEventListener('change', function() {
        freeFields.style.display = this.value === 'Free' ? 'block' : 'none';
        chargeableFields.style.display = this.value === 'Chargeable' ? 'block' : 'none';
    });
});
</script>
@endpush
@endsection
