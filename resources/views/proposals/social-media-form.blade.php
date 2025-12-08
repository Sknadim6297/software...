@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-share-alt me-2"></i>
                        Social Media Marketing Proposal
                    </h4>
                    <p class="mb-0 text-muted">Create a professional social media marketing proposal for {{ $lead->customer_name }}</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('proposals.store-social-media') }}" method="POST" id="socialMediaForm">
                        @csrf
                        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                        <input type="hidden" name="lead_type" value="{{ $leadType }}">
                        <input type="hidden" name="project_type" value="social_media_marketing">
                        
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
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="company_name" 
                                               placeholder="e.g., Power Cab - Elevator Manufacturing Company" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Proposed Price (₹) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="monthly_charges" 
                                               value="{{ $lead->project_valuation ?? '' }}"
                                               placeholder="19000" min="1000" required>
                                        @if($lead->project_valuation)
                                            <small class="text-muted">Auto-fetched from lead valuation</small>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Platforms Covered <span class="text-danger">*</span></label>
                                        <div class="platforms-grid">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="platforms[]" value="Facebook" id="facebook" checked>
                                                <label class="form-check-label" for="facebook">Facebook</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="platforms[]" value="Instagram" id="instagram" checked>
                                                <label class="form-check-label" for="instagram">Instagram</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="platforms[]" value="LinkedIn" id="linkedin">
                                                <label class="form-check-label" for="linkedin">LinkedIn</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="platforms[]" value="Twitter" id="twitter">
                                                <label class="form-check-label" for="twitter">Twitter</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Target Audience</label>
                                        <textarea class="form-control" name="target_audience" rows="4" 
                                                  placeholder="e.g., businesses, builders, architects, developers, institutions"></textarea>
                                        <small class="text-muted">Optional — leave blank if not specified</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Creation -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-image me-2"></i>Content Creation & Posting
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label class="form-label">Posters per Month <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="posters_per_month" 
                                               placeholder="30" min="1" required>
                                        <small class="text-muted">Static/Carousel/Infographic posts</small>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label class="form-label">Reels per Week <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="reels_per_week" 
                                               placeholder="2" min="0" required>
                                        <small class="text-muted">Product-focused, testimonial, behind-the-scenes</small>
                                    </div>
                                    <div class="col-lg-4 col-md-12 mb-3">
                                        <label class="form-label">Video Editing</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="includes_video_editing" value="1" id="video_editing" checked>
                                            <label class="form-check-label" for="video_editing">
                                                <strong>Include Video Editing Support</strong><br>
                                                <small class="text-muted">Client videos professionally edited and optimized</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Services Included -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-cogs me-2"></i>Services Included
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="platform_management" id="platform_mgmt" checked>
                                            <label class="form-check-label" for="platform_mgmt">Platform Management & Optimization</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="daily_posting" id="daily_posting" checked>
                                            <label class="form-check-label" for="daily_posting">Daily Posting & Caption Writing</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="hashtag_research" id="hashtag_research" checked>
                                            <label class="form-check-label" for="hashtag_research">Hashtag Research & Implementation</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="story_management" id="story_mgmt" checked>
                                            <label class="form-check-label" for="story_mgmt">Profile Highlights & Story Management</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="lead_generation" id="lead_gen" checked>
                                            <label class="form-check-label" for="lead_gen">Lead Generation Setup & Monitoring</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="paid_ads" id="paid_ads" checked>
                                            <label class="form-check-label" for="paid_ads">Paid Ad Management</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="ad_creative" id="ad_creative" checked>
                                            <label class="form-check-label" for="ad_creative">Ad Creative Designs</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="monthly_reports" id="monthly_reports" checked>
                                            <label class="form-check-label" for="monthly_reports">Monthly Performance Reports</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Terms & Additional Details -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-credit-card me-2"></i>Payment Terms & Additional Details
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Payment Mode</label>
                                        <select class="form-control" name="payment_mode">
                                            <option value="bank_transfer_upi">Bank Transfer / UPI</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="upi">UPI</option>
                                            <option value="cheque">Cheque</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">GST</label>
                                        <select class="form-control" name="gst_applicable">
                                            <option value="additional">Additional as applicable</option>
                                            <option value="included">Included in price</option>
                                            <option value="not_applicable">Not Applicable</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Additional Notes</label>
                                        <textarea class="form-control" name="additional_notes" rows="3" 
                                                  placeholder="Any special requirements, ad budget notes, or custom terms..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex flex-wrap justify-content-between gap-2">
                                    <div>
                                        <a href="{{ route('proposals.select-customer', ['lead_type' => $leadType]) }}" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left me-1"></i> Back to Customer Selection
                                        </a>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-info me-2" onclick="previewProposal()">
                                            <i class="fa fa-eye me-1"></i> Preview Proposal
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-check me-1"></i> Generate & Send Proposal
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

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" style="z-index: 1055;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Social Media Marketing Proposal Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Preview content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" onclick="submitProposal()">
                    <i class="fa fa-paper-plane me-1"></i> Generate & Send Proposal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function previewProposal() {
    // Collect form data
    const form = document.getElementById('socialMediaForm');
    const formData = new FormData(form);
    
    // Build platforms list
    const platforms = [];
    document.querySelectorAll('input[name="platforms[]"]:checked').forEach(function(checkbox) {
        platforms.push(checkbox.value);
    });
    
    // Build services list
    const services = [];
    document.querySelectorAll('input[name="services[]"]:checked').forEach(function(checkbox) {
        services.push(checkbox.nextElementSibling.textContent);
    });
    
    // Generate preview content
    const companyName = formData.get('company_name') || '{{ $lead->customer_name }}';
    const proposedPrice = formData.get('monthly_charges') || '19000';
    const postersPerMonth = formData.get('posters_per_month') || '30';
    const reelsPerWeek = formData.get('reels_per_week') || '2';
    const targetAudience = formData.get('target_audience') || 'businesses, builders, and architects';
    
    const previewHTML = `
        <div class="proposal-preview" style="font-family: Arial, sans-serif; max-width: 800px;">
            <div class="text-center mb-4">
                <h2 class="text-primary">Social Media Marketing Proposal</h2>
                <h4>Scope of Work & Marketing Strategy</h4>
                <h5>For ${companyName}</h5>
                <p class="text-muted">Submitted by: Konnectix Technologies Pvt. Ltd.</p>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="text-success">Platform Management</h5>
                    <ul>
                        <li>Page/Profile optimization on all platforms</li>
                        <li>Daily posting and engaging caption writing</li>
                        <li>Hashtag research and implementation</li>
                        <li>Profile highlights and story management</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5 class="text-info">Lead Generation</h5>
                    <ul>
                        <li>Use of Meta Lead Forms and Landing Pages</li>
                        <li>Capturing inquiries from ${targetAudience}</li>
                        <li>Weekly lead reports with follow-up tracking support</li>
                    </ul>
                </div>
            </div>
            
            <div class="mb-4">
                <h5 class="text-warning">Paid Ad Management</h5>
                <p>Strategic ad campaigns for:</p>
                <ul>
                    <li>Lead Generation (targeting ${targetAudience})</li>
                    <li>Page Likes & Followers Growth</li>
                    <li>A/B Testing of creatives & audience targeting</li>
                    <li>Real-time ad monitoring and optimization for ROI</li>
                </ul>
            </div>
            
            <div class="mb-4">
                <h5 class="text-primary">Platforms Covered: ${platforms.join(' & ')}</h5>
            </div>
            
            <div class="mb-4">
                <h5>Content Creation & Posting</h5>
                <ul>
                    <li>${postersPerMonth} Posters per Month (Static/Carousel/Infographic based on marketing objective)</li>
                    <li>${reelsPerWeek} Reels per Week (Product-focused, testimonial, behind-the-scenes, etc.)</li>
                    ${formData.get('includes_video_editing') ? '<li>Video Editing Support: Any video content shared by your team will be professionally edited and optimized for social media.</li>' : ''}
                </ul>
            </div>
            
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead class="table-primary">
                        <tr><th>Deliverables</th><th>Details</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Posters per month</td><td>${postersPerMonth} per month</td></tr>
                        <tr><td>Reels per Week</td><td>${Math.floor(reelsPerWeek * 4)}+ per month</td></tr>
                        <tr><td>Ad Creative Designs</td><td>Included</td></tr>
                        <tr><td>Video Editing</td><td>${formData.get('includes_video_editing') ? 'Included (Client video)' : 'Not included'}</td></tr>
                        <tr><td>Lead Generation Setup & Monitoring</td><td>Included</td></tr>
                        <tr><td>Page Management & Strategy</td><td>Included</td></tr>
                    </tbody>
                </table>
            </div>
            
            <div class="mb-4">
                <h5 class="text-success">Proposed Price</h5>
                <div class="alert alert-success">
                    <h4>Total Proposed Price: ₹${parseInt(proposedPrice).toLocaleString()}/-</h4>
                    <p class="mb-0">
                        <strong>Payment Mode:</strong> ${formData.get('payment_mode').replace('_', ' / ').toUpperCase()}<br>
                        <strong>GST:</strong> ${formData.get('gst_applicable').replace('_', ' ')}<br>
                        <strong>Advance Payment:</strong> One month in advance to initiate work
                    </p>
                </div>
            </div>
            
            <div class="mb-4">
                <h5>Growth Monitoring</h5>
                <ul>
                    <li>Monthly performance report (Reach, Engagement, Leads, Followers)</li>
                    <li>Strategy refinement based on insights</li>
                </ul>
            </div>
            
            <div class="alert alert-info mb-4">
                <h6>Note:</h6>
                <ul class="mb-0">
                    <li>Meta Ad budget (Facebook/Instagram Ads) is to be provided separately by the client.</li>
                    <li>Ads will be run through client's business manager/ad account for transparency.</li>
                    <li>All designs and edited content will be shared for approval before posting.</li>
                </ul>
            </div>
            
            <div class="text-center">
                <h4 class="text-primary">Let's Elevate Your Digital Presence!</h4>
                <p>For queries or approval, feel free to contact us.</p>
                <p><strong>Phone:</strong> +91 9123354003<br>
                <strong>Email:</strong> sales.konnectixtech@gmail.com<br>
                <strong>Website:</strong> www.konnectixtech.com</p>
                <p class="text-muted">We look forward to helping ${companyName} achieve digital marketing success!</p>
            </div>
        </div>
    `;
    
    document.getElementById('previewContent').innerHTML = previewHTML;
    var modal = new bootstrap.Modal(document.getElementById('previewModal'));
    modal.show();
}

function submitProposal() {
    // Hide preview modal
    var modal = bootstrap.Modal.getInstance(document.getElementById('previewModal'));
    if (modal) modal.hide();
    
    // Submit the form
    document.getElementById('socialMediaForm').submit();
}
</script>

<style>
/* Compact layout fixes */
.container-fluid {
    padding: 10px;
}

.card {
    margin-bottom: 1rem !important;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.card-body {
    padding: 1rem !important;
}

.card-header {
    padding: 0.75rem 1rem !important;
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.form-label {
    margin-bottom: 0.25rem !important;
    font-weight: 600;
    font-size: 0.9rem;
}

.form-control {
    margin-bottom: 0.25rem !important;
    padding: 0.5rem 0.75rem;
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

/* Compact row spacing */
.row {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
}

.row > * {
    padding-left: 0.5rem !important;
    padding-right: 0.5rem !important;
}

/* Compact form spacing */
.mb-3 {
    margin-bottom: 0.75rem !important;
}

.mb-4 {
    margin-bottom: 1rem !important;
}

/* Compact checkbox styling */
.form-check {
    margin-bottom: 0.25rem !important;
    padding-left: 1.5rem;
}

.form-check-label {
    margin-left: 0;
    font-size: 0.9rem;
}

.form-check-input {
    margin-top: 0.125em;
}

/* Platforms grid layout */
.platforms-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.25rem;
}

/* Small text styling */
small.text-muted {
    font-size: 0.8rem;
    margin-top: 0.25rem;
    display: block;
}

/* Textarea compact */
textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

/* Button responsive styling */
.d-flex.flex-wrap {
    gap: 0.5rem;
}

.btn {
    margin-bottom: 0.25rem;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .container-fluid {
        padding: 5px;
    }
    
    .card-body {
        padding: 0.75rem !important;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.25rem;
    }
    
    .col-md-6, .col-lg-4 {
        margin-bottom: 0.75rem;
    }
    
    .platforms-grid {
        grid-template-columns: 1fr;
    }
}

/* Proposal preview styling */
.proposal-preview {
    padding: 15px;
    background: white;
    font-family: Arial, sans-serif;
}

.proposal-preview h2, .proposal-preview h4, .proposal-preview h5 {
    margin-bottom: 10px;
}

.proposal-preview ul {
    padding-left: 20px;
    margin-bottom: 10px;
}

.proposal-preview .table th {
    background-color: #f8f9fa;
    font-weight: 600;
    padding: 0.5rem;
}

.proposal-preview .table td {
    padding: 0.5rem;
}

/* Modal responsiveness */
@media (max-width: 768px) {
    .modal-xl {
        max-width: 95%;
    }
}

@media print {
    .modal-header, .modal-footer {
        display: none;
    }
}

/* Card title icons */
.card-title i {
    color: #0d6efd;
}

/* Remove default Bootstrap spacing overrides */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Override Bootstrap margins */
.mb-0 {
    margin-bottom: 0 !important;
}

/* Compact select styling */
select.form-control {
    padding: 0.5rem 0.75rem;
}
</style>
@endsection