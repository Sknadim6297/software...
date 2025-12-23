@extends('layouts.app')

@section('content')
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
                        
                        <!-- Project Details -->
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
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Total Project Cost (₹) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="total_cost" id="totalCost"
                                               value="{{ $lead->project_valuation ?? 7000 }}"
                                               placeholder="7000" min="1000" required>
                                        @if($lead->project_valuation)
                                            <small class="text-muted">Auto-fetched from lead valuation</small>
                                        @endif
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Estimated Timeline <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="timeline" 
                                               value="3 working days" placeholder="e.g., 3 working days" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Scope of Work -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-tasks me-2"></i>Scope of Work
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Development Services (Check all that apply)</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="services[]" 
                                                       value="Design and development of website/app" id="service1" checked>
                                                <label class="form-check-label" for="service1">
                                                    Design and development of website/app
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="services[]" 
                                                       value="Mobile-responsive design" id="service2" checked>
                                                <label class="form-check-label" for="service2">
                                                    Mobile-responsive design (desktop, tablet, mobile)
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="services[]" 
                                                       value="Product listing and detail pages" id="service3">
                                                <label class="form-check-label" for="service3">
                                                    Product listing and detail pages
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="services[]" 
                                                       value="Shopping cart and checkout" id="service4">
                                                <label class="form-check-label" for="service4">
                                                    Shopping cart and checkout functionality
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="services[]" 
                                                       value="Payment gateway integration" id="service5">
                                                <label class="form-check-label" for="service5">
                                                    Payment gateway integration
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="services[]" 
                                                       value="Admin panel" id="service6">
                                                <label class="form-check-label" for="service6">
                                                    Admin panel for managing products/orders
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="services[]" 
                                                       value="Testing and deployment" id="service7" checked>
                                                <label class="form-check-label" for="service7">
                                                    Website/App testing and deployment
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="services[]" 
                                                       value="SEO optimization" id="service8">
                                                <label class="form-check-label" for="service8">
                                                    SEO optimization
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Additional Features/Services</label>
                                    <textarea class="form-control" name="additional_features" rows="3" 
                                              placeholder="Add any custom features or services beyond the above scope"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Terms -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-rupee-sign me-2"></i>Payment Terms
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Upfront Payment (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="upfront_percentage" 
                                               id="upfrontPercentage" value="30" min="0" max="100" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Upfront Amount (₹)</label>
                                        <input type="number" class="form-control" name="upfront_amount" 
                                               id="upfrontAmount" readonly>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Final Payment (%) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="final_percentage" 
                                               id="finalPercentage" value="70" min="0" max="100" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Final Amount (₹)</label>
                                        <input type="number" class="form-control" name="final_amount" 
                                               id="finalAmount" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Payment Note</label>
                                        <input type="text" class="form-control" name="payment_note" 
                                               value="Website shall not be made live until full payment is received">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Domain & Hosting -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-server me-2"></i>Domain & Hosting
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Domain Name Provided By</label>
                                        <select class="form-control" name="domain_provided_by">
                                            <option value="Client">Client</option>
                                            <option value="Service Provider">Service Provider</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Hosting Duration</label>
                                        <input type="text" class="form-control" name="hosting_duration" 
                                               value="One (1) year provided by Service Provider">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Warranty & Support -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-shield-alt me-2"></i>Warranty & Support
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Free Support Period</label>
                                        <input type="text" class="form-control" name="support_period" 
                                               value="7 days free bug support after delivery">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Post-Support Charges</label>
                                        <input type="text" class="form-control" name="post_support_charges" 
                                               value="Future updates/maintenance shall be chargeable separately">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Client Responsibilities -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-user-check me-2"></i>Client Responsibilities
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="client_responsibilities[]" 
                                           value="Provide content, images, branding materials" id="resp1" checked>
                                    <label class="form-check-label" for="resp1">
                                        Provide all necessary content, product details, images, and branding materials
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="client_responsibilities[]" 
                                           value="Timely approvals and feedback" id="resp2" checked>
                                    <label class="form-check-label" for="resp2">
                                        Ensure timely approvals and feedback
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="client_responsibilities[]" 
                                           value="Bear third-party charges" id="resp3" checked>
                                    <label class="form-check-label" for="resp3">
                                        Bear any third-party charges such as payment gateway fees
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Terms -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-file-contract me-2"></i>Additional Terms
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Additional Notes/Terms</label>
                                    <textarea class="form-control" name="additional_terms" rows="3" 
                                              placeholder="Any additional terms, conditions, or notes"></textarea>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculate payment amounts
    function calculatePayments() {
        const totalCost = parseFloat(document.getElementById('totalCost').value) || 0;
        const upfrontPercentage = parseFloat(document.getElementById('upfrontPercentage').value) || 0;
        const finalPercentage = 100 - upfrontPercentage;
        
        const upfrontAmount = Math.round(totalCost * upfrontPercentage / 100);
        const finalAmount = totalCost - upfrontAmount;
        
        document.getElementById('upfrontAmount').value = upfrontAmount;
        document.getElementById('finalAmount').value = finalAmount;
        document.getElementById('finalPercentage').value = finalPercentage;
    }
    
    // Initial calculation
    calculatePayments();
    
    // Recalculate on changes
    document.getElementById('totalCost').addEventListener('input', calculatePayments);
    document.getElementById('upfrontPercentage').addEventListener('input', calculatePayments);
    
    // Generate Preview
    document.getElementById('generatePreviewBtn').addEventListener('click', function() {
        const form = document.getElementById('appWebsiteForm');
        const formData = new FormData(form);
        
        // Get all checked services
        const services = [];
        document.querySelectorAll('input[name="services[]"]:checked').forEach(function(checkbox) {
            services.push(checkbox.nextElementSibling.textContent.trim());
        });
        
        // Get all checked client responsibilities
        const clientResponsibilities = [];
        document.querySelectorAll('input[name="client_responsibilities[]"]:checked').forEach(function(checkbox) {
            clientResponsibilities.push(checkbox.nextElementSibling.textContent.trim());
        });
        
        const projectTitle = formData.get('project_title') || 'WEBSITE DEVELOPMENT';
        const totalCost = formData.get('total_cost') || '7000';
        const timeline = formData.get('timeline') || '3 working days';
        const upfrontPercentage = formData.get('upfront_percentage') || '30';
        const upfrontAmount = formData.get('upfront_amount') || '0';
        const finalPercentage = formData.get('final_percentage') || '70';
        const finalAmount = formData.get('final_amount') || '0';
        
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
                    <h5 class="text-success">1. PURPOSE OF THE AGREEMENT</h5>
                    <p>The purpose of this Agreement is to define the terms and conditions under which the Service Provider shall design and develop a ${projectTitle.toLowerCase()} for the Client.</p>
                </div>
                
                <div class="mb-4">
                    <h5 class="text-success">2. SCOPE OF WORK</h5>
                    <p>The Service Provider agrees to provide the following services:</p>
                    <ul>
                        ${services.map(service => `<li>${service}</li>`).join('')}
                    </ul>
                    ${formData.get('additional_features') ? `<p><strong>Additional Features:</strong> ${formData.get('additional_features')}</p>` : ''}
                    <p class="alert alert-info">Any features or changes beyond the above scope shall be considered additional work and charged separately upon mutual agreement.</p>
                </div>
                
                <div class="mb-4">
                    <h5 class="text-success">3. PROJECT TIMELINE</h5>
                    <ul>
                        <li>The project shall commence after receipt of the upfront payment and required materials from the Client</li>
                        <li>Estimated project completion timeline: <strong>${timeline}</strong></li>
                        <li>Any delay due to late content, approvals, or feedback from the Client shall extend the timeline accordingly</li>
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h5 class="text-success">4. FEES & PAYMENT TERMS</h5>
                    <ul>
                        <li><strong>Total Project Cost:</strong> ₹${parseInt(totalCost).toLocaleString()} (Rupees ${convertNumberToWords(totalCost)} Only)</li>
                        <li><strong>Upfront Payment:</strong> ${upfrontPercentage}% of the total amount (₹${parseInt(upfrontAmount).toLocaleString()}) payable before commencement of work</li>
                        <li><strong>Final Payment:</strong> ${finalPercentage}% of the total amount (₹${parseInt(finalAmount).toLocaleString()}) payable after completion and before the website/app goes live</li>
                        <li class="text-danger">${formData.get('payment_note')}</li>
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h5 class="text-success">5. DOMAIN & HOSTING</h5>
                    <ul>
                        <li>The domain name shall be provided by the <strong>${formData.get('domain_provided_by')}</strong></li>
                        <li>${formData.get('hosting_duration')}</li>
                        <li>The Service Provider shall not be responsible for delays caused due to domain-related issues from the Client's end</li>
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h5 class="text-success">6. CLIENT RESPONSIBILITIES</h5>
                    <p>The Client agrees to:</p>
                    <ul>
                        ${clientResponsibilities.map(resp => `<li>${resp}</li>`).join('')}
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h5 class="text-success">7. INTELLECTUAL PROPERTY RIGHTS</h5>
                    <ul>
                        <li>Ownership of the website/app and related files shall be transferred to the Client only after full payment</li>
                        <li>The Service Provider retains the right to display the completed project in its portfolio unless restricted in writing</li>
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h5 class="text-success">8. WARRANTY & SUPPORT</h5>
                    <ul>
                        <li>${formData.get('support_period')}</li>
                        <li>${formData.get('post_support_charges')}</li>
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h5 class="text-success">9. TERMINATION</h5>
                    <ul>
                        <li>Either Party may terminate this Agreement with written notice</li>
                        <li>Payments made shall be non-refundable</li>
                        <li>In case of termination after project commencement, the upfront payment shall be forfeited</li>
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h5 class="text-success">10. LIMITATION OF LIABILITY</h5>
                    <p>The Service Provider shall not be liable for:</p>
                    <ul>
                        <li>Downtime or failure caused by third-party services including domain registrars and payment gateways</li>
                        <li>Any indirect loss of business, revenue, or data</li>
                    </ul>
                </div>
                
                <div class="mb-4">
                    <h5 class="text-success">11. GOVERNING LAW & JURISDICTION</h5>
                    <p>This Agreement shall be governed by and construed in accordance with the laws of India, and courts of Kolkata shall have exclusive jurisdiction.</p>
                </div>
                
                <div class="mb-4">
                    <h5 class="text-success">12. ENTIRE AGREEMENT</h5>
                    <p>This Agreement constitutes the entire understanding between the Parties and supersedes all prior discussions or communications.</p>
                </div>
                
                ${formData.get('additional_terms') ? `
                <div class="mb-4">
                    <h5 class="text-success">13. ADDITIONAL TERMS</h5>
                    <p>${formData.get('additional_terms')}</p>
                </div>
                ` : ''}
                
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
    
    // Helper function to convert number to words (Indian system)
    function convertNumberToWords(num) {
        const a = ['','One ','Two ','Three ','Four ', 'Five ','Six ','Seven ','Eight ','Nine ','Ten ','Eleven ','Twelve ','Thirteen ','Fourteen ','Fifteen ','Sixteen ','Seventeen ','Eighteen ','Nineteen '];
        const b = ['', '', 'Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety'];
        
        if (num === 0) return 'Zero';
        
        num = parseInt(num);
        
        if (num < 20) return a[num];
        if (num < 100) return b[Math.floor(num/10)] + ' ' + a[num%10];
        if (num < 1000) return a[Math.floor(num/100)] + 'Hundred ' + convertNumberToWords(num%100);
        if (num < 100000) return convertNumberToWords(Math.floor(num/1000)) + 'Thousand ' + convertNumberToWords(num%1000);
        if (num < 10000000) return convertNumberToWords(Math.floor(num/100000)) + 'Lakh ' + convertNumberToWords(num%100000);
        return convertNumberToWords(Math.floor(num/10000000)) + 'Crore ' + convertNumberToWords(num%10000000);
    }
});
</script>
@endpush
@endsection
