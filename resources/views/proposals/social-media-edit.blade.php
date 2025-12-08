@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-edit me-2"></i>
                        Edit Social Media Marketing Proposal
                    </h4>
                    <p class="mb-0 text-muted">Edit proposal for {{ $lead->customer_name }}</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('proposals.update', $proposal->id) }}" method="POST" id="socialMediaForm">
                        @csrf
                        @method('PUT')
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
                                               value="{{ $metadata['company_name'] ?? '' }}"
                                               placeholder="e.g., Power Cab - Elevator Manufacturing Company" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Proposed Price (₹) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="monthly_charges" 
                                               value="{{ $proposal->proposed_amount }}"
                                               placeholder="19000" min="1000" required>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Platforms Covered <span class="text-danger">*</span></label>
                                        <div class="platforms-grid">
                                            @php
                                                $selectedPlatforms = $metadata['platforms'] ?? ['Facebook', 'Instagram'];
                                            @endphp
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="platforms[]" value="Facebook" id="facebook" 
                                                    {{ in_array('Facebook', $selectedPlatforms) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="facebook">Facebook</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="platforms[]" value="Instagram" id="instagram"
                                                    {{ in_array('Instagram', $selectedPlatforms) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="instagram">Instagram</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="platforms[]" value="LinkedIn" id="linkedin"
                                                    {{ in_array('LinkedIn', $selectedPlatforms) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="linkedin">LinkedIn</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="platforms[]" value="Twitter" id="twitter"
                                                    {{ in_array('Twitter', $selectedPlatforms) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="twitter">Twitter</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Target Audience</label>
                                        <textarea class="form-control" name="target_audience" rows="4" 
                                                  placeholder="e.g., businesses, builders, architects, developers, institutions">{{ $metadata['target_audience'] ?? '' }}</textarea>
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
                                               value="{{ $metadata['posters_per_month'] ?? 30 }}"
                                               placeholder="30" min="1" required>
                                        <small class="text-muted">Static/Carousel/Infographic posts</small>
                                    </div>
                                    <div class="col-lg-4 col-md-6 mb-3">
                                        <label class="form-label">Reels per Week <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="reels_per_week" 
                                               value="{{ $metadata['reels_per_week'] ?? 2 }}"
                                               placeholder="2" min="0" required>
                                        <small class="text-muted">Product-focused, testimonial, behind-the-scenes</small>
                                    </div>
                                    <div class="col-lg-4 col-md-12 mb-3">
                                        <label class="form-label">Video Editing</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="includes_video_editing" value="1" id="video_editing"
                                                {{ !empty($metadata['includes_video_editing']) ? 'checked' : '' }}>
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
                                @php
                                    $selectedServices = $metadata['services'] ?? [];
                                @endphp
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="platform_management" id="platform_mgmt"
                                                {{ in_array('platform_management', $selectedServices) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="platform_mgmt">Platform Management & Optimization</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="daily_posting" id="daily_posting"
                                                {{ in_array('daily_posting', $selectedServices) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="daily_posting">Daily Posting & Caption Writing</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="hashtag_research" id="hashtag_research"
                                                {{ in_array('hashtag_research', $selectedServices) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="hashtag_research">Hashtag Research & Implementation</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="story_management" id="story_mgmt"
                                                {{ in_array('story_management', $selectedServices) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="story_mgmt">Profile Highlights & Story Management</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="lead_generation" id="lead_gen"
                                                {{ in_array('lead_generation', $selectedServices) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="lead_gen">Lead Generation Setup & Monitoring</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="paid_ads" id="paid_ads"
                                                {{ in_array('paid_ads', $selectedServices) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="paid_ads">Paid Ad Management</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="ad_creative" id="ad_creative"
                                                {{ in_array('ad_creative', $selectedServices) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="ad_creative">Ad Creative Designs</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="services[]" value="monthly_reports" id="monthly_reports"
                                                {{ in_array('monthly_reports', $selectedServices) ? 'checked' : '' }}>
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
                                            <option value="bank_transfer_upi" {{ ($metadata['payment_mode'] ?? '') == 'bank_transfer_upi' ? 'selected' : '' }}>Bank Transfer / UPI</option>
                                            <option value="bank_transfer" {{ ($metadata['payment_mode'] ?? '') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                            <option value="upi" {{ ($metadata['payment_mode'] ?? '') == 'upi' ? 'selected' : '' }}>UPI</option>
                                            <option value="cheque" {{ ($metadata['payment_mode'] ?? '') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">GST</label>
                                        <select class="form-control" name="gst_applicable">
                                            <option value="additional" {{ ($metadata['gst_applicable'] ?? '') == 'additional' ? 'selected' : '' }}>Additional as applicable</option>
                                            <option value="included" {{ ($metadata['gst_applicable'] ?? '') == 'included' ? 'selected' : '' }}>Included in price</option>
                                            <option value="not_applicable" {{ ($metadata['gst_applicable'] ?? '') == 'not_applicable' ? 'selected' : '' }}>Not Applicable</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Additional Notes</label>
                                        <textarea class="form-control" name="additional_notes" rows="3" 
                                                  placeholder="Any special requirements, ad budget notes, or custom terms...">{{ $metadata['additional_notes'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex flex-wrap justify-content-between gap-2">
                                    <div>
                                        <a href="{{ route('proposals.show', $proposal->id) }}" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left me-1"></i> Back to Proposal
                                        </a>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-save me-1"></i> Update Proposal
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

/* Compact checkbox styling */
.form-check {
    margin-bottom: 0.25rem !important;
    padding-left: 1.5rem;
}

.form-check-label {
    margin-left: 0;
    font-size: 0.9rem;
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

/* Button responsive styling */
.d-flex.flex-wrap {
    gap: 0.5rem;
}

.btn {
    margin-bottom: 0.25rem;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

/* Card title icons */
.card-title i {
    color: #0d6efd;
}
</style>
@endsection
