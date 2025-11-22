@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg history-hero-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-2 fw-bold text-white">
                                <i class="fa fa-history me-3"></i>Customer History
                            </h2>
                            <p class="mb-0 text-white-50">
                                Complete historical log of all customers - View only access
                            </p>
                        </div>
                        <div class="history-stats">
                            <div class="stat-item">
                                <h3 class="text-white mb-0">{{ $customers->total() }}</h3>
                                <small class="text-white-50">Total Customers</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('customers.history') }}" method="GET">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-primary text-white border-0">
                                <i class="fa fa-search"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   class="form-control border-0" 
                                   placeholder="Search by name, company, phone, email, or project type..." 
                                   value="{{ request('search') }}"
                                   autocomplete="off">
                            @if(request('search'))
                                <a href="{{ route('customers.history') }}" class="btn btn-outline-secondary">
                                    <i class="fa fa-times"></i> Clear
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fa fa-search me-2"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Info -->
    @if(request('search'))
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible fade show">
                    <i class="fa fa-info-circle me-2"></i>
                    Found <strong>{{ $customers->total() }}</strong> customer(s) matching "<strong>{{ request('search') }}</strong>"
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Customer History Cards -->
    <div class="row g-4 mb-5 pb-4">
        @forelse($customers as $customer)
            <div class="col-xl-6">
                <div class="card border-0 shadow-sm history-card {{ $customer->deleted_at ? 'deleted-customer' : '' }}">
                    <div class="card-header bg-gradient-primary text-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">
                                    <i class="fa fa-user-circle me-2"></i>
                                    {{ $customer->customer_name }}
                                </h5>
                                @if($customer->company_name)
                                    <small class="text-white-50">
                                        <i class="fa fa-building me-1"></i>{{ $customer->company_name }}
                                    </small>
                                @endif
                            </div>
                            <div>
                                @if($customer->deleted_at)
                                    <span class="badge bg-danger">
                                        <i class="fa fa-trash me-1"></i>Deleted
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fa fa-check-circle me-1"></i>Active
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Contact Information -->
                        <div class="info-section mb-3">
                            <h6 class="section-title">
                                <i class="fa fa-address-card text-primary me-2"></i>Contact Information
                            </h6>
                            <div class="info-grid">
                                <div class="info-item">
                                    <i class="fa fa-phone text-success"></i>
                                    <span>{{ $customer->number }}</span>
                                </div>
                                @if($customer->alternate_number)
                                    <div class="info-item">
                                        <i class="fa fa-phone-alt text-info"></i>
                                        <span>{{ $customer->alternate_number }}</span>
                                    </div>
                                @endif
                                @if($customer->email)
                                    <div class="info-item">
                                        <i class="fa fa-envelope text-warning"></i>
                                        <span>{{ $customer->email }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Project Information -->
                        <div class="info-section mb-3">
                            <h6 class="section-title">
                                <i class="fa fa-project-diagram text-success me-2"></i>Project Details
                            </h6>
                            <div class="project-info">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Project Type:</span>
                                    <strong>{{ $customer->project_type }}</strong>
                                </div>
                                @if($customer->project_valuation)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Valuation:</span>
                                        <strong class="text-success">₹{{ number_format($customer->project_valuation, 2) }}</strong>
                                    </div>
                                @endif
                                @if($customer->payment_terms)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Payment Terms:</span>
                                        <strong>{{ ucfirst(str_replace('_', ' ', $customer->payment_terms)) }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="info-section mb-3">
                            <h6 class="section-title">
                                <i class="fa fa-clock text-info me-2"></i>Timeline
                            </h6>
                            <div class="timeline-history">
                                <div class="timeline-item">
                                    <div class="timeline-dot bg-primary"></div>
                                    <div class="timeline-details">
                                        <small class="text-muted">Added Date</small>
                                        <strong>{{ \Carbon\Carbon::parse($customer->added_date)->format('d M Y') }}</strong>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-dot bg-success"></div>
                                    <div class="timeline-details">
                                        <small class="text-muted">Created At</small>
                                        <strong>{{ $customer->created_at->format('d M Y, h:i A') }}</strong>
                                    </div>
                                </div>
                                @if($customer->updated_at != $customer->created_at)
                                    <div class="timeline-item">
                                        <div class="timeline-dot bg-warning"></div>
                                        <div class="timeline-details">
                                            <small class="text-muted">Last Updated</small>
                                            <strong>{{ $customer->updated_at->format('d M Y, h:i A') }}</strong>
                                        </div>
                                    </div>
                                @endif
                                @if($customer->deleted_at)
                                    <div class="timeline-item">
                                        <div class="timeline-dot bg-danger"></div>
                                        <div class="timeline-details">
                                            <small class="text-muted">Deleted At</small>
                                            <strong>{{ $customer->deleted_at->format('d M Y, h:i A') }}</strong>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Related Records -->
                        @if($customer->invoices->count() > 0 || $customer->contracts->count() > 0)
                            <div class="info-section">
                                <h6 class="section-title">
                                    <i class="fa fa-link text-warning me-2"></i>Related Records
                                </h6>
                                <div class="related-records">
                                    @if($customer->contracts->count() > 0)
                                        <span class="badge bg-info me-2">
                                            <i class="fa fa-file-contract me-1"></i>
                                            {{ $customer->contracts->count() }} Contract(s)
                                        </span>
                                    @endif
                                    @if($customer->invoices->count() > 0)
                                        <span class="badge bg-success">
                                            <i class="fa fa-file-invoice me-1"></i>
                                            {{ $customer->invoices->count() }} Invoice(s)
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Additional Details -->
                        @if($customer->lead_source || $customer->gst_number || $customer->address || $customer->remarks)
                            <div class="info-section mt-3">
                                <h6 class="section-title">
                                    <i class="fa fa-info-circle text-secondary me-2"></i>Additional Information
                                </h6>
                                @if($customer->lead_source)
                                    <div class="mb-2">
                                        <small class="text-muted">Lead Source:</small>
                                        <span class="badge bg-secondary ms-2">{{ ucfirst($customer->lead_source) }}</span>
                                    </div>
                                @endif
                                @if($customer->gst_number)
                                    <div class="mb-2">
                                        <small class="text-muted">GST Number:</small>
                                        <strong class="ms-2">{{ $customer->gst_number }}</strong>
                                    </div>
                                @endif
                                @if($customer->address)
                                    <div class="mb-2">
                                        <small class="text-muted">Address:</small>
                                        <p class="mb-0 ms-2">{{ $customer->address }}</p>
                                    </div>
                                @endif
                                @if($customer->remarks)
                                    <div class="mb-2">
                                        <small class="text-muted">Remarks:</small>
                                        <p class="mb-0 ms-2 text-muted fst-italic">{{ $customer->remarks }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <div class="card-footer bg-light border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fa fa-eye me-1"></i>View Only - No Editing Allowed
                            </small>
                            <small class="text-muted">
                                ID: #{{ $customer->id }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fa fa-users fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No Customers Found</h4>
                        <p class="text-muted">
                            @if(request('search'))
                                No customers match your search criteria. Try a different search term.
                            @else
                                No customers have been added yet.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($customers->hasPages())
        <div class="row mb-5 pb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} customers
                            </div>
                            <div>
                                {{ $customers->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
/* Hero Card */
.history-hero-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px !important;
    overflow: hidden;
    position: relative;
}

.history-hero-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 400px;
    height: 400px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(30%, -30%);
}

.history-stats {
    background: rgba(255, 255, 255, 0.2);
    padding: 20px 30px;
    border-radius: 12px;
    backdrop-filter: blur(10px);
}

.stat-item {
    text-align: center;
}

/* History Cards */
.history-card {
    border-radius: 12px !important;
    transition: all 0.3s ease;
    overflow: hidden;
}

.history-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2) !important;
}

.history-card.deleted-customer {
    opacity: 0.85;
    border: 2px solid #dc3545;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

/* Info Sections */
.info-section {
    padding: 15px 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-section:last-child {
    border-bottom: none;
}

.section-title {
    font-weight: 700;
    margin-bottom: 15px;
    font-size: 14px;
    text-transform: uppercase;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 10px;
}

.info-item {
    display: flex;
    align-items: center;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.info-item i {
    font-size: 18px;
    margin-right: 12px;
    width: 25px;
    text-align: center;
}

/* Project Info */
.project-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #667eea;
}

/* Timeline History */
.timeline-history {
    position: relative;
    padding-left: 30px;
}

.timeline-history::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
    display: flex;
    align-items: flex-start;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-dot {
    position: absolute;
    left: -26px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

.timeline-details {
    flex: 1;
}

.timeline-details small {
    display: block;
    font-size: 11px;
}

.timeline-details strong {
    display: block;
    font-size: 14px;
    color: #2c3e50;
}

/* Related Records */
.related-records {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

/* Search Input */
.input-group-lg .form-control {
    font-size: 16px;
    padding: 12px 20px;
}

.input-group-text {
    font-size: 18px;
}

/* Responsive */
@media (max-width: 768px) {
    .history-stats {
        margin-top: 20px;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
