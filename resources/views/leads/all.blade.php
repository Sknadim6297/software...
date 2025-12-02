@extends('layouts.app')

@section('title', 'All Leads - Konnectix BDM')

@section('page-title', 'BDM - All Leads Management')

@section('content')
<style>
    /* Fix modal backdrop and z-index issues */
    .modal-backdrop {
        z-index: 1040 !important;
        opacity: 0.5 !important;
    }
    
    /* Remove ALL duplicate backdrops immediately */
    body > .modal-backdrop ~ .modal-backdrop {
        display: none !important;
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none !important;
    }
    
    /* Ensure only ONE backdrop exists */
    body.modal-open > .modal-backdrop:not(:first-of-type) {
        display: none !important;
    }
    
    /* Modal must be above backdrop */
    .modal {
        z-index: 1055 !important;
    }
    
    /* Modal dialog and content */
    .modal-dialog {
        z-index: 1056 !important;
        position: relative;
    }
    
    .modal-content {
        z-index: 1057 !important;
        position: relative;
    }
    
    /* Ensure modal is visible when shown */
    .modal.show {
        display: block !important;
    }
    
    /* Prevent body scroll when modal is open */
    body.modal-open {
        overflow: hidden !important;
    }
    
    /* Fix for backdrop click to close */
    .modal-backdrop.show {
        opacity: 0.5 !important;
    }
    
    /* Ensure modal is always on top */
    .modal.show .modal-dialog {
        transform: translate(0, 0) !important;
    }
</style>
<div class="row">
    <div class="col-12">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="widget-stat card bg-primary">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-white">
                                <p class="card-text">Total Leads</p>
                                <h3 class="card-title">{{ $totalLeads }}</h3>
                            </div>
                            <i class="fa fa-users fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="widget-stat card bg-info">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-white">
                                <p class="card-text">Incoming</p>
                                <h3 class="card-title">{{ $incomingLeads }}</h3>
                            </div>
                            <i class="fa fa-arrow-down fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="widget-stat card bg-warning">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-white">
                                <p class="card-text">Outgoing</p>
                                <h3 class="card-title">{{ $outgoingLeads }}</h3>
                            </div>
                            <i class="fa fa-arrow-up fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-sm-6">
                <div class="widget-stat card bg-success">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-white">
                                <p class="card-text">Interested</p>
                                <h3 class="card-title">{{ $interestedLeads }}</h3>
                            </div>
                            <i class="fa fa-heart fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">BDM - All Leads</h4>
                <div class="dropdown">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fa fa-plus"></i> Add New Lead
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('leads.create', 'incoming') }}">
                            <i class="fa fa-arrow-down me-2"></i> Add Incoming Lead
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('leads.create', 'outgoing') }}">
                            <i class="fa fa-arrow-up me-2"></i> Add Outgoing Lead
                        </a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Controls -->
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="filter_type" class="form-label">Filter by Type</label>
                        <select class="form-control" id="filter_type">
                            <option value="">All Types</option>
                            <option value="incoming">Incoming</option>
                            <option value="outgoing">Outgoing</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filter_customer" class="form-label">Filter by Customer</label>
                        <select class="form-control" id="filter_customer">
                            <option value="">All Customers</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer['display'] }}">{{ $customer['display'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filter_platform" class="form-label">Filter by Platform</label>
                        <select class="form-control" id="filter_platform">
                            <option value="">All Platforms</option>
                            <option value="website">Website</option>
                            <option value="facebook">Facebook</option>
                            <option value="instagram">Instagram</option>
                            <option value="linkedin">LinkedIn</option>
                            <option value="referral">Referral</option>
                            <option value="cold_call">Cold Call</option>
                            <option value="email">Email</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filter_status" class="form-label">Filter by Status</label>
                        <select class="form-control" id="filter_status">
                            <option value="">All Statuses</option>
                            <option value="new">New</option>
                            <option value="contacted">Contacted</option>
                            <option value="qualified">Qualified</option>
                            <option value="meeting_scheduled">Meeting Scheduled</option>
                            <option value="proposal_sent">Proposal Sent</option>
                            <option value="negotiation">Negotiation</option>
                            <option value="won">Won</option>
                            <option value="lost">Lost</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filter_remarks" class="form-label">Filter by Remarks</label>
                        <select class="form-control" id="filter_remarks">
                            <option value="">All Remarks</option>
                            @foreach($remarksOptions as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="clearAllFilters()">
                            <i class="fa fa-refresh"></i> Clear Filters
                        </button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table id="leadsTable" class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th style="width:50px;"><strong>S.NO</strong></th>
                                <th><strong>TYPE</strong></th>
                                <th><strong>DATE</strong></th>
                                <th><strong>TIME</strong></th>
                                <th><strong>PLATFORM</strong></th>
                                <th><strong>CUSTOMER NAME</strong></th>
                                <th><strong>PROJECT TYPE</strong></th>
                                <th><strong>PROJECT VALUATION</strong></th>
                                <th><strong>STATUS</strong></th>
                                <th><strong>REMARKS</strong></th>
                                <th><strong>ACTIONS</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leads as $index => $lead)
                            <tr>
                                <td><strong>{{ $index + 1 }}</strong></td>
                                <td>
                                    @if($lead->type === 'incoming')
                                        <span class="badge badge-info"><i class="fa fa-arrow-down me-1"></i>Incoming</span>
                                    @else
                                        <span class="badge badge-warning"><i class="fa fa-arrow-up me-1"></i>Outgoing</span>
                                    @endif
                                </td>
                                <td>{{ $lead->date ? $lead->date->format('d M Y') : $lead->created_at->format('d M Y') }}</td>
                                <td>{{ $lead->time ? $lead->time->format('H:i') : $lead->created_at->format('H:i') }}</td>
                                <td>
                                    <span class="badge badge-primary">{{ ucfirst($lead->platform_custom ?? $lead->platform) }}</span>
                                </td>
                                <td>
                                    <div>
                                        <h6 class="mb-0">{{ $lead->customer_name }}</h6>
                                        <small class="text-muted">{{ $lead->phone_number }}</small>
                                        @if($lead->email)
                                            <br><small class="text-info">{{ $lead->email }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ ucfirst(str_replace('_', ' ', $lead->project_type)) }}</td>
                                <td>
                                    @if($lead->project_valuation)
                                        <strong class="text-success">₹{{ number_format($lead->project_valuation, 2) }}</strong>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'new' => 'primary',
                                            'contacted' => 'info',
                                            'callback_scheduled' => 'warning',
                                            'interested' => 'success',
                                            'not_interested' => 'danger',
                                            'meeting_scheduled' => 'success',
                                            'did_not_receive' => 'warning',
                                            'not_required' => 'secondary'
                                        ];
                                        $statusColor = $statusColors[$lead->status] ?? 'secondary';
                                        $statusText = ucfirst(str_replace('_', ' ', $lead->status));
                                    @endphp
                                    <span class="badge badge-{{ $statusColor }}">{{ $statusText }}</span>
                                    
                                    @if($lead->status == 'callback_scheduled' && $lead->callback_time)
                                        <br><small class="text-muted">📞 {{ \Carbon\Carbon::parse($lead->callback_time)->format('d M, h:i A') }}</small>
                                    @elseif($lead->status == 'meeting_scheduled' && $lead->meeting_time)
                                        <br><small class="text-success">📅 {{ \Carbon\Carbon::parse($lead->meeting_time)->format('d M, h:i A') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($lead->remarks)
                                        <span class="badge badge-light-secondary">{{ $lead->remarks }}</span>
                                    @else
                                        <span class="text-muted">No remarks</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <!-- Show "Complete Callback" button if callback is scheduled -->
                                        @if($lead->status === 'callback_scheduled' && $lead->callback_time)
                                            <div class="btn-group">
                                                <button class="btn btn-gradient-success btn-xs" onclick="completeCallback({{ $lead->id }})" title="Complete Callback">
                                                    <i class="fa fa-check-circle"></i>
                                                </button>
                                                <button type="button" class="btn btn-success btn-xs dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="editCallbackInList({{ $lead->id }}, '{{ $lead->callback_time }}', '{{ addslashes($lead->call_notes ?? '') }}')">
                                                        <i class="fa fa-edit text-primary"></i> Edit Callback
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="postponeCallbackInList({{ $lead->id }}, '{{ $lead->callback_time }}')">
                                                        <i class="fa fa-clock text-warning"></i> Postpone
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="cancelCallback({{ $lead->id }})">
                                                        <i class="fa fa-times"></i> Cancel
                                                    </a></li>
                                                </ul>
                                            </div>
                                        @endif
                                        
                                        <!-- Show Complete Meeting button if meeting is scheduled -->
                                        @if($lead->status === 'meeting_scheduled' && $lead->meeting_time)
                                            <div class="btn-group">
                                                @if(!$lead->meeting_completed)
                                                <button class="btn btn-success btn-xs" onclick="openCompleteMeetingModal({{ $lead->id }})" title="Complete Meeting">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                @endif
                                                <button type="button" class="btn btn-info btn-xs dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                                    <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="editMeetingInList({{ $lead->id }}, '{{ $lead->meeting_time }}', '{{ addslashes($lead->meeting_address ?? '') }}', '{{ addslashes($lead->meeting_person_name ?? '') }}', '{{ addslashes($lead->meeting_phone_number ?? '') }}', '{{ addslashes($lead->meeting_summary ?? '') }}')">
                                                        <i class="fa fa-edit text-primary"></i> Edit Meeting
                                                    </a></li>
                                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="postponeMeetingInList({{ $lead->id }}, '{{ $lead->meeting_time }}')">
                                                        <i class="fa fa-clock text-warning"></i> Postpone
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="cancelMeeting({{ $lead->id }})">
                                                        <i class="fa fa-times"></i> Cancel
                                                    </a></li>
                                                </ul>
                                            </div>
                                        @endif
                                        
                                        <!-- Action Buttons - Hide buttons that are already completed -->
                                        @if($lead->status !== 'callback_scheduled')
                                            <button class="btn btn-warning btn-xs" onclick="scheduleCallback({{ $lead->id }})" title="Call Back">
                                                <i class="fa fa-phone"></i>
                                            </button>
                                        @endif
                                        
                                        @if($lead->status !== 'meeting_scheduled')
                                            <button class="btn btn-info btn-xs" onclick="scheduleMeeting({{ $lead->id }})" title="Schedule Meeting">
                                                <i class="fa fa-calendar"></i>
                                            </button>
                                        @endif
                                        
                                        @if($lead->status !== 'did_not_receive')
                                            <button class="btn btn-secondary btn-xs" onclick="markDidNotReceive({{ $lead->id }})" title="Did Not Receive">
                                                <i class="fa fa-times-circle"></i>
                                            </button>
                                        @endif
                                        
                                        @if($lead->status !== 'not_required')
                                            <button class="btn btn-dark btn-xs" onclick="markNotRequired({{ $lead->id }})" title="Not Required">
                                                <i class="fa fa-ban"></i>
                                            </button>
                                        @endif
                                        
                                        @if($lead->status !== 'interested')
                                            <button class="btn btn-success btn-xs" onclick="markInterested({{ $lead->id }})" title="Interested">
                                                <i class="fa fa-thumbs-up"></i>
                                            </button>
                                        @endif
                                        
                                        @if($lead->status !== 'not_interested')
                                            <button class="btn btn-danger btn-xs" onclick="markNotInterested({{ $lead->id }})" title="Not Interested">
                                                <i class="fa fa-thumbs-down"></i>
                                            </button>
                                        @endif
                                        
                                        <!-- Always show View Details -->
                                        <button class="btn btn-primary btn-xs" onclick="viewDetails({{ $lead->id }})" title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fa fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No leads found</h5>
                                        <p class="text-muted">Get started by adding your first lead.</p>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('leads.create', 'incoming') }}" class="btn btn-primary">
                                                <i class="fa fa-arrow-down me-2"></i> Add Incoming Lead
                                            </a>
                                            <a href="{{ route('leads.create', 'outgoing') }}" class="btn btn-warning">
                                                <i class="fa fa-arrow-up me-2"></i> Add Outgoing Lead
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($leads->hasPages())
                    <div class="mt-4">
                        {{ $leads->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Complete Meeting Modal -->
<div class="modal fade" id="completeMeetingModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">✅ Complete Meeting - What Happened?</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="completeMeetingForm">
                <input type="hidden" id="complete_meeting_lead_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Meeting Summary</label>
                        <textarea class="form-control" id="meeting_completed_summary" rows="4" placeholder="Brief notes on what happened" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Summary</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Callback Modal -->
<div class="modal fade" id="callbackModal" tabindex="-1" role="dialog" aria-labelledby="callbackModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="callbackModalLabel">Schedule Callback</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeCallbackModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="callbackForm" action="javascript:void(0);" onsubmit="return false;">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="callback_date">Callback Date & Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="callback_date" name="callback_date" required>
                        <small class="text-muted">This will appear in Dashboard → Upcoming Work section</small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="callback_notes">Notes (Optional)</label>
                        <textarea class="form-control" id="callback_notes" name="callback_notes" rows="3" placeholder="Enter any notes about this callback..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeCallbackModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule Callback</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Meeting Modal -->
<div class="modal fade" id="meetingModal" tabindex="-1" role="dialog" aria-labelledby="meetingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="meetingModalLabel">Schedule Meeting</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeMeetingModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="meetingForm" action="javascript:void(0);" onsubmit="return false;">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="meeting_date">Meeting Date & Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="meeting_date" name="meeting_date" required>
                                <small class="text-muted">Maximum 3 meetings per day</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="meeting_person_name">Person Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="meeting_person_name" name="meeting_person_name" required placeholder="Name of person attending">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="meeting_phone_number">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="meeting_phone_number" name="meeting_phone_number" required placeholder="Contact number">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="meeting_address">Meeting Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="meeting_address" name="meeting_address" rows="2" required placeholder="Full meeting address"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="meeting_summary">Brief Summary of Discussion <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="meeting_summary" name="meeting_summary" rows="3" required placeholder="Brief discussion summary or agenda"></textarea>
                        <small class="text-muted">Email notification will be sent to customer and BDM (bdm.konnectixtech@gmail.com)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeMeetingModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule Meeting</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Callback Modal -->
<div class="modal fade" id="editCallbackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fa fa-edit"></i> Edit Callback</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCallbackForm">
                <input type="hidden" id="edit_callback_lead_id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="edit_callback_date">Callback Date & Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="edit_callback_date" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_callback_notes">Notes</label>
                        <textarea class="form-control" id="edit_callback_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update Callback</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Meeting Modal -->
<div class="modal fade" id="editMeetingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fa fa-edit"></i> Edit Meeting</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editMeetingForm">
                <input type="hidden" id="edit_meeting_lead_id">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="edit_meeting_date">Meeting Date & Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="edit_meeting_date" required>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="edit_meeting_person_name">Person Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_meeting_person_name" required>
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label for="edit_meeting_phone_number">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_meeting_phone_number" required>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_meeting_address">Meeting Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="edit_meeting_address" rows="2" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="edit_meeting_summary">Meeting Summary <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="edit_meeting_summary" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Update Meeting</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Postpone Callback Modal -->
<div class="modal fade" id="postponeCallbackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fa fa-clock"></i> Postpone Callback</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="postponeCallbackForm">
                <input type="hidden" id="postpone_callback_lead_id">
                <div class="modal-body">
                    <div class="alert alert-info" id="postpone_callback_current"></div>
                    <div class="form-group mb-3">
                        <label for="postpone_callback_date">New Callback Date & Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="postpone_callback_date" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="postpone_callback_reason">Reason for Postponing <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="postpone_callback_reason" rows="3" required placeholder="Why is this callback being postponed?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Postpone Callback</button>
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
                <h5 class="modal-title"><i class="fa fa-clock"></i> Postpone Meeting</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="postponeMeetingForm">
                <input type="hidden" id="postpone_meeting_lead_id">
                <input type="hidden" id="postpone_meeting_address">
                <input type="hidden" id="postpone_meeting_person">
                <input type="hidden" id="postpone_meeting_phone">
                <div class="modal-body">
                    <div class="alert alert-info" id="postpone_meeting_current"></div>
                    <div class="form-group mb-3">
                        <label for="postpone_meeting_date">New Meeting Date & Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="postpone_meeting_date" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="postpone_meeting_reason">Reason for Postponing <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="postpone_meeting_reason" rows="3" required placeholder="Why is this meeting being postponed?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Postpone Meeting</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Global variables and CSRF token setup
let currentLeadId = null;
window.Laravel = window.Laravel || {};
window.Laravel.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Expose modal helpers globally so onclick handlers (outside document.ready)
// can call them before jQuery's document.ready runs.
window.showModalById = function(id) {
    var modalEl = document.getElementById(id);
    if (!modalEl) return;
    if (modalEl.parentElement !== document.body) document.body.appendChild(modalEl);
    var modal = new bootstrap.Modal(modalEl, { backdrop: true, keyboard: true });
    modal.show();
    return modal;
};

window.hideAllModals = function() {
    var openModals = document.querySelectorAll('.modal.show');
    openModals.forEach(function(el) {
        var inst = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
        try { inst.hide(); } catch(e) { }
    });
};

// Function to clean up modal backdrops - AGGRESSIVE CLEANUP
window.cleanupModalBackdrops = function() {
    // Get all backdrops
    var backdrops = $('.modal-backdrop');
    
    // If more than one, remove all except the first
    if (backdrops.length > 1) {
        backdrops.slice(1).remove();
    }
    
    // Also check body children
    $('body').children('.modal-backdrop:not(:first)').remove();
    
    // Remove modal-open class if no modals are showing
    if ($('.modal.show').length === 0) {
        $('body').removeClass('modal-open');
        $('body').css('padding-right', '');
        // Remove any remaining backdrops
        $('.modal-backdrop').remove();
    }
}

// Function to ensure only one backdrop exists
window.ensureSingleBackdrop = function() {
    var backdrops = $('.modal-backdrop');
    if (backdrops.length > 1) {
        // Keep only the last one (most recently added)
        backdrops.slice(0, -1).remove();
    }
    
    // Also remove from body children
    var bodyBackdrops = $('body').children('.modal-backdrop');
    if (bodyBackdrops.length > 1) {
        bodyBackdrops.slice(0, -1).remove();
    }
}

// Global functions for onclick handlers
window.scheduleCallback = function(leadId) {
    console.log('Scheduling callback for lead:', leadId);
    currentLeadId = leadId;
    
    // Clean up any existing modals and backdrops first
    hideAllModals();
    cleanupModalBackdrops();
    
    // Wait for cleanup to complete
    setTimeout(function() {
        // Ensure all backdrops are removed
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('padding-right', '');
        
        // Reset form and set minimum date
        document.getElementById('callbackForm').reset();
        const now = new Date();
        const minDateTime = now.toISOString().slice(0, 16);
        document.getElementById('callback_date').min = minDateTime;
        
        // Show modal using helper
        showModalById('callbackModal');
        
        // After modal shows, ensure only one backdrop
        setTimeout(function() {
            ensureSingleBackdrop();
        }, 100);
    }, 200);
}

window.scheduleMeeting = function(leadId) {
    console.log('Scheduling meeting for lead:', leadId);
    currentLeadId = leadId;
    
    // Clean up any existing modals and backdrops first
    hideAllModals();
    cleanupModalBackdrops();
    
    // Wait for cleanup to complete
    setTimeout(function() {
        // Ensure all backdrops are removed
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        $('body').css('padding-right', '');
        
        // Reset form and set minimum date
        document.getElementById('meetingForm').reset();
        const now = new Date();
        const minDateTime = now.toISOString().slice(0, 16);
        document.getElementById('meeting_date').min = minDateTime;
        
        // Show modal using helper
        showModalById('meetingModal');
        
        // After modal shows, ensure only one backdrop
        setTimeout(function() {
            ensureSingleBackdrop();
        }, 100);
    }, 200);
}

// Edit Callback from list
window.editCallbackInList = function(leadId, callbackTime, notes) {
    currentLeadId = leadId;
    document.getElementById('edit_callback_lead_id').value = leadId;
    document.getElementById('edit_callback_date').value = new Date(callbackTime).toISOString().slice(0, 16);
    document.getElementById('edit_callback_notes').value = notes || '';
    showModalById('editCallbackModal');
}

// Edit Meeting from list
window.editMeetingInList = function(leadId, meetingTime, address, personName, phoneNumber, summary) {
    currentLeadId = leadId;
    document.getElementById('edit_meeting_lead_id').value = leadId;
    document.getElementById('edit_meeting_date').value = new Date(meetingTime).toISOString().slice(0, 16);
    document.getElementById('edit_meeting_address').value = address || '';
    document.getElementById('edit_meeting_person_name').value = personName || '';
    document.getElementById('edit_meeting_phone_number').value = phoneNumber || '';
    document.getElementById('edit_meeting_summary').value = summary || '';
    showModalById('editMeetingModal');
}

// Postpone Callback from list
window.postponeCallbackInList = function(leadId, currentTime) {
    currentLeadId = leadId;
    document.getElementById('postpone_callback_lead_id').value = leadId;
    document.getElementById('postpone_callback_current').innerHTML = `<i class="fa fa-info-circle"></i> Current callback: <strong>${new Date(currentTime).toLocaleString()}</strong>`;
    const now = new Date();
    document.getElementById('postpone_callback_date').min = now.toISOString().slice(0, 16);
    showModalById('postponeCallbackModal');
}

// Postpone Meeting from list
window.postponeMeetingInList = function(leadId, currentTime) {
    currentLeadId = leadId;
    document.getElementById('postpone_meeting_lead_id').value = leadId;
    document.getElementById('postpone_meeting_current').innerHTML = `<i class="fa fa-info-circle"></i> Current meeting: <strong>${new Date(currentTime).toLocaleString()}</strong>`;
    const now = new Date();
    document.getElementById('postpone_meeting_date').min = now.toISOString().slice(0, 16);
    showModalById('postponeMeetingModal');
}

// Cancel Callback
window.cancelCallback = function(leadId) {
    if (confirm('Are you sure you want to cancel this callback? This action cannot be undone.')) {
        fetch(`/leads/${leadId}/cancel-callback`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Callback cancelled successfully!');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', 'Error cancelling callback: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while cancelling the callback.');
        });
    }
}

// Cancel Meeting
window.cancelMeeting = function(leadId) {
    if (confirm('Are you sure you want to cancel this meeting? This action cannot be undone.')) {
        fetch(`/leads/${leadId}/cancel-meeting`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Meeting cancelled successfully!');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', 'Error cancelling meeting: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while cancelling the meeting.');
        });
    }
}

window.markDidNotReceive = function(leadId) {
    console.log('Marking lead as did not receive:', leadId);
    if (confirm('Mark this lead as "Did Not Receive"? This will be added to your Did Not Receive Call List.')) {
        fetch(`/leads/${leadId}/update-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: 'did_not_receive'
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text || 'Server error');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('success', 'Lead marked as "Did Not Receive" and added to your call list.');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', 'Error updating status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', error.message || 'An error occurred while updating the status.');
        });
    }
}

window.markNotRequired = function(leadId) {
    console.log('Marking lead as not required:', leadId);
    if (confirm('Mark this lead as "Not Required"? This means the customer does not need the service.')) {
        fetch(`/leads/${leadId}/update-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: 'not_required'
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text || 'Server error');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('success', 'Lead marked as "Not Required".');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', 'Error updating status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', error.message || 'An error occurred while updating the status.');
        });
    }
}

window.markInterested = function(leadId) {
    console.log('Marking lead as interested:', leadId);
    if (confirm('Mark this lead as "Interested"?')) {
        fetch(`/leads/${leadId}/update-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: 'interested'
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text || 'Server error');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('success', 'Lead marked as "Interested".');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', 'Error updating status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', error.message || 'An error occurred while updating the status.');
        });
    }
}

window.markNotInterested = function(leadId) {
    console.log('Marking lead as not interested:', leadId);
    if (confirm('Mark this lead as "Not Interested"?')) {
        fetch(`/leads/${leadId}/update-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: 'not_interested'
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text || 'Server error');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('success', 'Lead marked as "Not Interested".');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', 'Error updating status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', error.message || 'An error occurred while updating the status.');
        });
    }
}

window.viewDetails = function(leadId) {
    console.log('Viewing details for lead:', leadId);
    // Redirect to lead details page or show modal
    window.location.href = `/leads/${leadId}`;
}

window.closeCallbackModal = function() {
    console.log('Closing callback modal');
    var modalInstance = bootstrap.Modal.getInstance(document.getElementById('callbackModal'));
    if (modalInstance) modalInstance.hide();
    document.getElementById('callbackForm').reset();
    // Clean up backdrop after modal closes
    setTimeout(function() {
        cleanupModalBackdrops();
    }, 300);
}

window.closeMeetingModal = function() {
    console.log('Closing meeting modal');
    var modalInstance = bootstrap.Modal.getInstance(document.getElementById('meetingModal'));
    if (modalInstance) modalInstance.hide();
    document.getElementById('meetingForm').reset();
    // Clean up backdrop after modal closes
    setTimeout(function() {
        cleanupModalBackdrops();
    }, 300);
}

window.clearAllFilters = function() {
    $('#filter_type').val('');
    $('#filter_customer').val('');
    $('#filter_platform').val('');
    $('#filter_status').val('');
    $('#filter_remarks').val('');
    
    // If DataTables is initialized, clear the filters
    if (typeof $.fn.DataTable !== 'undefined' && $('#leadsTable').DataTable()) {
        var table = $('#leadsTable').DataTable();
        table.search('').columns().search('').draw();
    }
}

window.showAlert = function(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    // Remove existing alerts and add new one
    $('.alert').remove();
    $('.card').first().before(alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}

// Document ready initialization
$(document).ready(function() {
    
    // CRITICAL: Prevent multiple modal backdrops
    $(document).on('show.bs.modal', '.modal', function() {
        // Remove any existing backdrops BEFORE showing
        $('.modal-backdrop').remove();
        ensureSingleBackdrop();
    });
    
    $(document).on('shown.bs.modal', '.modal', function() {
        // After modal is shown, ensure only one backdrop exists
        ensureSingleBackdrop();
        
        // Remove any duplicates that might have been created
        var backdrops = $('.modal-backdrop');
        if (backdrops.length > 1) {
            backdrops.slice(0, -1).remove();
        }
    });
    
    $(document).on('hide.bs.modal', '.modal', function() {
        // Clean up duplicates when hiding
        ensureSingleBackdrop();
    });
    
    $(document).on('hidden.bs.modal', '.modal', function() {
        // Complete cleanup after modal is fully hidden
        cleanupModalBackdrops();
    });
    
    // Watch for backdrop creation and remove duplicates immediately
    var observer = new MutationObserver(function(mutations) {
        var backdrops = $('.modal-backdrop');
        if (backdrops.length > 1) {
            console.log('MutationObserver: Removing', backdrops.length - 1, 'duplicate backdrop(s)');
            backdrops.slice(0, -1).remove();
        }
        
        // Ensure body only has one backdrop child
        var bodyBackdrops = $('body').children('.modal-backdrop');
        if (bodyBackdrops.length > 1) {
            bodyBackdrops.slice(0, -1).remove();
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
    
    // Also run periodic cleanup when modals are open
    var backdropCleanupInterval = setInterval(function() {
        if ($('.modal.show').length > 0) {
            var backdrops = $('.modal-backdrop');
            if (backdrops.length > 1) {
                console.log('Interval cleanup: Removing', backdrops.length - 1, 'duplicate backdrop(s)');
                backdrops.slice(0, -1).remove();
            }
        } else {
            // Stop interval when no modals are open
            clearInterval(backdropCleanupInterval);
        }
    }, 200);

    // Helper to show modal via Bootstrap 5 and ensure it's appended to body
    function showModalById(id) {
        var modalEl = document.getElementById(id);
        if (!modalEl) return;
        if (modalEl.parentElement !== document.body) document.body.appendChild(modalEl);
        var modal = new bootstrap.Modal(modalEl, { backdrop: true, keyboard: true });
        modal.show();
        return modal;
    }

    // Helper to hide all open modals using Bootstrap 5
    function hideAllModals() {
        var openModals = document.querySelectorAll('.modal.show');
        openModals.forEach(function(el) {
            var inst = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
            try { inst.hide(); } catch(e) { }
        });
    }
    
    // Initialize DataTables if available
    if (typeof $.fn.DataTable !== 'undefined') {
        try {
            var table = $('#leadsTable').DataTable({
                "order": [[ 2, "desc" ]],
                "pageLength": 25,
                "responsive": true,
                "dom": 'rtip'
            });

            // Custom filtering functions
            $('#filter_type').on('change', function() {
                var filterValue = this.value;
                table.column(1).search(filterValue).draw();
            });

            $('#filter_customer').on('change', function() {
                var filterValue = this.value;
                if (filterValue === '') {
                    table.column(5).search('').draw();
                } else {
                    var customerName = filterValue.split(' - ')[0];
                    table.column(5).search(customerName).draw();
                }
            });

            $('#filter_platform').on('change', function() {
                var filterValue = this.value;
                table.column(4).search(filterValue).draw();
            });
            
            $('#filter_status').on('change', function() {
                var filterValue = this.value;
                table.column(8).search(filterValue).draw();
            });

            $('#filter_remarks').on('change', function() {
                var filterValue = this.value;
                table.column(9).search(filterValue).draw();
            });
        } catch (error) {
            console.log('DataTables initialization error:', error);
        }
    }

    // Handle callback form submission
    $('#callbackForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        
        if (!currentLeadId) {
            showAlert('error', 'No lead selected');
            return;
        }
        
        console.log('Submitting callback form for lead:', currentLeadId);
        
        fetch(`/leads/${currentLeadId}/schedule-callback`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                callback_time: callbackDate,
                call_notes: callbackNotes
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text || 'Server error');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('success', data.message + ' This will appear in your dashboard under upcoming work.');
                var inst = bootstrap.Modal.getInstance(document.getElementById('callbackModal')) || new bootstrap.Modal(document.getElementById('callbackModal'));
                inst.hide();
                cleanupModalBackdrops();
                document.getElementById('callbackForm').reset();
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', 'Error scheduling callback: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Callback error:', error);
            showAlert('error', error.message || 'An error occurred while scheduling the callback.');
        });
    });

    // Handle meeting form submission
    $('#meetingForm').off('submit').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (!currentLeadId) {
            showAlert('error', 'No lead selected');
            return;
        }
        
        // Validate all required fields
        const meetingDate = $('#meeting_date').val();
        const meetingAddress = $('#meeting_address').val();
        const personName = $('#meeting_person_name').val();
        const phoneNumber = $('#meeting_phone_number').val();
        const summary = $('#meeting_summary').val();
        
        if (!meetingDate || !meetingAddress || !personName || !phoneNumber || !summary) {
            showAlert('error', 'All fields are required for scheduling a meeting.');
            return;
        }
        
        console.log('Submitting meeting form for lead:', currentLeadId);
        
        fetch(`/leads/${currentLeadId}/schedule-meeting`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                meeting_time: meetingDate,
                meeting_address: meetingAddress,
                meeting_person_name: personName,
                meeting_phone_number: phoneNumber,
                meeting_summary: summary
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text || 'Server error');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('success', data.message + ' Email notifications have been sent.');
                var inst = bootstrap.Modal.getInstance(document.getElementById('meetingModal')) || new bootstrap.Modal(document.getElementById('meetingModal'));
                inst.hide();
                cleanupModalBackdrops();
                document.getElementById('meetingForm').reset();
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Meeting error:', error);
            showAlert('error', error.message || 'An error occurred while scheduling the meeting.');
        });
    });
    
    // Complete meeting flow
    window.openCompleteMeetingModal = function(leadId) {
        currentLeadId = leadId;
        document.getElementById('complete_meeting_lead_id').value = leadId;
        showModalById('completeMeetingModal');
    };

    $('#completeMeetingForm').on('submit', function(e) {
        e.preventDefault();
        const summary = $('#meeting_completed_summary').val().trim();
        if (!summary) {
            showAlert('error', 'Please enter a brief meeting summary.');
            return;
        }
        fetch(`/leads/${currentLeadId}/complete-meeting`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ summary })
        }).then(r => {
            if (!r.ok) return r.text().then(t => { throw new Error(t || 'Server error'); });
            return r.json();
        }).then(data => {
            if (data.success) {
                showAlert('success', data.message);
                hideAllModals();
                cleanupModalBackdrops();
                setTimeout(() => location.reload(), 1200);
            } else {
                showAlert('error', data.message || 'Could not complete meeting.');
            }
        }).catch(err => {
            console.error(err);
            showAlert('error', err.message || 'An error occurred.');
        });
    });

    // Handle edit callback form submission
    $('#editCallbackForm').on('submit', function(e) {
        e.preventDefault();
        const leadId = document.getElementById('edit_callback_lead_id').value;
        
        fetch(`/leads/${leadId}/schedule-callback`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                callback_time: $('#edit_callback_date').val(),
                call_notes: $('#edit_callback_notes').val()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Callback updated successfully!');
                hideAllModals();
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', 'Error updating callback: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while updating the callback.');
        });
    });

    // Handle edit meeting form submission
    $('#editMeetingForm').on('submit', function(e) {
        e.preventDefault();
        const leadId = document.getElementById('edit_meeting_lead_id').value;
        
        fetch(`/leads/${leadId}/schedule-meeting`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                meeting_time: $('#edit_meeting_date').val(),
                meeting_address: $('#edit_meeting_address').val(),
                meeting_person_name: $('#edit_meeting_person_name').val(),
                meeting_phone_number: $('#edit_meeting_phone_number').val(),
                meeting_summary: $('#edit_meeting_summary').val()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Meeting updated successfully!');
                hideAllModals();
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', 'Error updating meeting: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while updating the meeting.');
        });
    });

    // Handle postpone callback form submission
    $('#postponeCallbackForm').on('submit', function(e) {
        e.preventDefault();
        const leadId = document.getElementById('postpone_callback_lead_id').value;
        
        fetch(`/leads/${leadId}/schedule-callback`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                callback_time: $('#postpone_callback_date').val(),
                call_notes: 'Postponed: ' + $('#postpone_callback_reason').val()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Callback postponed successfully!');
                hideAllModals();
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', 'Error postponing callback: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while postponing the callback.');
        });
    });

    // Handle postpone meeting form submission
    $('#postponeMeetingForm').on('submit', function(e) {
        e.preventDefault();
        const leadId = document.getElementById('postpone_meeting_lead_id').value;
        
        // Get existing meeting details from hidden fields or form
        const address = document.getElementById('postpone_meeting_address').value || $('#edit_meeting_address').val();
        const personName = document.getElementById('postpone_meeting_person').value || $('#edit_meeting_person_name').val();
        const phoneNumber = document.getElementById('postpone_meeting_phone').value || $('#edit_meeting_phone_number').val();
        
        fetch(`/leads/${leadId}/schedule-meeting`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                meeting_time: $('#postpone_meeting_date').val(),
                meeting_address: address,
                meeting_person_name: personName,
                meeting_phone_number: phoneNumber,
                meeting_summary: 'Postponed: ' + $('#postpone_meeting_reason').val()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Meeting postponed successfully!');
                hideAllModals();
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', 'Error postponing meeting: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while postponing the meeting.');
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.border-left-primary { border-left: 4px solid #007bff; }
.border-left-warning { border-left: 4px solid #ffc107; }
.border-left-info { border-left: 4px solid #17a2b8; }
.border-left-success { border-left: 4px solid #28a745; }
.border-left-danger { border-left: 4px solid #dc3545; }
.border-left-secondary { border-left: 4px solid #6c757d; }

.btn-xs {
    padding: 0.15rem 0.3rem;
    font-size: 0.75rem;
    border-radius: 0.15rem;
}

.gap-1 > * {
    margin: 2px;
}

.table th {
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.widget-stat .card-header {
    padding: 1.5rem;
}

.empty-state {
    text-align: center;
    padding: 2rem;
}
</style>
@endpush