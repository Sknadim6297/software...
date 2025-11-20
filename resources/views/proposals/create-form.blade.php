@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <form action="{{ route('proposals.store') }}" method="POST" id="proposalForm">
        @csrf
        <input type="hidden" name="lead_id" value="{{ $lead->id }}">
        <input type="hidden" name="lead_type" value="{{ $leadType }}">
        
        <div class="row">
            <!-- Left Column - Customer Details -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" value="{{ $lead->customer_name }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $lead->email }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" value="{{ $lead->phone_number }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Lead Type</label>
                            <input type="text" class="form-control" value="{{ ucfirst($leadType) }}" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Proposal Details -->
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Proposal Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Project Type <span class="text-danger">*</span></label>
                                <select name="project_type" class="form-control" required>
                                    <option value="">Select Project Type</option>
                                    @foreach($projectTypes as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Proposed Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="currency" class="form-control" style="max-width: 80px;" required>
                                        <option value="INR">INR</option>
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                    </select>
                                    <input type="number" name="proposed_amount" class="form-control" step="0.01" min="0" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Project Description</label>
                            <textarea name="project_description" class="form-control" rows="3" placeholder="Brief description of the project..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Estimated Duration (Days)</label>
                                <input type="number" name="estimated_days" class="form-control" min="1" placeholder="e.g., 30">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Payment Terms</label>
                                <input type="text" name="payment_terms" class="form-control" placeholder="e.g., 50% advance, 50% on completion">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deliverables</label>
                            <textarea name="deliverables" class="form-control" rows="4" placeholder="List what will be delivered..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Proposal Content <span class="text-danger">*</span></label>
                            <textarea name="proposal_content" id="proposal_content" class="form-control" rows="15" required>Dear {{ $lead->customer_name }},

Thank you for your interest in our services. We are pleased to present this proposal for your project.

PROPOSAL OVERVIEW:
We understand your requirements and are confident in delivering a high-quality solution that meets your expectations.

SCOPE OF WORK:
[Describe the work to be done]

TIMELINE:
[Project timeline and milestones]

INVESTMENT:
[Pricing details]

NEXT STEPS:
Upon acceptance of this proposal, we will:
1. Finalize the contract
2. Begin project initiation
3. Set up regular communication channels

We look forward to working with you on this exciting project!

Best regards,
Konnectix Technologies Team</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('proposals.index') }}" class="btn btn-secondary">
                                <i class="flaticon-381-back me-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="flaticon-381-save me-2"></i> Create Proposal (Draft)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('proposalForm');
    
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields');
        }
    });
});
</script>
@endsection
