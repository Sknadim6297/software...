@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="flaticon-381-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="flaticon-381-exclamation me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Header Card with Status -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3>Proposal #{{ $proposal->id }}</h3>
                            <p class="mb-0 text-muted">{{ $proposal->project_type }} for {{ $proposal->customer_name }}</p>
                        </div>
                        <div class="text-end">
                            <span class="badge badge-{{ $proposal->getStatusBadgeColor() }} badge-lg">
                                {{ ucfirst(str_replace('_', ' ', $proposal->status)) }}
                            </span>
                            <div class="mt-2">
                                @if($proposal->status === 'draft')
                                    <form action="{{ route('proposals.send', $proposal->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary" onclick="return confirm('Send this proposal to customer?')">
                                            <i class="flaticon-381-send me-2"></i> Send Proposal
                                        </button>
                                    </form>
                                    <a href="{{ route('proposals.edit', $proposal->id) }}" class="btn btn-warning">
                                        <i class="flaticon-381-edit me-2"></i> Edit
                                    </a>
                                @endif
                                
                                @if($proposal->status === 'sent' || $proposal->status === 'viewed')
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#acceptModal">
                                        <i class="flaticon-381-check me-2"></i> Accept Proposal
                                    </button>
                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                        <i class="flaticon-381-close me-2"></i> Reject Proposal
                                    </button>
                                @endif

                                <a href="{{ route('proposals.index') }}" class="btn btn-secondary">
                                    <i class="flaticon-381-back me-2"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Status Progress Bar -->
                    <div class="mt-4">
                        <div class="progress-bar-container">
                            <div class="d-flex justify-content-between">
                                <div class="text-center flex-fill">
                                    <div class="progress-step {{ in_array($proposal->status, ['draft', 'sent', 'viewed', 'accepted', 'rejected']) ? 'active' : '' }}">
                                        <i class="flaticon-381-file-1"></i>
                                    </div>
                                    <small>Draft</small>
                                </div>
                                <div class="progress-line {{ in_array($proposal->status, ['sent', 'viewed', 'accepted', 'rejected']) ? 'active' : '' }}"></div>
                                <div class="text-center flex-fill">
                                    <div class="progress-step {{ in_array($proposal->status, ['sent', 'viewed', 'accepted', 'rejected']) ? 'active' : '' }}">
                                        <i class="flaticon-381-send"></i>
                                    </div>
                                    <small>Sent</small>
                                </div>
                                <div class="progress-line {{ in_array($proposal->status, ['viewed', 'accepted', 'rejected']) ? 'active' : '' }}"></div>
                                <div class="text-center flex-fill">
                                    <div class="progress-step {{ in_array($proposal->status, ['viewed', 'accepted', 'rejected']) ? 'active' : '' }}">
                                        <i class="flaticon-381-view"></i>
                                    </div>
                                    <small>Viewed</small>
                                </div>
                                <div class="progress-line {{ $proposal->status === 'accepted' ? 'active' : '' }}"></div>
                                <div class="text-center flex-fill">
                                    <div class="progress-step {{ $proposal->status === 'accepted' ? 'active' : '' }}">
                                        <i class="flaticon-381-check"></i>
                                    </div>
                                    <small>{{ $proposal->status === 'rejected' ? 'Rejected' : 'Accepted' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Customer & Project Details -->
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Customer Details</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Name:</strong> {{ $proposal->customer_name }}</p>
                            <p><strong>Email:</strong> {{ $proposal->customer_email }}</p>
                            <p><strong>Phone:</strong> {{ $proposal->customer_phone }}</p>
                            <p><strong>Lead Type:</strong> <span class="badge badge-info">{{ ucfirst($proposal->lead_type) }}</span></p>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Project Summary</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Type:</strong> {{ $proposal->project_type }}</p>
                            <p><strong>Amount:</strong> {{ $proposal->currency }} {{ number_format($proposal->proposed_amount, 2) }}</p>
                            @if($proposal->estimated_days)
                                <p><strong>Duration:</strong> {{ $proposal->estimated_days }} days</p>
                            @endif
                            @if($proposal->payment_terms)
                                <p><strong>Payment:</strong> {{ $proposal->payment_terms }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Timeline</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Created:</strong><br>{{ $proposal->created_at->format('d M Y, h:i A') }}</p>
                            @if($proposal->sent_at)
                                <p><strong>Sent:</strong><br>{{ $proposal->sent_at->format('d M Y, h:i A') }}</p>
                            @endif
                            @if($proposal->viewed_at)
                                <p><strong>Viewed:</strong><br>{{ $proposal->viewed_at->format('d M Y, h:i A') }}</p>
                            @endif
                            @if($proposal->responded_at)
                                <p><strong>Responded:</strong><br>{{ $proposal->responded_at->format('d M Y, h:i A') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Proposal Content -->
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Proposal Content</h5>
                        </div>
                        <div class="card-body">
                            @if($proposal->project_description)
                                <div class="mb-4">
                                    <h6>Project Description:</h6>
                                    <p>{{ $proposal->project_description }}</p>
                                </div>
                            @endif

                            @if($proposal->deliverables)
                                <div class="mb-4">
                                    <h6>Deliverables:</h6>
                                    <p style="white-space: pre-line;">{{ $proposal->deliverables }}</p>
                                </div>
                            @endif

                            <div class="proposal-content">
                                <h6>Full Proposal:</h6>
                                <div class="p-4 bg-light border rounded">
                                    <pre style="white-space: pre-wrap; font-family: inherit;">{{ $proposal->proposal_content }}</pre>
                                </div>
                            </div>

                            @if($proposal->rejection_reason)
                                <div class="mt-4">
                                    <div class="alert alert-danger">
                                        <h6><i class="flaticon-381-info-1 me-2"></i> Rejection Reason:</h6>
                                        <p class="mb-0">{{ $proposal->rejection_reason }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($proposal->contract)
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title text-white mb-0">
                                    <i class="flaticon-381-check me-2"></i> Contract Generated
                                </h5>
                            </div>
                            <div class="card-body">
                                <p>This proposal has been accepted and a contract has been generated.</p>
                                <a href="{{ route('contracts.show', $proposal->contract->id) }}" class="btn btn-success">
                                    <i class="flaticon-381-view me-2"></i> View Contract
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
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
.progress-bar-container {
    padding: 20px 0;
}

.progress-step {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-size: 20px;
    color: #6c757d;
    transition: all 0.3s;
}

.progress-step.active {
    background-color: var(--primary);
    color: white;
}

.progress-line {
    height: 3px;
    background-color: #e9ecef;
    flex-grow: 1;
    margin: 25px 10px 0;
    transition: all 0.3s;
}

.progress-line.active {
    background-color: var(--primary);
}

.badge-lg {
    font-size: 16px;
    padding: 8px 16px;
}
</style>
@endsection
