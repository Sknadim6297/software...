@extends('layouts.app')

@section('content')
<link href="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.css" rel="stylesheet">
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
                                        <textarea class="form-control" id="project_description" name="project_description" rows="6"
                                                  placeholder="We, at Konnectix Technologies Pvt Ltd, are pleased to present our proposal for developing and implementing a comprehensive ERP software solution...">{{ old('project_description') }}</textarea>
                                        <small class="text-muted">This will appear in the introduction section of the proposal</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Objectives</label>
                                        <textarea class="form-control" id="objectives" name="objectives" rows="5"
                                                  placeholder="* Automate and streamline day-to-day operations&#10;* Enable real-time access to data for better decision-making&#10;* Maintain detailed log book of all activities...">{{ old('objectives') }}</textarea>
                                        <small class="text-muted">List the key objectives</small>
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
                                    Tip: Use formatting tools above. This content can span multiple pages.
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
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Payment Terms <span class="text-danger">*</span></label>
                                        <div id="payment-terms-container">
                                            <div class="payment-term-item mb-3">
                                                <div class="row align-items-end">
                                                    <div class="col-md-7">
                                                        <label class="form-label">Payment Stage Description</label>
                                                        <input type="text" class="form-control" name="payment_descriptions[]" 
                                                               value="Advance Payment (Project Kickoff)" placeholder="e.g., Advance Payment, After Development" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Percentage (%)</label>
                                                        <input type="number" class="form-control payment-percentage" name="payment_percentages[]" 
                                                               value="30" min="0" max="100" step="0.01" required>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-sm btn-danger remove-payment-term" style="display:none;">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="payment-term-item mb-3">
                                                <div class="row align-items-end">
                                                    <div class="col-md-7">
                                                        <label class="form-label">Payment Stage Description</label>
                                                        <input type="text" class="form-control" name="payment_descriptions[]" 
                                                               value="After Completion of Development" placeholder="e.g., Advance Payment, After Development" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Percentage (%)</label>
                                                        <input type="number" class="form-control payment-percentage" name="payment_percentages[]" 
                                                               value="40" min="0" max="100" step="0.01" required>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-sm btn-danger remove-payment-term">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="payment-term-item mb-3">
                                                <div class="row align-items-end">
                                                    <div class="col-md-7">
                                                        <label class="form-label">Payment Stage Description</label>
                                                        <input type="text" class="form-control" name="payment_descriptions[]" 
                                                               value="After Final Deployment" placeholder="e.g., Advance Payment, After Development" required>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Percentage (%)</label>
                                                        <input type="number" class="form-control payment-percentage" name="payment_percentages[]" 
                                                               value="30" min="0" max="100" step="0.01" required>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-sm btn-danger remove-payment-term">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="add-payment-term">
                                            <i class="fas fa-plus me-1"></i>Add Another Payment Term
                                        </button>
                                        <div class="alert alert-warning mt-3" id="payment-total-warning" style="display:none;">
                                            <strong>Warning:</strong> Total payment percentages must equal 100%. Current total: <span id="payment-total">100</span>%
                                        </div>
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
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor
    CKEDITOR.replace('project_description', {
        height: 200,
        toolbar: [
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Blockquote'] },
            { name: 'insert', items: ['Table'] },
            { name: 'styles', items: ['Format'] }
        ]
    });
    
    CKEDITOR.replace('objectives', {
        height: 150,
        toolbar: [
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList'] }
        ]
    });
    
    CKEDITOR.replace('scope_of_work', {
        height: 400,
        toolbar: [
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Blockquote'] },
            { name: 'insert', items: ['Table'] },
            { name: 'styles', items: ['Format', 'Styles'] }
        ]
    });
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

    // Payment terms functionality
    function calculatePaymentTotal() {
        const percentages = document.querySelectorAll('.payment-percentage');
        let total = 0;
        percentages.forEach(function(input) {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('payment-total').textContent = total.toFixed(2);
        
        const warning = document.getElementById('payment-total-warning');
        if (Math.abs(total - 100) > 0.01) {
            warning.style.display = 'block';
        } else {
            warning.style.display = 'none';
        }
        return total;
    }
    
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('payment-percentage')) {
            calculatePaymentTotal();
        }
    });
    
    document.getElementById('add-payment-term').addEventListener('click', function() {
        const container = document.getElementById('payment-terms-container');
        const newTerm = document.createElement('div');
        newTerm.className = 'payment-term-item mb-3';
        newTerm.innerHTML = `
            <div class="row align-items-end">
                <div class="col-md-7">
                    <label class="form-label">Payment Stage Description</label>
                    <input type="text" class="form-control" name="payment_descriptions[]" 
                           placeholder="e.g., After Testing, After Training" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Percentage (%)</label>
                    <input type="number" class="form-control payment-percentage" name="payment_percentages[]" 
                           value="0" min="0" max="100" step="0.01" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-danger remove-payment-term">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newTerm);
        calculatePaymentTotal();
        updateRemoveButtons();
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-payment-term')) {
            e.target.closest('.payment-term-item').remove();
            calculatePaymentTotal();
            updateRemoveButtons();
        }
    });
    
    function updateRemoveButtons() {
        const items = document.querySelectorAll('.payment-term-item');
        items.forEach(function(item, index) {
            const removeBtn = item.querySelector('.remove-payment-term');
            if (items.length === 1) {
                removeBtn.style.display = 'none';
            } else {
                removeBtn.style.display = 'block';
            }
        });
    }
    
    calculatePaymentTotal();
    updateRemoveButtons();

    // Form validation
    document.getElementById('erpSoftwareForm').addEventListener('submit', function(e) {
        // Update CKEditor instances before submit
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        
        const total = calculatePaymentTotal();
        if (Math.abs(total - 100) > 0.01) {
            e.preventDefault();
            alert('Payment percentages must add up to 100%. Current total: ' + total.toFixed(2) + '%');
            return false;
        }
    });
});
</script>
@endpush
@endsection
