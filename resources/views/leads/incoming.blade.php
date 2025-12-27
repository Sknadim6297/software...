@extends('layouts.app')

@section('title', 'Incoming Leads - Konnectix BDM')

@section('page-title', 'BDM - Leads Management')

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
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">BDM - Incoming Leads</h4>
                <a href="{{ route('leads.create', 'incoming') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Add New Lead
                </a>
            </div>
            <div class="card-body">
                <!-- Filter Controls -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="filter_customer" class="form-label">Filter by Customer</label>
                        <select class="form-control" id="filter_customer">
                            <option value="">All Customers</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer['display'] }}">{{ $customer['display'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_platform" class="form-label">Filter by Platform</label>
                        <select class="form-control" id="filter_platform">
                            <option value="">All Platforms</option>
                            @foreach($platformOptions as $platform)
                                <option value="{{ $platform }}">{{ ucfirst(str_replace('_', ' ', $platform)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_status" class="form-label">Filter by Status</label>
                        <select class="form-control" id="filter_status">
                            <option value="">All Statuses</option>
                            @foreach($statusOptions as $status)
                                <option value="{{ $status }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_remarks" class="form-label">Filter by Remarks</label>
                        <select class="form-control" id="filter_remarks">
                            <option value="">All Remarks</option>
                            @foreach($remarksOptions as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12 d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-sm" onclick="applyFilters()">
                            <i class="fa fa-search"></i> Search
                        </button>
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
                                        @if($lead->status === 'callback_scheduled')
                                            <button class="btn btn-gradient-success btn-xs" onclick="completeCallback({{ $lead->id }})" title="Complete Callback">
                                                <i class="fa fa-check-circle"></i>
                                            </button>
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
                                        @else
                                            @if(!$lead->meeting_completed)
                                            <button class="btn btn-success btn-xs" onclick="openCompleteMeetingModal({{ $lead->id }})" title="Mark as Completed">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            @endif
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
                                <td colspan="10" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fa fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No incoming leads found</h5>
                                        <p class="text-muted">Get started by adding your first incoming lead.</p>
                                        <a href="{{ route('leads.create', 'incoming') }}" class="btn btn-primary">
                                            <i class="fa fa-plus me-2"></i> Add Your First Lead
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
            <form id="callbackForm">
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
            <form id="meetingForm">
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
@endsection

@push('scripts')
<script>
// Global variables and CSRF token setup
let currentLeadId = null;
window.Laravel = window.Laravel || {};
window.Laravel.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Function to clean up modal backdrops - AGGRESSIVE CLEANUP
function cleanupModalBackdrops() {
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
function ensureSingleBackdrop() {
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
function scheduleCallback(leadId) {
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
        
        // Show modal using Bootstrap 5 helper
        showModalById('callbackModal');
        
        // After modal shows, ensure only one backdrop
        setTimeout(function() {
            ensureSingleBackdrop();
        }, 100);
    }, 200);
}

function scheduleMeeting(leadId) {
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
        
        // Show modal using Bootstrap 5 helper
        showModalById('meetingModal');
        
        // After modal shows, ensure only one backdrop
        setTimeout(function() {
            ensureSingleBackdrop();
        }, 100);
    }, 200);
}

function markDidNotReceive(leadId) {
    console.log('Marking lead as did not receive:', leadId);
    if (confirm('Mark this lead as "Did Not Receive"? This will be added to your Did Not Receive Call List.')) {
        fetch(`{{ url('/') }}/leads/${leadId}/update-status`, {
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

function markNotRequired(leadId) {
    console.log('Marking lead as not required:', leadId);
    if (confirm('Mark this lead as "Not Required"? This means the customer does not need the service.')) {
        fetch(`{{ url('/') }}/leads/${leadId}/update-status`, {
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

function markInterested(leadId) {
    console.log('Opening GST modal for lead:', leadId);
    currentLeadId = leadId;
    
    // Reset form and show modal
    document.getElementById('interestedGstForm').reset();
    $('#gst_number_group').hide();
    $('#gst_payment_group').hide();
    $('#gst_number, #invoice_gst_number, #gst_email').prop('required', false);
    
    var modal = new bootstrap.Modal(document.getElementById('interestedGstModal'));
    modal.show();
}

function markNotInterested(leadId) {
    console.log('Marking lead as not interested:', leadId);
    $('#not_interested_lead_id').val(leadId);
    $('#not_interested_reason').val('');
    const modal = new bootstrap.Modal(document.getElementById('notInterestedModal'));
    modal.show();
}

// Handle not interested form submission
$(document).ready(function() {
    $('#notInterestedForm').on('submit', function(e) {
        e.preventDefault();
        const leadId = $('#not_interested_lead_id').val();
        const reason = $('#not_interested_reason').val().trim();
        
        if (!reason) {
            showAlert('error', 'Please provide a reason.');
            return;
        }
        
        fetch(`{{ url('/') }}/leads/${leadId}/update-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: 'not_interested',
                not_interested_reason: reason
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
                bootstrap.Modal.getInstance(document.getElementById('notInterestedModal')).hide();
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
    });
});

function completeCallback(leadId) {
    console.log('Opening complete callback modal for lead:', leadId);
    currentLeadId = leadId;
    
    // If you have a complete callback modal, show it here
    // For now, we'll redirect to outgoing leads where the complete callback functionality exists
    window.location.href = '/leads/outgoing';
}

function viewDetails(leadId) {
    console.log('Viewing details for lead:', leadId);
    // Redirect to lead details page or show modal
    window.location.href = `{{ url('/') }}/leads/${leadId}`;
}

function convertToCustomer(leadId) {
    if (confirm('Are you sure you want to convert this lead to a customer? This action will move the details to Customer Management section.')) {
        fetch(`{{ url('/') }}/leads/${leadId}/convert-to-customer`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
            },
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
                showAlert('success', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', 'Error converting lead: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', error.message || 'An error occurred while converting the lead.');
        });
    }
}

function closeCallbackModal() {
    console.log('Closing callback modal');
    var modalInstance = bootstrap.Modal.getInstance(document.getElementById('callbackModal'));
    if (modalInstance) modalInstance.hide();
    document.getElementById('callbackForm').reset();
    // Clean up backdrop after modal closes
    setTimeout(function() {
        cleanupModalBackdrops();
    }, 300);
}

function closeMeetingModal() {
    console.log('Closing meeting modal');
    var modalInstance = bootstrap.Modal.getInstance(document.getElementById('meetingModal'));
    if (modalInstance) modalInstance.hide();
    document.getElementById('meetingForm').reset();
    // Clean up backdrop after modal closes
    setTimeout(function() {
        cleanupModalBackdrops();
    }, 300);
}

function showAlert(type, message) {
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

// Use Bootstrap 5 modal API and ensure modals are appended to <body>
// Remove previous aggressive DOM manipulations which caused z-index/backdrop issues.
function showModalById(id) {
    // Move modal to body to avoid z-index context problems
    var modalEl = document.getElementById(id);
    if (!modalEl) return;
    if (modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }
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

// Document ready initialization
$(document).ready(function() {
    // Ensure only one backdrop after showing a modal
    $(document).on('shown.bs.modal', '.modal', function() {
        var backdrops = document.querySelectorAll('.modal-backdrop');
        if (backdrops.length > 1) {
            backdrops.forEach(function(b, idx) {
                if (idx < backdrops.length - 1) b.remove();
            });
        }
    });
    
    // Initialize DataTables if available
    if (typeof $.fn.DataTable !== 'undefined') {
        try {
            var table = $('#leadsTable').DataTable({
                "order": [[ 1, "desc" ]],
                "pageLength": 25,
                "responsive": true,
                "dom": 'rtip'
            });

            // Store table reference globally for filter functions
            window.leadsTable = document.getElementById('leadsTable');
        } catch (error) {
            console.error('Error initializing DataTables:', error);
        }
    }
    
    // Handle callback form submission
    $('#callbackForm').on('submit', function(e) {
        e.preventDefault();
        
        const callbackDate = $('#callback_date').val();
        const callbackNotes = $('#callback_notes').val();
        
        if (!callbackDate) {
            showAlert('error', 'Callback date and time is required.');
            return;
        }
        
        fetch(`{{ url('/') }}/leads/${currentLeadId}/schedule-callback`, {
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
            console.error('Error:', error);
            showAlert('error', error.message || 'An error occurred while scheduling the callback.');
        });
    });

    // Handle meeting form submission
    $('#meetingForm').on('submit', function(e) {
        e.preventDefault();
        
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
        
        fetch(`{{ url('/') }}/leads/${currentLeadId}/schedule-meeting`, {
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
            console.error('Error:', error);
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
        fetch(`{{ url('/') }}/leads/${currentLeadId}/complete-meeting`, {
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

    // Additional modal close handlers (use Bootstrap 5 instances)
    $('.modal .close, .modal .btn-secondary').on('click', function() {
        var modalEl = $(this).closest('.modal')[0];
        var modalId = $(modalEl).attr('id');
        console.log('Closing modal:', modalId);
        var inst = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        try { inst.hide(); } catch(e) { }

        // Reset forms when closing
        if (modalId === 'callbackModal') {
            document.getElementById('callbackForm').reset();
        } else if (modalId === 'meetingModal') {
            document.getElementById('meetingForm').reset();
        }

        // Clean up backdrop
        setTimeout(function() {
            cleanupModalBackdrops();
        }, 300);
    });
});

// Apply filters when search button is clicked
window.applyFilters = function() {
    if (!window.leadsTable) {
        console.log('Table not found');
        return;
    }
    
    var customerFilter = $('#filter_customer').val().toLowerCase();
    var platformFilter = $('#filter_platform').val().toLowerCase();
    var statusFilter = $('#filter_status').val().toLowerCase();
    var remarksFilter = $('#filter_remarks').val().toLowerCase();
    
    var rows = window.leadsTable.querySelectorAll('tbody tr');
    
    rows.forEach(function(row) {
        var cells = row.querySelectorAll('td');
        if (cells.length < 9) return;
        
        var platformText = cells[3].textContent.toLowerCase().trim();
        var customerText = cells[4].textContent.toLowerCase().trim();
        var statusText = cells[7].textContent.toLowerCase().trim();
        var remarksText = cells[8].textContent.toLowerCase().trim();
        
        // Extract customer name from "Name - Phone" format
        var customerName = customerFilter ? customerFilter.split(' - ')[0].toLowerCase() : '';
        
        // Convert status filter from underscore to space format for matching
        var statusFilterFormatted = statusFilter ? statusFilter.replace(/_/g, ' ') : '';
        
        var showRow = true;
        
        if (customerFilter && !customerText.includes(customerName)) showRow = false;
        if (platformFilter && !platformText.includes(platformFilter)) showRow = false;
        if (statusFilter && !statusText.includes(statusFilterFormatted)) showRow = false;
        if (remarksFilter && !remarksText.includes(remarksFilter)) showRow = false;
        
        row.style.display = showRow ? '' : 'none';
    });
}

window.clearAllFilters = function() {
    $('#filter_customer').val('');
    $('#filter_platform').val('');
    $('#filter_status').val('');
    $('#filter_remarks').val('');
    
    // Show all rows
    if (window.leadsTable) {
        var rows = window.leadsTable.querySelectorAll('tbody tr');
        rows.forEach(function(row) {
            row.style.display = '';
        });
    }
}

// Handle Interested/GST form submission
$('#interestedGstForm').on('submit', function(e) {
    e.preventDefault();
    
    if (!currentLeadId) {
        alert('No lead selected');
        return;
    }

    // Collect form data
    const formData = new FormData(this);
    const gstData = {
        status: 'interested',
        confirmed_email: formData.get('confirmed_email'),
        has_gst: formData.get('has_gst'),
        gst_number: formData.get('gst_number'),
        wants_gst: formData.get('wants_gst'),
        invoice_gst_number: formData.get('invoice_gst_number'),
        gst_email: formData.get('gst_email')
    };

    fetch(`{{ url('/') }}/leads/${currentLeadId}/update-interested-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.Laravel.csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(gstData)
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
            alert('Lead marked as interested with GST details saved!');
            $('#interestedGstModal').modal('hide');
            setTimeout(() => location.reload(), 1500);
        } else {
            alert('Error updating status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'An error occurred while updating the status.');
    });
});

// GST Modal Logic
$(document).on('change', 'input[name="has_gst"]', function() {
    if ($(this).val() === 'yes') {
        $('#gst_number_group').show();
        $('#gst_number').prop('required', true);
    } else {
        $('#gst_number_group').hide();
        $('#gst_number').prop('required', false).val('');
    }
});

$(document).on('change', 'input[name="wants_gst"]', function() {
    if ($(this).val() === 'yes') {
        $('#gst_payment_group').show();
        $('#invoice_gst_number, #gst_email').prop('required', false);
    } else {
        $('#gst_payment_group').hide();
        $('#invoice_gst_number, #gst_email').prop('required', false).val('');
    }
});

// Auto-save GST modal data to localStorage on input change
$('#interestedGstModal').on('input change', 'input, select, textarea', function() {
    const formData = {
        confirmed_email: $('#confirmed_email').val(),
        has_gst: $('input[name="has_gst"]:checked').val(),
        gst_number: $('#gst_number').val(),
        wants_gst: $('input[name="wants_gst"]:checked').val(),
        invoice_gst_number: $('#invoice_gst_number').val(),
        gst_email: $('#gst_email').val()
    };
    localStorage.setItem('gst_modal_draft_' + window.location.pathname, JSON.stringify(formData));
});

// Restore GST modal data when modal opens
$('#interestedGstModal').on('show.bs.modal', function() {
    const savedData = localStorage.getItem('gst_modal_draft_' + window.location.pathname);
    if (savedData) {
        try {
            const formData = JSON.parse(savedData);
            $('#confirmed_email').val(formData.confirmed_email || '');
            if (formData.has_gst) {
                $('input[name="has_gst"][value="' + formData.has_gst + '"]').prop('checked', true).trigger('change');
                $('#gst_number').val(formData.gst_number || '');
            }
            if (formData.wants_gst) {
                $('input[name="wants_gst"][value="' + formData.wants_gst + '"]').prop('checked', true).trigger('change');
                $('#invoice_gst_number').val(formData.invoice_gst_number || '');
                $('#gst_email').val(formData.gst_email || '');
            }
        } catch (e) {
            console.error('Error restoring GST modal data:', e);
        }
    }
});

// Clear saved data when form is successfully submitted
$('#interestedGstForm').on('submit', function() {
    localStorage.removeItem('gst_modal_draft_' + window.location.pathname);
});
</script>

<!-- GST Modal -->
<div class="modal fade" id="interestedGstModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Interest & GST Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="interestedGstForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Please collect the following information since the customer is interested:
                    </div>
                    
                    <!-- Email Confirmation -->
                    <div class="mb-3">
                        <label class="form-label">Please confirm customer's email address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="confirmed_email" name="confirmed_email" required>
                        <small class="text-muted">This is required for all interested customers</small>
                    </div>

                    <!-- GST Question 1 -->
                    <div class="mb-3">
                        <label class="form-label">Does the person have a GST number? <span class="text-danger">*</span></label>
                        <div>
                            <input type="radio" class="form-check-input" id="has_gst_yes" name="has_gst" value="yes" required>
                            <label class="form-check-label me-3" for="has_gst_yes">YES</label>
                            <input type="radio" class="form-check-input" id="has_gst_no" name="has_gst" value="no" required>
                            <label class="form-check-label" for="has_gst_no">NO</label>
                        </div>
                    </div>

                    <!-- GST Number (if has GST) -->
                    <div class="mb-3" id="gst_number_group" style="display: none;">
                        <label class="form-label">GST Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="gst_number" name="gst_number" placeholder="22AAAAA0000A1Z5">
                        <small class="text-muted">Format: 15 characters (e.g., 22AAAAA0000A1Z5)</small>
                    </div>

                    <!-- GST Question 2 -->
                    <div class="mb-3">
                        <label class="form-label">Does the customer want to pay GST? <span class="text-danger">*</span></label>
                        <div>
                            <input type="radio" class="form-check-input" id="wants_gst_yes" name="wants_gst" value="yes" required>
                            <label class="form-check-label me-3" for="wants_gst_yes">YES</label>
                            <input type="radio" class="form-check-input" id="wants_gst_no" name="wants_gst" value="no" required>
                            <label class="form-check-label" for="wants_gst_no">NO</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Mark as Interested & Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Not Interested Reason Modal -->
<div class="modal fade" id="notInterestedModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Not Interested - Reason Required</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="notInterestedForm">
                <input type="hidden" id="not_interested_lead_id">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i> Please provide a reason why the customer is not interested.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="not_interested_reason" name="reason" rows="4" required placeholder="Please explain why the customer is not interested..."></textarea>
                        <small class="text-muted">This helps understand customer concerns and improve future approaches.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Save & Mark Not Interested</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endpush
