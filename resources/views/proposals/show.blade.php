@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm">
            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm">
            <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Modern Header Section -->
    <div class="proposal-header-section mb-4">
        <div class="card border-0 shadow-lg proposal-hero-card">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <div class="d-flex align-items-center mb-3">
                            <div class="proposal-icon-wrapper me-3">
                                <i class="fa fa-file-alt fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h2 class="mb-1 fw-bold">Proposal #{{ $proposal->id }}</h2>
                                <p class="text-muted mb-0">
                                    <i class="fa fa-briefcase me-2"></i>{{ $proposal->project_type }}
                                    <span class="mx-2">•</span>
                                    <i class="fa fa-user me-2"></i>{{ $proposal->customer_name }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Enhanced Status Badge -->
                        <div class="status-badge-wrapper">
                            <span class="badge badge-{{ $proposal->getStatusBadgeColor() }} status-badge-modern">
                                <i class="fa fa-circle me-2 pulse-animation"></i>
                                {{ ucfirst(str_replace('_', ' ', $proposal->status)) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 text-end">
                        <div class="action-buttons-group">
                            @if($proposal->status === 'draft')
                                <form action="{{ route('proposals.send', $proposal->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-modern" onclick="return confirm('Send this proposal to customer?')">
                                        <i class="fa fa-paper-plane me-2"></i> Send Proposal
                                    </button>
                                </form>
                                <a href="{{ route('proposals.edit', $proposal->id) }}" class="btn btn-warning btn-modern">
                                    <i class="fa fa-edit me-2"></i> Edit
                                </a>
                            @endif
                            
                            @if($proposal->status === 'sent' || $proposal->status === 'viewed')
                                <button type="button" class="btn btn-success btn-modern" data-bs-toggle="modal" data-bs-target="#acceptModal">
                                    <i class="fa fa-check-circle me-2"></i> Accept
                                </button>
                                <button type="button" class="btn btn-danger btn-modern" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fa fa-times-circle me-2"></i> Reject
                                </button>
                            @endif

                            <a href="{{ route('proposals.index') }}" class="btn btn-secondary btn-modern">
                                <i class="fa fa-arrow-left me-2"></i> Back
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Modern Progress Timeline -->
                <div class="proposal-timeline mt-4">
                    <div class="timeline-container">
                        <div class="timeline-step {{ in_array($proposal->status, ['draft', 'sent', 'viewed', 'accepted', 'rejected']) ? 'completed' : '' }}">
                            <div class="timeline-icon">
                                <i class="fa fa-edit"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-label">Draft</div>
                                <div class="timeline-date">{{ $proposal->created_at->format('M d') }}</div>
                            </div>
                        </div>
                        
                        <div class="timeline-connector {{ in_array($proposal->status, ['sent', 'viewed', 'accepted', 'rejected']) ? 'active' : '' }}"></div>
                        
                        <div class="timeline-step {{ in_array($proposal->status, ['sent', 'viewed', 'accepted', 'rejected']) ? 'completed' : '' }}">
                            <div class="timeline-icon">
                                <i class="fa fa-paper-plane"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-label">Sent</div>
                                <div class="timeline-date">{{ $proposal->sent_at ? $proposal->sent_at->format('M d') : '-' }}</div>
                            </div>
                        </div>
                        
                        <div class="timeline-connector {{ in_array($proposal->status, ['viewed', 'accepted', 'rejected']) ? 'active' : '' }}"></div>
                        
                        <div class="timeline-step {{ in_array($proposal->status, ['viewed', 'accepted', 'rejected']) ? 'completed' : '' }}">
                            <div class="timeline-icon">
                                <i class="fa fa-eye"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-label">Viewed</div>
                                <div class="timeline-date">{{ $proposal->viewed_at ? $proposal->viewed_at->format('M d') : '-' }}</div>
                            </div>
                        </div>
                        
                        <div class="timeline-connector {{ $proposal->status === 'accepted' ? 'active' : '' }}"></div>
                        
                        <div class="timeline-step {{ $proposal->status === 'accepted' ? 'completed' : ($proposal->status === 'rejected' ? 'rejected' : '') }}">
                            <div class="timeline-icon">
                                <i class="fa fa-{{ $proposal->status === 'rejected' ? 'times' : 'check' }}"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-label">{{ $proposal->status === 'rejected' ? 'Rejected' : 'Accepted' }}</div>
                                <div class="timeline-date">{{ $proposal->responded_at ? $proposal->responded_at->format('M d') : '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4 mb-5 pb-4">
        <!-- Left Sidebar -->
        <div class="col-xl-4">
            <!-- Customer Card -->
            <div class="card border-0 shadow-sm info-card customer-card">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <h5 class="mb-0"><i class="fa fa-user-circle me-2"></i>Customer Details</h5>
                </div>
                <div class="card-body p-4">
                    <div class="customer-info-item">
                        <div class="info-icon bg-primary">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="info-details">
                            <label>Full Name</label>
                            <p>{{ $proposal->customer_name }}</p>
                        </div>
                    </div>
                    
                    <div class="customer-info-item">
                        <div class="info-icon bg-info">
                            <i class="fa fa-envelope"></i>
                        </div>
                        <div class="info-details">
                            <label>Email Address</label>
                            <p>{{ $proposal->customer_email }}</p>
                        </div>
                    </div>
                    
                    <div class="customer-info-item">
                        <div class="info-icon bg-success">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="info-details">
                            <label>Phone Number</label>
                            <p>{{ $proposal->customer_phone }}</p>
                        </div>
                    </div>
                    
                    <div class="customer-info-item mb-0">
                        <div class="info-icon bg-warning">
                            <i class="fa fa-tag"></i>
                        </div>
                        <div class="info-details">
                            <label>Lead Type</label>
                            <p><span class="badge bg-gradient-info">{{ ucfirst($proposal->lead_type) }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Summary Card -->
            <div class="card border-0 shadow-sm info-card project-summary-card">
                <div class="card-header bg-gradient-success text-white border-0">
                    <h5 class="mb-0"><i class="fa fa-project-diagram me-2"></i>Project Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="summary-stat-item">
                        <div class="stat-icon">
                            <i class="fa fa-briefcase"></i>
                        </div>
                        <div>
                            <label>Project Type</label>
                            <h6>{{ $proposal->project_type }}</h6>
                        </div>
                    </div>
                    
                    <div class="summary-stat-item amount-highlight">
                        <div class="stat-icon">
                            <i class="fa fa-dollar-sign"></i>
                        </div>
                        <div>
                            <label>Proposed Amount</label>
                            <h4 class="text-success mb-0">{{ $proposal->currency }} {{ number_format($proposal->proposed_amount, 2) }}</h4>
                        </div>
                    </div>
                    
                    @if($proposal->estimated_days)
                        <div class="summary-stat-item">
                            <div class="stat-icon">
                                <i class="fa fa-clock"></i>
                            </div>
                            <div>
                                <label>Estimated Duration</label>
                                <h6>{{ $proposal->estimated_days }} days</h6>
                            </div>
                        </div>
                    @endif
                    
                    @if($proposal->payment_terms)
                        <div class="summary-stat-item mb-0">
                            <div class="stat-icon">
                                <i class="fa fa-credit-card"></i>
                            </div>
                            <div>
                                <label>Payment Terms</label>
                                <p class="mb-0">{{ $proposal->payment_terms }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline Card -->
            <div class="card border-0 shadow-sm info-card timeline-card">
                <div class="card-header bg-gradient-info text-white border-0">
                    <h5 class="mb-0"><i class="fa fa-history me-2"></i>Activity Timeline</h5>
                </div>
                <div class="card-body p-4">
                    <div class="activity-timeline">
                        <div class="activity-item">
                            <div class="activity-dot bg-primary"></div>
                            <div class="activity-content">
                                <label>Created</label>
                                <p>{{ $proposal->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($proposal->sent_at)
                            <div class="activity-item">
                                <div class="activity-dot bg-info"></div>
                                <div class="activity-content">
                                    <label>Sent to Customer</label>
                                    <p>{{ $proposal->sent_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($proposal->viewed_at)
                            <div class="activity-item">
                                <div class="activity-dot bg-warning"></div>
                                <div class="activity-content">
                                    <label>Viewed by Customer</label>
                                    <p>{{ $proposal->viewed_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if($proposal->responded_at)
                            <div class="activity-item mb-0">
                                <div class="activity-dot bg-{{ $proposal->status === 'accepted' ? 'success' : 'danger' }}"></div>
                                <div class="activity-content">
                                    <label>{{ $proposal->status === 'accepted' ? 'Accepted' : 'Rejected' }}</label>
                                    <p>{{ $proposal->responded_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Content Area -->
        <div class="col-xl-8">
            <!-- Proposal Content Card -->
            <div class="card border-0 shadow-sm content-card">
                <div class="card-header bg-gradient-dark text-white border-0">
                    <h5 class="mb-0"><i class="fa fa-file-contract me-2"></i>Proposal Content</h5>
                </div>
                <div class="card-body p-4">
                    @if($proposal->project_description)
                        <div class="content-section">
                            <div class="section-header">
                                <i class="fa fa-align-left text-primary"></i>
                                <h6>Project Description</h6>
                            </div>
                            <div class="section-content">
                                <p>{{ $proposal->project_description }}</p>
                            </div>
                        </div>
                    @endif

                    @if($proposal->deliverables)
                        <div class="content-section">
                            <div class="section-header">
                                <i class="fa fa-tasks text-success"></i>
                                <h6>Deliverables</h6>
                            </div>
                            <div class="section-content">
                                <p style="white-space: pre-line;">{{ $proposal->deliverables }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="content-section mb-0">
                        <div class="section-header">
                            <i class="fa fa-file-alt text-info"></i>
                            <h6>Full Proposal</h6>
                        </div>
                        <div class="section-content proposal-full-content">
                            <pre>{{ $proposal->proposal_content }}</pre>
                        </div>
                    </div>

                    @if($proposal->rejection_reason)
                        <div class="rejection-notice mt-4">
                            <div class="rejection-header">
                                <i class="fa fa-exclamation-triangle"></i>
                                <h6>Rejection Reason</h6>
                            </div>
                            <div class="rejection-content">
                                {{ $proposal->rejection_reason }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($proposal->contract)
                <div class="card border-0 shadow-lg contract-generated-card">
                    <div class="card-body p-4">
                        <div class="contract-success-content">
                            <div class="success-icon">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <div class="success-text">
                                <h4 class="mb-2">Contract Generated Successfully!</h4>
                                <p class="mb-4">This proposal has been accepted and a contract has been automatically generated.</p>
                                <a href="{{ route('contracts.show', $proposal->contract->id) }}" class="btn btn-success btn-lg btn-modern">
                                    <i class="fa fa-eye me-2"></i> View Contract
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Accept Proposal Modal -->
<div class="modal fade" id="acceptModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('proposals.accept', $proposal->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Accept Proposal & Generate Contract</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>What happens next:</strong>
                        <ul class="mb-0">
                            <li>✅ Contract will be automatically generated</li>
                            <li>✅ Invoice will be automatically created</li>
                            <li>✅ Customer will be added to Customer Management Portal</li>
                            <li>✅ Emails will be sent to customer and admin</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Final Amount <span class="text-danger">*</span></label>
                            <input type="number" name="final_amount" class="form-control" step="0.01" min="0" value="{{ $proposal->proposed_amount }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Expected Completion Date <span class="text-danger">*</span></label>
                        <input type="date" name="expected_completion_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deliverables</label>
                        <textarea name="deliverables" class="form-control" rows="3">{{ $proposal->deliverables }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Milestones (Optional)</label>
                        <textarea name="milestones" class="form-control" rows="3" placeholder="List project milestones..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Schedule</label>
                        <textarea name="payment_schedule" class="form-control" rows="2">{{ $proposal->payment_terms }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Terms & Conditions (Optional)</label>
                        <textarea name="terms_and_conditions" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="flaticon-381-check me-2"></i> Accept & Generate Contract
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Proposal Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('proposals.reject', $proposal->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Proposal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        The proposal will be marked as rejected but all data will be saved for future reference.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea name="rejection_reason" class="form-control" rows="4" placeholder="Please provide the reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="flaticon-381-close me-2"></i> Reject Proposal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Modern Proposal Show Page Styles */

/* Hero Card */
.proposal-hero-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px !important;
    overflow: hidden;
    position: relative;
}

.proposal-hero-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(30%, -30%);
}

.proposal-hero-card .card-body {
    position: relative;
    z-index: 1;
}

.proposal-hero-card h2,
.proposal-hero-card p {
    color: white !important;
}

.proposal-icon-wrapper {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

/* Status Badge */
.status-badge-modern {
    font-size: 16px;
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.pulse-animation {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* Action Buttons */
.btn-modern {
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin: 5px;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.action-buttons-group {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: flex-end;
}

/* Modern Timeline */
.proposal-timeline {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 30px;
    backdrop-filter: blur(10px);
}

.timeline-container {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    position: relative;
}

.timeline-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
}

.timeline-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    border: 3px solid rgba(255, 255, 255, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: rgba(255, 255, 255, 0.5);
    transition: all 0.4s ease;
    position: relative;
}

.timeline-step.completed .timeline-icon {
    background: rgba(255, 255, 255, 1);
    border-color: rgba(255, 255, 255, 1);
    color: #667eea;
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
}

.timeline-step.rejected .timeline-icon {
    background: rgba(220, 53, 69, 0.9);
    border-color: rgba(220, 53, 69, 1);
    color: white;
}

.timeline-content {
    margin-top: 15px;
    text-align: center;
}

.timeline-label {
    font-weight: 600;
    color: white;
    font-size: 14px;
    margin-bottom: 5px;
}

.timeline-date {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.7);
}

.timeline-connector {
    flex: 1;
    height: 3px;
    background: rgba(255, 255, 255, 0.2);
    margin: 30px 10px 0;
    position: relative;
    overflow: hidden;
}

.timeline-connector.active {
    background: rgba(255, 255, 255, 0.5);
}

.timeline-connector.active::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.8), transparent);
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

/* Info Cards */
.info-card {
    border-radius: 12px !important;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
}

.info-card .card-header {
    border-radius: 12px 12px 0 0 !important;
    padding: 15px 20px;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
}

.bg-gradient-dark {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%) !important;
}

/* Customer Info Items */
.customer-info-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.customer-info-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.info-icon {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 15px;
    flex-shrink: 0;
}

.info-details label {
    font-size: 12px;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 5px;
    display: block;
}

.info-details p {
    margin: 0;
    font-weight: 600;
    color: #2c3e50;
}

/* Project Summary Stats */
.summary-stat-item {
    display: flex;
    align-items: center;
    padding: 15px;
    margin-bottom: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #667eea;
    transition: all 0.3s ease;
}

.summary-stat-item:hover {
    background: #e9ecef;
    border-left-width: 6px;
}

.summary-stat-item.amount-highlight {
    background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%);
    border-left-color: #11998e;
    box-shadow: 0 4px 15px rgba(17, 153, 142, 0.2);
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: white;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 20px;
    color: #667eea;
}

.summary-stat-item label {
    font-size: 12px;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 5px;
    display: block;
}

.summary-stat-item h6,
.summary-stat-item h4 {
    margin: 0;
    color: #2c3e50;
}

/* Activity Timeline */
.activity-timeline {
    position: relative;
    padding-left: 30px;
}

.activity-timeline::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
}

.activity-item {
    position: relative;
    margin-bottom: 25px;
}

.activity-dot {
    position: absolute;
    left: -26px;
    top: 5px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.activity-content label {
    font-size: 13px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 3px;
    display: block;
}

.activity-content p {
    font-size: 12px;
    color: #6c757d;
    margin: 0;
}

/* Content Sections */
.content-section {
    margin-bottom: 30px;
    padding-bottom: 30px;
    border-bottom: 2px solid #f0f0f0;
}

.content-section:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.section-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.section-header i {
    font-size: 24px;
    margin-right: 12px;
}

.section-header h6 {
    margin: 0;
    font-weight: 700;
    font-size: 18px;
    color: #2c3e50;
}

.section-content {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    border-left: 4px solid #667eea;
}

.section-content p {
    margin: 0;
    line-height: 1.8;
    color: #495057;
}

.proposal-full-content {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.proposal-full-content pre {
    white-space: pre-wrap;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    color: #2c3e50;
    line-height: 1.8;
}

/* Rejection Notice */
.rejection-notice {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    border-radius: 12px;
    padding: 25px;
    color: white;
    box-shadow: 0 8px 25px rgba(238, 90, 111, 0.3);
}

.rejection-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.rejection-header i {
    font-size: 28px;
    margin-right: 12px;
}

.rejection-header h6 {
    margin: 0;
    font-weight: 700;
    color: white;
}

.rejection-content {
    background: rgba(255, 255, 255, 0.2);
    padding: 15px;
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

/* Contract Generated Card */
.contract-generated-card {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    border-radius: 15px !important;
    margin-top: 20px;
}

.contract-success-content {
    display: flex;
    align-items: center;
    gap: 30px;
    color: white;
}

.success-icon {
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 50px;
    flex-shrink: 0;
    animation: scaleIn 0.5s ease;
}

@keyframes scaleIn {
    0% { transform: scale(0); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.success-text h4,
.success-text p {
    color: white;
}

/* Gradient Backgrounds */
.bg-gradient-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

/* Ensure proper spacing from footer */
.content-card,
.contract-generated-card {
    margin-bottom: 30px;
}

/* Fix scrolling and footer overlap */
.container-fluid {
    padding-bottom: 50px !important;
    min-height: calc(100vh - 200px);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .timeline-container {
        flex-wrap: wrap;
    }
    
    .timeline-step {
        flex: 1 1 20%;
        min-width: 80px;
    }
}

@media (max-width: 768px) {
    .proposal-hero-card h2 {
        font-size: 24px;
    }
    
    .action-buttons-group {
        justify-content: center;
    }
    
    .timeline-container {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .timeline-connector {
        display: none;
    }
    
    .timeline-step {
        flex-direction: row;
        width: 100%;
        margin-bottom: 20px;
    }
    
    .timeline-content {
        margin-top: 0;
        margin-left: 15px;
        text-align: left;
    }
    
    .contract-success-content {
        flex-direction: column;
        text-align: center;
    }
}
</style>
@endsection
