@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-laptop-code me-2"></i>
                        ERP / Software Development Proposal
                    </h4>
                    <p class="mb-0 text-muted">Create a professional software development proposal for {{ $lead->customer_name }}</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('proposals.store-erp-software') }}" method="POST" id="erpSoftwareForm">
                        @csrf
                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                        <input type="hidden" name="lead_type" value="{{ $leadType }}">
                        <input type="hidden" name="project_type" value="{{ $lead->project_type }}">
                        
                        <!-- Customer Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-user me-2"></i>Customer Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Customer Name:</strong><br>
                                        <span>{{ $lead->customer_name }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Email:</strong><br>
                                        <span>{{ $lead->email }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Phone:</strong><br>
                                        <span>{{ $lead->phone_number }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Lead Type:</strong><br>
                                        <span class="badge badge-{{ $leadType === 'incoming' ? 'success' : 'primary' }}">
                                            {{ ucfirst($leadType) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Basic Details -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Basic Proposal Details
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Project Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="project_title" 
                                               placeholder="e.g., ERP System for BENSEN CONSULTING SERVICES LLP" required>
                                        <small class="text-muted">This will appear as the main heading in the proposal</small>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Project Overview/Introduction</label>
                                        <textarea class="form-control" name="project_description" rows="6"
                                                  placeholder="We, at Konnectix Technologies Pvt Ltd, are pleased to present our proposal for developing and implementing a comprehensive ERP software solution...&#10;&#10;Our ERP solution is designed to optimize workflows, enhance productivity...">{{ old('project_description') }}</textarea>
                                        <small class="text-muted">This will appear in the introduction section of the proposal</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Objectives</label>
                                        <textarea class="form-control" name="objectives" rows="5"
                                                  placeholder="* Automate and streamline day-to-day operations&#10;* Enable real-time access to data for better decision-making&#10;* Maintain detailed log book of all activities...">{{ old('objectives') }}</textarea>
                                        <small class="text-muted">List the key objectives. Use * for bullet points</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Scope of Work -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-list-check me-2"></i>Scope of Work
                                </h6>
                                <p class="mb-0 mt-2 text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Describe all modules, features, and functionality in detail. Use headings and formatting as needed.
                                </p>
                            </div>
                            <div class="card-body">
                                <textarea class="form-control" id="scope_of_work" name="scope_of_work" rows="20"
                                          placeholder="Modules to be Developed:&#10;&#10;1. User Management & Access Control&#10;&#10;* Super Admin Panel: Full control...&#10;&#10;Describe your scope of work with all modules and features here...">{{ old('scope_of_work') }}</textarea>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Tip: Use * for bullet points, ## for headings. This content can span multiple pages.
                                </small>
                            </div>
                        </div>

                        <!-- Pricing & Payment -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-rupee-sign me-2"></i>Pricing & Payment Terms
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Total Project Cost (₹) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="total_cost" id="total_cost"
                                               value="{{ $lead->project_valuation ?? '' }}"
                                               placeholder="900000" min="1000" required>
                                        @if($lead->project_valuation)
                                            <small class="text-muted">Auto-fetched from lead valuation</small>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">GST Percentage (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="gst_percentage" id="gst_percentage"
                                               value="18" min="0" max="100" step="0.01" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="alert alert-info">
                                            <strong>Final Amount (with GST):</strong> 
                                            <span id="final_amount" class="ms-2 h5">₹0</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Advance Payment (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="advance_percentage" 
                                               value="30" min="0" max="100" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">After Development (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="development_percentage" 
                                               value="40" min="0" max="100" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">After Deployment (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="deployment_percentage" 
                                               value="30" min="0" max="100" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline & Technical Details -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-calendar-days me-2"></i>Timeline & Technical Specifications
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Project Timeline (Weeks) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="timeline_weeks" 
                                               value="10" min="1" placeholder="8-10" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Free Support Period (Months)</label>
                                        <input type="number" class="form-control" name="support_months" 
                                               value="6" min="0" placeholder="6">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Architecture</label>
                                        <select class="form-control" name="architecture">
                                            <option value="Cloud-based / On-premise (as per requirement)">Cloud-based / On-premise</option>
                                            <option value="Cloud-based (Hostinger/Hostgator)">Cloud-based (Hostinger/Hostgator)</option>
                                            <option value="On-premise">On-premise</option>
                                            <option value="Hybrid Architecture">Hybrid Architecture</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Technology Stack</label>
                                        <input type="text" class="form-control" name="technology_stack" 
                                               value="PHP/Laravel, MySQL, React/HTML, CSS, Bootstrap 4.0"
                                               placeholder="PHP/Laravel, MySQL, React/HTML, CSS, Bootstrap">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Deliverables -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-box-check me-2"></i>Deliverables
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="deliverables-container">
                                    <div class="deliverable-item mb-2">
                                        <input type="text" class="form-control" name="deliverables[]" 
                                               value="Fully functional ERP system as per the agreed scope" 
                                               placeholder="Deliverable item...">
                                    </div>
                                    <div class="deliverable-item mb-2">
                                        <input type="text" class="form-control" name="deliverables[]" 
                                               value="Admin and Super Admin manuals" 
                                               placeholder="Deliverable item...">
                                    </div>
                                    <div class="deliverable-item mb-2">
                                        <input type="text" class="form-control" name="deliverables[]" 
                                               value="User training materials" 
                                               placeholder="Deliverable item...">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-deliverable">
                                    <i class="fas fa-plus me-1"></i>Add Another Deliverable
                                </button>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-sticky-note me-2"></i>Additional Notes
                                </h6>
                            </div>
                            <div class="card-body">
                                <textarea class="form-control" name="additional_notes" rows="4"
                                          placeholder="Any additional information, special requirements, or terms...">{{ old('additional_notes') }}</textarea>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('proposals.create') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-contract me-2"></i>Generate Proposal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.platforms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 10px;
}

.feature-item, .deliverable-item {
    position: relative;
}

.remove-btn {
    position: absolute;
    right: 10px;
    top: 10px;
    z-index: 10;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate final amount
    function calculateFinalAmount() {
        const totalCost = parseFloat(document.getElementById('total_cost').value) || 0;
        const gstPercentage = parseFloat(document.getElementById('gst_percentage').value) || 0;
        const gstAmount = (totalCost * gstPercentage) / 100;
        const finalAmount = totalCost + gstAmount;
        document.getElementById('final_amount').textContent = '₹' + finalAmount.toLocaleString('en-IN');
    }

    document.getElementById('total_cost').addEventListener('input', calculateFinalAmount);
    document.getElementById('gst_percentage').addEventListener('input', calculateFinalAmount);
    
    // Initial calculation
    calculateFinalAmount();

    // Add feature functionality
    let featureCount = 1;
    const addFeatureBtn = document.getElementById('add-feature');
    if (addFeatureBtn) {
        addFeatureBtn.addEventListener('click', function() {
            featureCount++;
            const container = document.getElementById('features-container');
            const newFeature = document.createElement('div');
            newFeature.className = 'feature-item mb-3';
            newFeature.innerHTML = `
                <label class="form-label">Feature/Module ${featureCount}</label>
                <div class="position-relative">
                    <textarea class="form-control" name="features[]" rows="8" 
                              placeholder="Feature Title (First line becomes heading)\n\nDetailed description of this feature/module..."></textarea>
                    <button type="button" class="btn btn-sm btn-danger remove-btn remove-feature" style="top: 5px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            container.appendChild(newFeature);
        });
    }

    // Remove feature functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-feature')) {
            e.target.closest('.feature-item').remove();
        }
    });

    // Add deliverable functionality
    document.getElementById('add-deliverable').addEventListener('click', function() {
        const container = document.getElementById('deliverables-container');
        const newDeliverable = document.createElement('div');
        newDeliverable.className = 'deliverable-item mb-2 position-relative';
        newDeliverable.innerHTML = `
            <input type="text" class="form-control" name="deliverables[]" placeholder="Deliverable item...">
            <button type="button" class="btn btn-sm btn-danger remove-btn remove-deliverable" style="position: absolute; right: 10px; top: 5px;">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newDeliverable);
    });

    // Remove deliverable functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-deliverable')) {
            e.target.closest('.deliverable-item').remove();
        }
    });

    // Form validation
    document.getElementById('erpSoftwareForm').addEventListener('submit', function(e) {
        const advance = parseFloat(document.querySelector('[name="advance_percentage"]').value) || 0;
        const development = parseFloat(document.querySelector('[name="development_percentage"]').value) || 0;
        const deployment = parseFloat(document.querySelector('[name="deployment_percentage"]').value) || 0;
        const total = advance + development + deployment;

        if (total !== 100) {
            e.preventDefault();
            alert('Payment percentages must add up to 100%. Current total: ' + total + '%');
            return false;
        }
    });
});
</script>
@endpush
@endsection
