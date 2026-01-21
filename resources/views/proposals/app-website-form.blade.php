@extends('layouts.app')

@section('content')
<link href="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.css" rel="stylesheet">
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-globe me-2"></i>
                        App & Website Development Agreement
                    </h4>
                    <p class="mb-0 text-muted">Create a professional development agreement for {{ $lead->customer_name }}</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('proposals.store-app-website') }}" method="POST" id="appWebsiteForm">
                        @csrf
                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                        <input type="hidden" name="lead_type" value="{{ $leadType }}">
                        <input type="hidden" name="project_type" value="{{ $lead->project_type }}">
                        
                        <!-- Customer Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-user me-2"></i>Client Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Client Name:</strong><br>
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
                        
                        <!-- Project Details + Financials -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-laptop-code me-2"></i>Project Details
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Project Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="project_title" 
                                               placeholder="e.g., E-COMMERCE WEBSITE DEVELOPMENT" 
                                               value="E-COMMERCE WEBSITE DEVELOPMENT" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Total Project Cost (₹) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="total_cost" id="total_cost"
                                               value="{{ $lead->project_valuation ?? 7000 }}"
                                               placeholder="7000" min="1000" required>
                                        @if($lead->project_valuation)
                                            <small class="text-muted">Auto-fetched from lead valuation</small>
                                        @endif
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">GST Percentage (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="gst_percentage" id="gst_percentage"
                                               value="18" min="0" max="100" step="0.01" required>
                                    </div>
                                    <div class="col-md-4 mb-3 d-flex align-items-end">
                                        <div class="alert alert-info w-100 mb-0">
                                            <strong>Final Amount (with GST):</strong>
                                            <div id="final_amount" class="mt-1">₹0</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Project Timeline (Weeks) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="timeline_weeks" 
                                               value="4" min="1" placeholder="e.g., 4" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Free Support Period (Months)</label>
                                        <input type="number" class="form-control" name="support_months" 
                                               value="1" min="0" placeholder="1">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Narrative Sections (same as Software form) -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Introduction & Objectives
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Project Overview/Introduction</label>
                                        <textarea class="form-control" id="project_description" name="project_description" rows="6"
                                                  placeholder="We, at Konnectix Technologies Pvt Ltd, are pleased to present our proposal...">{{ old('project_description', 'We, at Konnectix Technologies Pvt Ltd, are pleased to present our proposal for developing and implementing a comprehensive website/mobile application solution for your business. This proposal outlines our approach, deliverables, timeline, and investment required to bring your vision to reality. Our team specializes in creating user-friendly, scalable, and feature-rich digital solutions tailored to your specific business needs.') }}</textarea>
                                        <small class="text-muted">This will appear in the introduction section of the agreement</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Objectives</label>
                                        <textarea class="form-control" id="objectives" name="objectives" rows="5"
                                                  placeholder="* Automate and streamline day-to-day operations...">{{ old('objectives', '• Create a user-friendly interface that enhances customer engagement and experience\n• Implement robust security measures to protect sensitive customer and business data\n• Enable real-time analytics and reporting for better business insights\n• Ensure seamless integration with existing business processes\n• Provide scalable architecture for future growth and feature additions') }}</textarea>
                                        <small class="text-muted">List the key objectives</small>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                <textarea class="form-control" id="scope_of_work" name="scope_of_work" rows="18"
                                          placeholder="Modules to be Developed:&#10;&#10;1. Website/App UI & UX...">{{ old('scope_of_work', '## MODULES & FEATURES TO BE DEVELOPED

### 1. Website/App User Interface & User Experience
- Professional, responsive design compatible with all devices (Desktop, Tablet, Mobile)
- Intuitive navigation with clear call-to-action buttons
- Fast loading times and optimized performance
- Accessibility features for users with disabilities

### 2. User Authentication & Account Management
- Secure user registration and login system
- Email verification and password reset functionality
- User profile management with editable information
- Social login integration (Google, Facebook)

### 3. Product/Service Catalog
- Dynamic product/service listing with search and filter capabilities
- Detailed product pages with images, descriptions, and specifications
- Category-based organization and navigation
- Customer reviews and ratings system

### 4. Shopping Cart & Checkout
- Intuitive shopping cart with add/remove functionality
- Cart persistence across sessions
- Secure checkout process with multiple payment options
- Order summary and confirmation

### 5. Payment Gateway Integration
- Secure payment processing with industry-standard encryption
- Support for multiple payment methods (Credit/Debit Cards, Wallets, UPI)
- Payment success/failure handling and notifications
- Transaction history and receipts

### 6. Admin Dashboard
- Comprehensive dashboard with key metrics and analytics
- Product/Service management (Create, Read, Update, Delete)
- Order management and fulfillment tracking
- User management and role-based access control
- Reports and analytics generation

### 7. Customer Support System
- Contact form and inquiry management
- Ticketing system for customer issues
- Live chat support integration (optional)
- FAQ section and knowledge base

### 8. Additional Features
- Email notifications for order confirmations and updates
- SMS alerts for order status (optional)
- Newsletter subscription management
- Social media integration
- SEO optimization') }}</textarea>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Tip: Use formatting tools above. This content can span multiple pages.
                                </small>
                            </div>
                        </div>

                        <!-- Deliverables Section -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-box-open me-2"></i>Deliverables
                                </h6>
                                <p class="mb-0 mt-2 text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Specify what will be delivered to the client upon project completion.
                                </p>
                            </div>
                            <div class="card-body">
                                <textarea class="form-control" id="deliverables" name="deliverables" rows="10"
                                          placeholder="List all deliverables...">{{ old('deliverables', '- Fully Functional and Live Website
- Admin Panel Access (if CMS-based)
- 1-Month Complimentary Support Post-Launch
- Basic Training Session for Admin Use (if applicable)
- Source Code and Documentation
- User Manual/Guide') }}</textarea>
                                <small class="text-muted mt-2 d-block">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Tip: Use formatting tools to create a clear list of deliverables.
                                </small>
                            </div>
                        </div>

                        <!-- Payment Terms (same as Software form) -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-rupee-sign me-2"></i>Payment Terms
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong>Note:</strong> Payment percentages must total 100%. Add as many stages as you need.
                                </div>
                                <div id="payment-terms-container">
                                    <div class="payment-term-item mb-3">
                                        <div class="row align-items-end">
                                            <div class="col-md-7">
                                                <label class="form-label">Payment Stage Description</label>
                                                <input type="text" class="form-control" name="payment_descriptions[]" 
                                                       value="Advance Payment (Project Kickoff)" placeholder="e.g., Advance Payment" required>
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
                                                       value="After Development Completion" placeholder="e.g., After Development" required>
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
                                                       value="After Final Deployment" placeholder="e.g., After Final Deployment" required>
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



                        <!-- Preview Section -->
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-eye me-2"></i>Agreement Preview
                                </h6>
                            </div>
                            <div class="card-body" id="proposalPreview">
                                <p class="text-muted text-center">Fill in the details above to see a preview of your agreement</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="button" class="btn btn-info me-2" id="generatePreviewBtn">
                                            <i class="fa fa-eye me-1"></i> Generate Preview
                                        </button>
                                        <a href="{{ route('proposals.select-customer', ['lead_type' => $leadType]) }}" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left me-1"></i> Back to Customer Selection
                                        </a>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-check me-1"></i> Generate & Send Agreement
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Container Optimization */
.container-fluid {
    padding: 15px;
}

/* Card Styling */
.card {
    margin-bottom: 1.25rem;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    padding: 0.75rem 1.25rem;
}

.card-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2d7a32;
}

/* Form Styling */
.form-label {
    font-weight: 500;
    color: #333;
    margin-bottom: 0.5rem;
}

.form-control:focus {
    border-color: #33973a;
    box-shadow: 0 0 0 0.2rem rgba(51, 151, 58, 0.25);
}

/* Badge Styling */
.badge {
    padding: 0.4em 0.75em;
    font-size: 0.85rem;
    font-weight: 500;
    border-radius: 4px;
}

/* Preview Section */
#proposalPreview {
    max-height: 600px;
    overflow-y: auto;
}

/* Utility Classes */
.text-primary { color: #0d6efd !important; }
.text-success { color: #198754 !important; }
.text-info { color: #0dcaf0 !important; }
.text-warning { color: #ffc107 !important; }
</style>

@push('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // CKEditor setup (same as software form)
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
    CKEDITOR.replace('deliverables', {
        height: 250,
        toolbar: [
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Blockquote'] },
            { name: 'insert', items: ['Table'] }
        ]
    });

    // Calculate final amount with GST
    function calculateFinalAmount() {
        const totalCost = parseFloat(document.getElementById('total_cost').value) || 0;
        const gstPercentage = parseFloat(document.getElementById('gst_percentage').value) || 0;
        const gstAmount = (totalCost * gstPercentage) / 100;
        const finalAmount = totalCost + gstAmount;
        document.getElementById('final_amount').textContent = '₹' + finalAmount.toLocaleString('en-IN');
        return { totalCost, gstAmount, finalAmount };
    }

    document.getElementById('total_cost').addEventListener('input', calculateFinalAmount);
    document.getElementById('gst_percentage').addEventListener('input', calculateFinalAmount);
    calculateFinalAmount();

    // Payment term helpers
    function calculatePaymentTotal() {
        const percentages = document.querySelectorAll('.payment-percentage');
        let total = 0;
        percentages.forEach(function(input) {
            total += parseFloat(input.value) || 0;
        });
        document.getElementById('payment-total').textContent = total.toFixed(2);
        const warning = document.getElementById('payment-total-warning');
        warning.style.display = Math.abs(total - 100) > 0.01 ? 'block' : 'none';
        return total;
    }

    function updateRemoveButtons() {
        const items = document.querySelectorAll('.payment-term-item');
        items.forEach(function(item) {
            const removeBtn = item.querySelector('.remove-payment-term');
            removeBtn.style.display = items.length === 1 ? 'none' : 'block';
        });
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

    calculatePaymentTotal();
    updateRemoveButtons();

    // Auto-generate preview on page load
    setTimeout(function() {
        const generateBtn = document.getElementById('generatePreviewBtn');
        if (generateBtn) {
            generateBtn.click();
        }
    }, 1000);

    // Generate Preview (mirrors software form output)
    document.getElementById('generatePreviewBtn').addEventListener('click', function() {
        // Sync CKEditor fields
        Object.values(CKEDITOR.instances).forEach(instance => instance.updateElement());

        const form = document.getElementById('appWebsiteForm');
        const formData = new FormData(form);

        const { totalCost, gstAmount, finalAmount } = calculateFinalAmount();
        const projectTitle = formData.get('project_title') || 'APP & WEBSITE DEVELOPMENT';
        const timelineWeeks = formData.get('timeline_weeks') || '4';
        const supportMonths = formData.get('support_months') || '0';

        const paymentDescriptions = formData.getAll('payment_descriptions[]');
        const paymentPercentages = formData.getAll('payment_percentages[]');

        const paymentList = paymentDescriptions.map((desc, index) => {
            const pct = parseFloat(paymentPercentages[index] || 0);
            const amt = (finalAmount * pct) / 100;
            return `<li>${pct}% - ${desc} (₹${amt.toLocaleString('en-IN')})</li>`;
        }).join('');

        const previewHTML = `
            <div class="proposal-preview" style="font-family: 'Times New Roman', serif; max-width: 800px;">
                <div class="text-center mb-4">
                    <h2 class="text-primary">${projectTitle} AGREEMENT</h2>
                    <p class="text-muted">This Agreement is made on ${new Date().toLocaleDateString('en-GB')}</p>
                </div>

                <div class="mb-4">
                    <h5>BETWEEN</h5>
                    <p><strong>{{ $lead->customer_name }}</strong>,<br>
                    hereinafter referred to as the <strong>"Client"</strong>,</p>

                    <p><strong>AND</strong></p>

                    <p><strong>Konnectix Technologies Pvt. Ltd.</strong>,<br>
                    hereinafter referred to as the <strong>"Service Provider."</strong></p>

                    <p>The Client and the Service Provider shall collectively be referred to as the <strong>"Parties."</strong></p>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <h5 class="text-success">1. INTRODUCTION</h5>
                    <div>${formData.get('project_description') || ''}</div>
                </div>

                <div class="mb-4">
                    <h5 class="text-success">2. OBJECTIVES</h5>
                    <div>${formData.get('objectives') || ''}</div>
                </div>

                <div class="mb-4">
                    <h5 class="text-success">3. SCOPE OF WORK</h5>
                    <div>${formData.get('scope_of_work') || ''}</div>
                    <p class="alert alert-info mt-2">Any features or changes beyond the above scope shall be considered additional work and charged separately upon mutual agreement.</p>
                </div>

                <div class="mb-4">
                    <h5 class="text-success">4. PROJECT TIMELINE</h5>
                    <ul>
                        <li>Project commencement after kickoff payment and required materials from the Client</li>
                        <li>Estimated completion timeline: <strong>${timelineWeeks} weeks</strong></li>
                        <li>Delays due to late content/approvals extend the timeline accordingly</li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h5 class="text-success">5. FEES & PAYMENT TERMS</h5>
                    <ul>
                        <li><strong>Base Cost:</strong> ₹${totalCost.toLocaleString('en-IN')} (Rupees ${convertNumberToWords(totalCost)} Only)</li>
                        <li><strong>GST (${formData.get('gst_percentage')}%):</strong> ₹${gstAmount.toLocaleString('en-IN')}</li>
                        <li><strong>Final Amount:</strong> ₹${finalAmount.toLocaleString('en-IN')}</li>
                        <li><strong>Payment Schedule:</strong>
                            <ul>${paymentList}</ul>
                        </li>
                    </ul>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <h5 class="text-success">6. DELIVERABLES</h5>
                    <div>${formData.get('deliverables') || ''}</div>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <h5 class="text-success">7. APPROVAL</h5>
                    <p>Please review and confirm your acceptance of the proposal.</p>
                    <p><strong>Client Name:</strong> {{ $lead->customer_name }}<br>
                    <strong>Company Name:</strong> {{ $lead->company_name ?? 'N/A' }}<br>
                    <strong>Signature:</strong> _________________<br>
                    <strong>Date:</strong> _________________</p>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <h5 class="text-success">8. CONTACT INFORMATION</h5>
                    <p><strong>Konnectix Technologies Pvt. Ltd.</strong><br>
                    📞 Phone: 9123354003<br>
                    📧 Email: info@konnectixtech.com<br>
                    🌐 Website: www.konnectixtech.com</p>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-6">
                        <h6>For {{ $lead->customer_name }} (Client)</h6>
                        <p>Name: {{ $lead->customer_name }}<br>
                        Signature: _________________<br>
                        Date: _________________</p>
                    </div>
                    <div class="col-md-6">
                        <h6>For Konnectix Technologies Pvt. Ltd. (Service Provider)</h6>
                        <p>Name: Ishita Banerjee<br>
                        Designation: Director<br>
                        Signature: _________________<br>
                        Date: ${new Date().toLocaleDateString('en-GB')}</p>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('proposalPreview').innerHTML = previewHTML;
    });

    // Helper: number to words (Indian system)
    function convertNumberToWords(num) {
        const a = ['','One ','Two ','Three ','Four ', 'Five ','Six ','Seven ','Eight ','Nine ','Ten ','Eleven ','Twelve ','Thirteen ','Fourteen ','Fifteen ','Sixteen ','Seventeen ','Eighteen ','Nineteen '];
        const b = ['', '', 'Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety'];

        if (!num || isNaN(num)) return '';
        num = parseInt(num);
        if (num === 0) return 'Zero';
        if (num < 20) return a[num];
        if (num < 100) return b[Math.floor(num/10)] + ' ' + a[num%10];
        if (num < 1000) return a[Math.floor(num/100)] + 'Hundred ' + convertNumberToWords(num%100);
        if (num < 100000) return convertNumberToWords(Math.floor(num/1000)) + 'Thousand ' + convertNumberToWords(num%1000);
        if (num < 10000000) return convertNumberToWords(Math.floor(num/100000)) + 'Lakh ' + convertNumberToWords(num%100000);
        return convertNumberToWords(Math.floor(num/10000000)) + 'Crore ' + convertNumberToWords(num%10000000);
    }

    // Form validation to enforce 100%
    document.getElementById('appWebsiteForm').addEventListener('submit', function(e) {
        Object.values(CKEDITOR.instances).forEach(instance => instance.updateElement());
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
