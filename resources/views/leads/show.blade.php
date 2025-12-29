@extends('layouts.app')

@section('title', $lead->customer_name . ' - Lead Details')

@section('page-title', 'Lead Details')

@section('content')

<div class="row g-3">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Lead Information</h4>
                <div class="d-flex gap-2">
                    <!-- Quick Actions Dropdown (Schedule/Manage Only) -->
                    <div class="dropdown">
                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bolt"></i> Quick Actions
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Schedule Activities</h6>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#scheduleCallbackModal">
                                <i class="fa fa-phone text-warning"></i> Schedule Callback
                            </a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#scheduleMeetingModal">
                                <i class="fa fa-calendar text-success"></i> Schedule Meeting
                            </a>
                            @if($lead->callback_time)
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Manage Callback</h6>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#postponeCallbackModal">
                                <i class="fa fa-clock text-orange"></i> Postpone Callback
                            </a>
                            <a class="dropdown-item" href="#" onclick="markCallbackComplete()">
                                <i class="fa fa-check text-success"></i> Mark Callback Complete
                            </a>
                            @endif
                            @if($lead->meeting_time)
                            <div class="dropdown-divider"></div>
                            <h6 class="dropdown-header">Manage Meeting</h6>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editMeetingModal">
                                <i class="fa fa-edit text-info"></i> Edit Meeting
                            </a>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#postponeMeetingModal">
                                <i class="fa fa-calendar-times text-warning"></i> Postpone Meeting
                            </a>
                            <a class="dropdown-item" href="#" onclick="cancelMeeting()">
                                <i class="fa fa-times text-danger"></i> Cancel Meeting
                            </a>
                            @endif
                        </div>
                    </div>
                    <!-- View Details Dropdown -->
                    <div class="dropdown">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-eye"></i> View Details
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#lead-timeline">
                                <i class="fa fa-history text-primary"></i> View Complete History
                            </a>
                            <a class="dropdown-item" href="#contact-info">
                                <i class="fa fa-phone text-info"></i> Contact Information
                            </a>
                            <a class="dropdown-item" href="#scheduled-activities">
                                <i class="fa fa-calendar text-warning"></i> Scheduled Activities
                            </a>
                        </div>
                    </div>
                    @if($lead->status !== 'converted')
                    <button id="convertBtn" class="btn btn-outline-success btn-sm ms-2" type="button" onclick="convertToCustomer()">
                        <i class="fa fa-user-plus"></i> Convert to Customer
                    </button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <!-- Lead Basic Info -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <div class="avatar-lg me-4">
                                <span class="avatar-title rounded-circle bg-soft-primary text-primary fs-24">
                                    {{ strtoupper(substr($lead->customer_name, 0, 1)) }}
                                </span>
                            </div>
                            <div>
                                <h4 class="mb-1">{{ $lead->customer_name }}</h4>
                                <p class="text-muted mb-0">{{ $lead->email }}</p>
                                <p class="text-muted mb-0">{{ $lead->phone_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lead Quick Summary -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="text-muted small d-block mb-1"><i class="fa fa-share-alt"></i> Platform/Source</label>
                            <span class="badge bg-primary">{{ ucfirst($lead->platform_custom ?? $lead->platform) }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="text-muted small d-block mb-1"><i class="fa fa-user-tie"></i> Assigned To</label>
                            <strong>{{ $lead->assignedUser->name ?? 'Unassigned' }}</strong>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="text-muted small d-block mb-1"><i class="fa fa-calendar-plus"></i> Date Added</label>
                            <strong>{{ $lead->created_at->format('d M Y, g:i A') }}</strong>
                            <span class="badge bg-light text-dark ms-2">{{ $daysInSystem }} days</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <label class="text-muted small d-block mb-1"><i class="fa fa-info-circle"></i> Current Status</label>
                            @switch($lead->status)
                                @case('new')
                                    <span class="badge bg-info">New</span>
                                    @break
                                @case('contacted')
                                    <span class="badge bg-warning">Contacted</span>
                                    @break
                                @case('qualified')
                                    <span class="badge bg-primary">Qualified</span>
                                    @break
                                @case('meeting_scheduled')
                                    <span class="badge bg-secondary">Meeting Scheduled</span>
                                    @break
                                @case('proposal_sent')
                                    <span class="badge bg-info">Proposal Sent</span>
                                    @break
                                @case('negotiation')
                                    <span class="badge bg-warning">Negotiation</span>
                                    @break
                                @case('won')
                                    <span class="badge bg-success">Won</span>
                                    @break
                                @case('lost')
                                    <span class="badge bg-danger">Lost</span>
                                    @break
                                @case('not_interested')
                                    <span class="badge bg-danger">Not Interested</span>
                                    @break
                                @case('converted')
                                    <span class="badge bg-success">Converted to Customer</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $lead->status)) }}</span>
                            @endswitch
                        </div>
                    </div>
                </div>

                <!-- Scheduled Activities -->
                @if($lead->callback_time || $lead->meeting_time)
                <hr class="my-4">
                <h5 class="mb-3">Scheduled Activities</h5>
                <div class="row">
                    @if($lead->callback_time)
                    <div class="col-md-6 mb-3">
                        <div class="card border-warning shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <div class="me-3">
                                            <i class="fa fa-phone fa-2x text-warning"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 text-warning">Next Callback</h6>
                                            <p class="mb-1 fw-bold">{{ \Carbon\Carbon::parse($lead->callback_time)->format('d M Y, g:i A') }}</p>
                                            @if($lead->call_notes)
                                                <small class="text-muted">Notes: {{ $lead->call_notes }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge bg-warning"><i class="fa fa-clock"></i> Scheduled</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($lead->meeting_time)
                    <div class="col-md-6 mb-3">
                        <div class="card border-info shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-start justify-content-between">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <div class="me-3">
                                            <i class="fa fa-calendar fa-2x text-info"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 text-info">Next Meeting</h6>
                                            <p class="mb-1 fw-bold">{{ \Carbon\Carbon::parse($lead->meeting_time)->format('d M Y, g:i A') }}</p>
                                            @if($lead->meeting_address)
                                                <small class="text-muted d-block"><i class="fa fa-map-marker-alt"></i> {{ $lead->meeting_address }}</small>
                                            @endif
                                            @if($lead->meeting_person_name)
                                                <small class="text-muted d-block"><i class="fa fa-user"></i> {{ $lead->meeting_person_name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge bg-info"><i class="fa fa-calendar-check"></i> Scheduled</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Comprehensive Activity History -->
        <div class="card" id="lead-timeline">
            <div class="card-header bg-primary text-white">
                <h4 class="card-title mb-0 text-white"><i class="fa fa-history"></i> Complete Lead Journey & History</h4>
                <p class="mb-0 text-white-50 small">Chronological view of all interactions, status changes, and activities</p>
            </div>
            <div class="card-body">
                <div class="lead-journey-timeline">
                    <!-- Lead Created -->
                    <div class="journey-step">
                        <div class="step-marker bg-primary">
                            <i class="fa fa-plus-circle"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-header">
                                <h5 class="step-title">Lead Created</h5>
                                <span class="step-time">{{ $lead->created_at->format('d M Y, g:i A') }}</span>
                            </div>
                            <div class="step-details">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Customer:</strong> {{ $lead->customer_name }}</p>
                                        <p class="mb-1"><strong>Contact:</strong> {{ $lead->phone_number }}</p>
                                        <p class="mb-1"><strong>Email:</strong> {{ $lead->email ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Source:</strong> {{ ucfirst($lead->platform_custom ?? $lead->platform) }}</p>
                                        <p class="mb-1"><strong>Project Type:</strong> {{ ucfirst(str_replace('_', ' ', $lead->project_type)) }}</p>
                                        @if($lead->project_valuation)
                                            <p class="mb-1"><strong>Valuation:</strong> ₹{{ number_format($lead->project_valuation, 2) }}</p>
                                        @endif
                                    </div>
                                </div>
                                @if($lead->remarks)
                                    <div class="alert alert-info mt-2 mb-0">
                                        <strong>Initial Remarks:</strong> {{ $lead->remarks }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Callback Scheduled -->
                    @if($lead->callback_time)
                    <div class="journey-arrow">
                        <i class="fa fa-arrow-down"></i>
                    </div>
                    
                    <div class="journey-step">
                        <div class="step-marker bg-warning">
                            <i class="fa fa-phone"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-header">
                                <h5 class="step-title">Callback Scheduled</h5>
                                <span class="step-time">{{ \Carbon\Carbon::parse($lead->callback_time)->format('d M Y, g:i A') }}</span>
                            </div>
                            <div class="step-details">
                                <p class="mb-1"><strong>Scheduled By:</strong> {{ $lead->assignedUser->name ?? 'System' }}</p>
                                @if($lead->call_notes)
                                    <div class="alert alert-warning mb-0">
                                        <strong>Notes:</strong> {{ $lead->call_notes }}
                                    </div>
                                @endif
                                @if($lead->callback_completed)
                                    <span class="badge bg-success mt-2"><i class="fa fa-check"></i> Completed</span>
                                @else
                                    <span class="badge bg-warning mt-2"><i class="fa fa-clock"></i> Pending</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Meeting Scheduled -->
                    @if($lead->meeting_time)
                    <div class="journey-arrow">
                        <i class="fa fa-arrow-down"></i>
                    </div>
                    
                    <div class="journey-step">
                        <div class="step-marker bg-info">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-header">
                                <h5 class="step-title">Meeting Scheduled</h5>
                                <span class="step-time">{{ \Carbon\Carbon::parse($lead->meeting_time)->format('d M Y, g:i A') }}</span>
                            </div>
                            <div class="step-details">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Meeting With:</strong> {{ $lead->meeting_person_name }}</p>
                                        <p class="mb-1"><strong>Contact:</strong> {{ $lead->meeting_phone_number }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Location:</strong> {{ $lead->meeting_address }}</p>
                                        <p class="mb-1"><strong>Scheduled By:</strong> {{ $lead->assignedUser->name ?? 'System' }}</p>
                                    </div>
                                </div>
                                @if($lead->meeting_summary)
                                    <div class="alert alert-info mt-2 mb-0">
                                        <strong>Agenda:</strong> {{ $lead->meeting_summary }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Current Status -->
                    <div class="journey-arrow">
                        <i class="fa fa-arrow-down"></i>
                    </div>
                    
                    <div class="journey-step">
                        <div class="step-marker {{ $lead->status === 'converted' ? 'bg-success' : ($lead->status === 'not_interested' || $lead->status === 'lost' ? 'bg-danger' : 'bg-secondary') }}">
                            <i class="fa {{ $lead->status === 'converted' ? 'fa-check-circle' : ($lead->status === 'not_interested' || $lead->status === 'lost' ? 'fa-times-circle' : 'fa-info-circle') }}"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-header">
                                <h5 class="step-title">Current Status: {{ ucfirst(str_replace('_', ' ', $lead->status)) }}</h5>
                                <span class="step-time">{{ $lead->updated_at->format('d M Y, g:i A') }}</span>
                            </div>
                            <div class="step-details">
                                @if($lead->status === 'not_interested' && $lead->not_interested_reason)
                                    <div class="alert alert-danger">
                                        <strong><i class="fa fa-exclamation-triangle"></i> Reason for Not Interested:</strong><br>
                                        {{ $lead->not_interested_reason }}
                                    </div>
                                @endif
                                @if($lead->status === 'lost' && $lead->remarks)
                                    <div class="alert alert-danger">
                                        <strong><i class="fa fa-exclamation-triangle"></i> Reason for Lost:</strong><br>
                                        {{ $lead->remarks }}
                                    </div>
                                @endif
                                @if($lead->status === 'converted')
                                    <div class="alert alert-success">
                                        <strong><i class="fa fa-trophy"></i> Success!</strong><br>
                                        This lead has been successfully converted to a customer.
                                    </div>
                                @endif
                                @if($lead->remarks && !in_array($lead->status, ['lost', 'not_interested']))
                                    <div class="alert alert-info mb-0">
                                        <strong>Latest Remarks:</strong> {{ $lead->remarks }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($lead->status === 'converted')
                    <div class="journey-arrow">
                        <i class="fa fa-arrow-down"></i>
                    </div>

                    <div class="journey-step">
                        <div class="step-marker bg-success">
                            <i class="fa fa-trophy"></i>
                        </div>
                        <div class="step-content">
                            <div class="step-header">
                                <h5 class="step-title text-success">🎉 Converted to Customer</h5>
                                <span class="step-time">{{ $lead->updated_at->format('d M Y, g:i A') }}</span>
                            </div>
                            <div class="step-details">
                                <div class="alert alert-success mb-0">
                                    <strong>Congratulations!</strong> This lead has completed the journey and is now a valued customer.
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <!-- Contact Information -->
        <div class="card" id="contact-info">
            <div class="card-header bg-info text-white">
                <h4 class="card-title mb-0 text-white"><i class="fa fa-address-card"></i> Contact Information</h4>
            </div>
            <div class="card-body">
                <div class="contact-info-item">
                    <div class="d-flex align-items-start">
                        <div class="contact-icon">
                            <i class="fa fa-user-circle text-primary"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <label class="text-muted small">Customer Name</label>
                            <h6 class="mb-0">{{ $lead->customer_name }}</h6>
                        </div>
                    </div>
                </div>
                <div class="contact-info-item">
                    <div class="d-flex align-items-start">
                        <div class="contact-icon">
                            <i class="fa fa-phone text-success"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <label class="text-muted small">Phone Number</label>
                            <h6 class="mb-0">
                                <a href="tel:{{ $lead->phone_number }}" class="text-decoration-none text-dark">
                                    {{ $lead->phone_number }}
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>
                @if($lead->email)
                <div class="contact-info-item">
                    <div class="d-flex align-items-start">
                        <div class="contact-icon">
                            <i class="fa fa-envelope text-info"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <label class="text-muted small">Email Address</label>
                            <h6 class="mb-0">
                                <a href="mailto:{{ $lead->email }}" class="text-decoration-none text-dark">
                                    {{ $lead->email }}
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>
                @endif
                <div class="contact-info-item">
                    <div class="d-flex align-items-start">
                        <div class="contact-icon">
                            <i class="fa fa-user-tie text-warning"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <label class="text-muted small">Assigned BDM</label>
                            <h6 class="mb-0">{{ $lead->assignedUser->name ?? 'Unassigned' }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lead Statistics -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Lead Statistics</h4>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end pe-3">
                            <h3 class="text-primary">{{ $daysInSystem }}</h3>
                            <p class="text-muted mb-0">Days in System</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <h3 class="text-info">{{ $totalInteractions }}</h3>
                        <p class="text-muted mb-0">Total Interactions</p>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-warning">{{ $scheduledCallbacks }}</h4>
                        <p class="text-muted mb-0">Callbacks</p>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $scheduledMeetings }}</h4>
                        <p class="text-muted mb-0">Meetings</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Details -->
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h4 class="card-title mb-0 text-white"><i class="fa fa-briefcase"></i> Project Details</h4>
            </div>
            <div class="card-body">
                <div class="project-detail-item">
                    <label class="text-muted small">Project Type</label>
                    <h6 class="mb-0">{{ ucfirst(str_replace('_', ' ', $lead->project_type)) }}</h6>
                </div>
                <div class="project-detail-item">
                    <label class="text-muted small">Project Valuation</label>
                    <h6 class="mb-0">
                        @if($lead->project_valuation)
                            <span class="text-success">₹{{ number_format($lead->project_valuation, 2) }}</span>
                        @else
                            <span class="text-muted">Not specified</span>
                        @endif
                    </h6>
                </div>
                <div class="project-detail-item">
                    <label class="text-muted small">Source Platform</label>
                    <h6 class="mb-0">
                        <span class="badge bg-primary">{{ ucfirst($lead->platform_custom ?? $lead->platform) }}</span>
                    </h6>
                </div>
                <div class="project-detail-item">
                    <label class="text-muted small">Date Added</label>
                    <h6 class="mb-0">{{ $lead->created_at->format('d M Y, g:i A') }}</h6>
                    <small class="text-muted">{{ $daysInSystem }} days in system</small>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Schedule Callback Modal -->
<div class="modal fade" id="scheduleCallbackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fa fa-phone"></i> Schedule Callback</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('leads.schedule-callback', $lead->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Callback Date & Time</label>
                        <input type="datetime-local" name="callback_time" class="form-control" required 
                               value="{{ $lead->callback_time ? \Carbon\Carbon::parse($lead->callback_time)->format('Y-m-d\TH:i') : '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Call Notes</label>
                        <textarea name="call_notes" class="form-control" rows="3" placeholder="Notes for the callback...">{{ $lead->call_notes }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm"><i class="fa fa-phone"></i> Schedule Callback</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Postpone Callback Modal -->
<div class="modal fade" id="postponeCallbackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-orange text-white" style="background-color: #fd7e14;">
                <h5 class="modal-title"><i class="fa fa-clock"></i> Postpone Callback</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('leads.postpone-callback', $lead->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Current callback: 
                        <strong>{{ $lead->callback_time ? \Carbon\Carbon::parse($lead->callback_time)->format('d M Y, g:i A') : 'Not set' }}</strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Callback Date & Time</label>
                        <input type="datetime-local" name="callback_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason for Postponing</label>
                        <textarea name="postpone_reason" class="form-control" rows="2" placeholder="Why is this callback being postponed?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm"><i class="fa fa-clock"></i> Postpone</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Meeting Modal -->
<div class="modal fade" id="scheduleMeetingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa fa-calendar"></i> Schedule Meeting</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('leads.schedule-meeting', $lead->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meeting Date & Time</label>
                            <input type="datetime-local" name="meeting_time" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meeting Person Name</label>
                            <input type="text" name="meeting_person_name" class="form-control" placeholder="Person to meet" value="{{ $lead->customer_name }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="meeting_phone_number" class="form-control" placeholder="Contact number" value="{{ $lead->phone_number }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meeting Address</label>
                            <input type="text" name="meeting_address" class="form-control" placeholder="Location">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meeting Agenda/Summary</label>
                        <textarea name="meeting_summary" class="form-control" rows="2" placeholder="Purpose of the meeting..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-calendar-check"></i> Schedule Meeting</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Meeting Modal -->
<div class="modal fade" id="editMeetingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fa fa-edit"></i> Edit Meeting</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('leads.update-meeting', $lead->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meeting Date & Time</label>
                            <input type="datetime-local" name="meeting_time" class="form-control" required
                                   value="{{ $lead->meeting_time ? \Carbon\Carbon::parse($lead->meeting_time)->format('Y-m-d\TH:i') : '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meeting Person Name</label>
                            <input type="text" name="meeting_person_name" class="form-control" value="{{ $lead->meeting_person_name }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="meeting_phone_number" class="form-control" value="{{ $lead->meeting_phone_number }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meeting Address</label>
                            <input type="text" name="meeting_address" class="form-control" value="{{ $lead->meeting_address }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meeting Agenda/Summary</label>
                        <textarea name="meeting_summary" class="form-control" rows="2">{{ $lead->meeting_summary }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-save"></i> Update Meeting</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Postpone Meeting Modal -->
<div class="modal fade" id="postponeMeetingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fa fa-calendar-times"></i> Postpone Meeting</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('leads.postpone-meeting', $lead->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Current meeting: 
                        <strong>{{ $lead->meeting_time ? \Carbon\Carbon::parse($lead->meeting_time)->format('d M Y, g:i A') : 'Not set' }}</strong>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Meeting Date & Time</label>
                        <input type="datetime-local" name="meeting_time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason for Postponing</label>
                        <textarea name="postpone_reason" class="form-control" rows="2" placeholder="Why is this meeting being postponed?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm"><i class="fa fa-calendar-times"></i> Postpone</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@push('styles')
<style>
/* Override template CSS - Force compact layout */
.content-body .container-fluid {
    padding-top: 15px !important;
    padding-bottom: 15px !important;
}

/* Card Overrides - Remove height calc and large margins */
.card {
    margin-bottom: 12px !important;
    height: auto !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
    border: 1px solid #e3e6f0 !important;
}

.col-xl-4 .card:last-child,
.col-xl-8 .card:last-child {
    margin-bottom: 0 !important;
}

.card-body {
    padding: 12px !important;
}

.card-header {
    padding: 10px 12px !important;
    background: #f8f9fc !important;
    border-bottom: 1px solid #e3e6f0 !important;
}

.card-header.bg-primary,
.card-header.bg-info,
.card-header.bg-warning {
    padding: 10px 12px !important;
}

.card-header .card-title {
    margin-bottom: 0 !important;
    font-size: 14px !important;
    font-weight: 600 !important;
}

.card-header p {
    margin-bottom: 0 !important;
    font-size: 11px !important;
}

/* Row Overrides */
.row.g-3 {
    --bs-gutter-x: 12px !important;
    --bs-gutter-y: 12px !important;
}

/* Lead Journey Timeline */
.lead-journey-timeline {
    padding: 0 !important;
    margin: 0 !important;
}

.journey-step {
    display: flex !important;
    align-items: flex-start !important;
    margin-bottom: 8px !important;
    gap: 10px !important;
}

.journey-step:last-child {
    margin-bottom: 0 !important;
}

.step-marker {
    width: 36px !important;
    height: 36px !important;
    min-width: 36px !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    color: #fff !important;
    font-size: 14px !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.15) !important;
}

.step-content {
    flex: 1 !important;
    background: #fff !important;
    border-radius: 6px !important;
    padding: 10px !important;
    border: 1px solid #e3e6f0 !important;
}

.step-header {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    margin-bottom: 8px !important;
    padding-bottom: 6px !important;
    border-bottom: 1px solid #e9ecef !important;
}

.step-title {
    font-size: 13px !important;
    font-weight: 600 !important;
    margin: 0 !important;
    color: #2c3e50 !important;
}

.step-time {
    font-size: 10px !important;
    color: #6c757d !important;
    background: #f8f9fa !important;
    padding: 2px 6px !important;
    border-radius: 10px !important;
    white-space: nowrap !important;
}

.step-details {
    font-size: 12px !important;
    line-height: 1.4 !important;
}

.step-details p {
    margin-bottom: 3px !important;
}

.step-details p:last-child {
    margin-bottom: 0 !important;
}

.step-details .alert {
    margin-top: 6px !important;
    margin-bottom: 0 !important;
    padding: 8px !important;
    font-size: 11px !important;
    border-radius: 4px !important;
}

.journey-arrow {
    text-align: center !important;
    margin: 3px 0 !important;
    padding-left: 18px !important;
    color: #007bff !important;
    font-size: 12px !important;
    line-height: 1 !important;
}

/* Info Item */
.info-item {
    padding: 6px 10px !important;
    background: #f8f9fc !important;
    border-radius: 5px !important;
    border-left: 3px solid #4e73df !important;
    margin-bottom: 8px !important;
}

.info-item label {
    margin-bottom: 2px !important;
    font-size: 10px !important;
}

/* Contact Info */
.contact-info-item {
    padding-bottom: 8px !important;
    margin-bottom: 8px !important;
    border-bottom: 1px solid #e9ecef !important;
}

.contact-info-item:last-child {
    border-bottom: none !important;
    padding-bottom: 0 !important;
    margin-bottom: 0 !important;
}

.contact-icon {
    width: 36px !important;
    height: 36px !important;
    min-width: 36px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: #f8f9fc !important;
    border-radius: 6px !important;
}

.contact-icon i {
    font-size: 16px !important;
}

.contact-info-item label {
    margin-bottom: 1px !important;
    font-size: 10px !important;
    display: block !important;
}

.contact-info-item h6 {
    font-size: 12px !important;
    margin-bottom: 0 !important;
}

/* Project Details */
.project-detail-item {
    padding: 6px 10px !important;
    background: #fffbf0 !important;
    border-radius: 5px !important;
    border-left: 3px solid #ffc107 !important;
    margin-bottom: 6px !important;
}

.project-detail-item:last-child {
    margin-bottom: 0 !important;
}

.project-detail-item label {
    margin-bottom: 1px !important;
    font-size: 10px !important;
    display: block !important;
}

.project-detail-item h6 {
    font-size: 12px !important;
    margin-bottom: 0 !important;
}

/* Lead Statistics */
.card-body .row.text-center h3 {
    font-size: 20px !important;
    margin-bottom: 2px !important;
}

.card-body .row.text-center h4 {
    font-size: 16px !important;
    margin-bottom: 2px !important;
}

.card-body .row.text-center p {
    font-size: 10px !important;
    margin-bottom: 0 !important;
}

/* Badge */
.badge {
    font-size: 10px !important;
    padding: 3px 6px !important;
    font-weight: 500 !important;
}

/* Avatar */
.avatar-lg {
    width: 45px !important;
    height: 45px !important;
}

.avatar-title {
    width: 100% !important;
    height: 100% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 18px !important;
}

/* HR */
hr {
    margin: 8px 0 !important;
}

/* Margin Utilities */
.mb-0 { margin-bottom: 0 !important; }
.mb-1 { margin-bottom: 0.15rem !important; }
.mb-2 { margin-bottom: 0.3rem !important; }
.mb-3 { margin-bottom: 0.6rem !important; }
.mb-4 { margin-bottom: 0.8rem !important; }
.mt-2 { margin-top: 0.3rem !important; }
.my-4 { margin-top: 0.8rem !important; margin-bottom: 0.8rem !important; }
.me-3 { margin-right: 0.8rem !important; }
.me-4 { margin-right: 1rem !important; }

/* Responsive */
@media (max-width: 768px) {
    .step-marker {
        width: 32px !important;
        height: 32px !important;
        min-width: 32px !important;
        font-size: 12px !important;
    }
    
    .step-content {
        padding: 8px !important;
    }
    
    .step-header {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 3px !important;
    }
    
    .journey-arrow {
        padding-left: 16px !important;
        font-size: 10px !important;
    }
}

/* Extra visual polish */
.lead-page-wrapper { background: linear-gradient(180deg, #fbfbfd 0%, #f6f8ff 100%); padding: 12px; border-radius: 8px; }
.card-header.gradient { background: linear-gradient(90deg,#4e73df,#6a82fb) !important; color: #fff !important; }
.card-header.gradient .card-title { color: #fff !important; }
.step-marker.bg-primary { background: linear-gradient(180deg,#4e73df,#3750d3) !important; }
.step-marker.bg-success { background: linear-gradient(180deg,#2ecc71,#28a745) !important; }
.step-marker.bg-warning { background: linear-gradient(180deg,#ffc107,#ffb020) !important; }
.step-marker.bg-info { background: linear-gradient(180deg,#17a2b8,#0ea5a4) !important; }
.journey-step .step-content { transition: box-shadow .18s ease, transform .18s ease; }
.journey-step:hover .step-content { box-shadow: 0 6px 20px rgba(46,61,73,0.08) !important; transform: translateY(-3px); }
.badge-custom { padding: 6px 8px; border-radius: 12px; font-weight:600; }
.info-item { box-shadow: inset 0 0 0 1px rgba(78,115,223,0.04); }
.contact-icon { background: linear-gradient(180deg,#fff,#f1f5ff) !important; }
.avatar-title { background: linear-gradient(90deg,#e9edf9,#fff); }
.btn-outline-success { border-color: #2ecc71; color: #2ecc71; }
.btn-outline-success:hover { background: #2ecc71; color: #fff; border-color: #2ecc71; }
.convert-note { font-size: 12px; color: #6c757d; margin-left: 8px; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide not interested reason field
    const statusSelect = document.getElementById('statusSelect');
    const notInterestedDiv = document.getElementById('notInterestedReasonDiv');
    
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            if (this.value === 'not_interested') {
                notInterestedDiv.style.display = 'block';
            } else {
                notInterestedDiv.style.display = 'none';
            }
        });
        
        // Check initial state
        if (statusSelect.value === 'not_interested') {
            notInterestedDiv.style.display = 'block';
        }
    }
    
    // Smooth scroll to timeline when clicking view details
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href.startsWith('#') && !href.includes('Modal')) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        });
    });
});

// Mark callback as complete
function markCallbackComplete() {
    if (confirm('Mark this callback as completed?')) {
        fetch('{{ route("leads.complete-callback", $lead->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Callback marked as completed!');
                location.reload();
            } else {
                alert(data.message || 'Error completing callback');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error completing callback');
        });
    }
}

// Cancel meeting
function cancelMeeting() {
    if (confirm('Are you sure you want to cancel this meeting?')) {
        fetch('{{ route("leads.cancel-meeting", $lead->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Meeting cancelled successfully!');
                location.reload();
            } else {
                alert(data.message || 'Error cancelling meeting');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error cancelling meeting');
        });
    }
}

// Convert to customer
function convertToCustomer() {
    if (!confirm('Convert this lead into a customer?')) return;
    fetch('{{ route("leads.convert-to-customer", $lead->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Converted successfully');
            location.reload();
        } else {
            alert(data.message || 'Conversion failed');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Error converting lead');
    });
}
</script>
@endpush

