@extends('layouts.app')

@section('title', 'Outgoing Leads - BDM Panel')

@section('page-title', 'Outgoing Leads Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">BDM - Outgoing Leads</h4>
                <a href="{{ route('leads.create', 'outgoing') }}" class="btn btn-primary btn-sm">
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
                    <div class="col-md-3">
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

                <!-- Outgoing Leads Table -->
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
                                    <span class="badge badge-primary">{{ ucfirst($lead->platform) }}</span>
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
                                    <div class="d-flex gap-1">
                                        @if($lead->status !== 'callback_scheduled')
                                            <button class="btn btn-info btn-xs" onclick="scheduleCallback({{ $lead->id }})" title="Schedule Callback">
                                                <i class="fa fa-phone"></i>
                                            </button>
                                        @endif
                                        
                                        @if($lead->status !== 'meeting_scheduled')
                                            <button class="btn btn-warning btn-xs" onclick="scheduleMeeting({{ $lead->id }})" title="Schedule Meeting">
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
                                <td colspan="10" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fa fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No outgoing leads found</h5>
                                        <p class="text-muted">Get started by adding your first outgoing lead.</p>
                                        <a href="{{ route('leads.create', 'outgoing') }}" class="btn btn-primary">
                                            <i class="fa fa-plus me-2"></i> Add Your First Lead
                                        </a>
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

<!-- Schedule Callback Modal -->
<div class="modal fade" id="callbackModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Callback</h5>
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
<div class="modal fade" id="meetingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Meeting</h5>
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

<!-- Complete Callback Modal -->
<div class="modal fade" id="completeCallbackModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">✅ Complete Call Back - What Happened?</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="completeCallbackForm">
                @csrf
                <input type="hidden" name="lead_id" id="complete_callback_lead_id">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>📞 Call Back Status Update</strong><br>
                        You called the customer. Now tell us what happened so we can update the lead status.
                    </div>

                    <!-- Status Selection -->
                    <div class="mb-3">
                        <label class="form-label"><strong>What was the outcome of the call?</strong> <span class="text-danger">*</span></label>
                        <select class="form-control" name="new_status" id="callback_outcome" required>
                            <option value="">-- Select Outcome --</option>
                            <option value="interested">✅ Customer is Interested</option>
                            <option value="not_interested">❌ Customer is Not Interested</option>
                            <option value="meeting_scheduled">📅 Schedule a Meeting</option>
                            <option value="did_not_receive">📵 Customer Did Not Receive the Call</option>
                            <option value="not_required">🚫 Service Not Required</option>
                            <option value="callback_scheduled">🔄 Schedule Another Call Back</option>
                        </select>
                    </div>

                    <!-- Call Notes -->
                    <div class="mb-3">
                        <label class="form-label">Call Notes</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="What did you discuss? Any important points..."></textarea>
                    </div>

                    <!-- Conditional Fields Based on Outcome -->
                    
                    <!-- If Another Callback -->
                    <div id="another_callback_fields" style="display: none;">
                        <div class="alert alert-warning">
                            <strong>🔄 Scheduling Another Call Back</strong>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Next Call Back Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" name="callback_time">
                        </div>
                    </div>

                    <!-- If Meeting Scheduled -->
                    <div id="meeting_fields" style="display: none;">
                        <div class="alert alert-success">
                            <strong>📅 Great! Let's Schedule the Meeting</strong>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Meeting Date & Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" name="meeting_time">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Person Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meeting_person_name" placeholder="Contact person">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meeting_phone_number" placeholder="Meeting contact number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Meeting Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="meeting_address" placeholder="Meeting location">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Meeting Summary <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="meeting_summary" rows="2" placeholder="What will be discussed?"></textarea>
                        </div>
                        <div class="alert alert-info">
                            <small><strong>⚠️ Note:</strong> Maximum 3 meetings per day. Emails will be sent to customer and admin.</small>
                        </div>
                    </div>

                    <!-- Success Messages Based on Selection -->
                    <div id="interested_message" class="alert alert-success" style="display: none;">
                        <strong>✅ Great!</strong> This lead will be marked as "Interested" and you can create a proposal for them later.
                    </div>
                    <div id="not_interested_message" class="alert alert-danger" style="display: none;">
                        <strong>❌ Noted.</strong> Lead will be saved with "Not Interested" status for records.
                    </div>
                    <div id="did_not_receive_message" class="alert alert-warning" style="display: none;">
                        <strong>📵 No Problem.</strong> You can schedule another callback or mark as "Did Not Receive".
                    </div>
                    <div id="not_required_message" class="alert alert-secondary" style="display: none;">
                        <strong>🚫 Understood.</strong> Lead will be saved with "Not Required" status for records.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Complete & Update Status
                    </button>
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

// Global functions for onclick handlers
function scheduleCallback(leadId) {
    console.log('Scheduling callback for lead:', leadId);
    currentLeadId = leadId;
    
    // Reset form and set minimum date
    document.getElementById('callbackForm').reset();
    const now = new Date();
    const minDateTime = now.toISOString().slice(0, 16);
    document.getElementById('callback_date').min = minDateTime;
    
    // Show modal
    $('#callbackModal').modal('show');
}

function scheduleMeeting(leadId) {
    console.log('Scheduling meeting for lead:', leadId);
    currentLeadId = leadId;
    
    // Reset form and set minimum date
    document.getElementById('meetingForm').reset();
    const now = new Date();
    const minDateTime = now.toISOString().slice(0, 16);
    document.getElementById('meeting_date').min = minDateTime;
    
    // Show modal
    $('#meetingModal').modal('show');
}

function markDidNotReceive(leadId) {
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

function markNotRequired(leadId) {
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

function markInterested(leadId) {
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

function markNotInterested(leadId) {
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
    window.location.href = `/leads/${leadId}`;
}

function convertToCustomer(leadId) {
    if (confirm('Are you sure you want to convert this lead to a customer? This action will move the details to Customer Management section.')) {
        fetch(`/leads/${leadId}/convert-to-customer`, {
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
    $('#callbackModal').modal('hide');
    document.getElementById('callbackForm').reset();
}

function closeMeetingModal() {
    console.log('Closing meeting modal');
    $('#meetingModal').modal('hide');
    document.getElementById('meetingForm').reset();
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

// Document ready initialization
$(document).ready(function() {
    
    // Initialize DataTables if available
    if (typeof $.fn.DataTable !== 'undefined') {
        try {
            var table = $('#leadsTable').DataTable({
                "order": [[ 1, "desc" ]],
                "pageLength": 25,
                "responsive": true,
                "dom": 'rtip'
            });

            // Custom filtering functions
            $('#filter_customer').on('change', function() {
                var filterValue = this.value;
                if (filterValue === '') {
                    table.column(4).search('').draw();
                } else {
                    var customerName = filterValue.split(' - ')[0];
                    table.column(4).search(customerName).draw();
                }
            });

            $('#filter_platform').on('change', function() {
                var filterValue = this.value;
                table.column(3).search(filterValue).draw();
            });
            
            $('#filter_status').on('change', function() {
                var filterValue = this.value;
                table.column(7).search(filterValue).draw();
            });

            $('#filter_remarks').on('keyup change', function() {
                var filterValue = this.value;
                table.column(8).search(filterValue).draw();
            });
        } catch (error) {
            console.log('DataTables initialization error:', error);
        }
    }

    // Handle callback form submission
    $('#callbackForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!currentLeadId) {
            showAlert('error', 'No lead selected');
            return;
        }
        
        const formData = {
            callback_time: $('#callback_date').val(),
            call_notes: $('#callback_notes').val()
        };
        
        console.log('Submitting callback form for lead:', currentLeadId, formData);
        
        fetch(`/leads/${currentLeadId}/schedule-callback`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
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
            closeCallbackModal();
            if (data.success) {
                showAlert('success', 'Callback scheduled successfully!');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', 'Error scheduling callback: ' + data.message);
            }
        })
        .catch(error => {
            closeCallbackModal();
            console.error('Callback error:', error);
            showAlert('error', error.message || 'An error occurred while scheduling the callback.');
        });
    });

    // Handle meeting form submission
    $('#meetingForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!currentLeadId) {
            showAlert('error', 'No lead selected');
            return;
        }
        
        const formData = {
            meeting_time: $('#meeting_date').val(),
            meeting_person_name: $('#meeting_person_name').val(),
            meeting_phone_number: $('#meeting_phone_number').val(),
            meeting_address: $('#meeting_address').val(),
            meeting_summary: $('#meeting_summary').val()
        };
        
        console.log('Submitting meeting form for lead:', currentLeadId, formData);
        
        fetch(`/leads/${currentLeadId}/schedule-meeting`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(formData)
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
            closeMeetingModal();
            if (data.success) {
                showAlert('success', 'Meeting scheduled successfully! Email notifications sent.');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', 'Error scheduling meeting: ' + data.message);
            }
        })
        .catch(error => {
            closeMeetingModal();
            console.error('Meeting error:', error);
            showAlert('error', error.message || 'An error occurred while scheduling the meeting.');
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
</style>
@endpush
