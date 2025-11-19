@extends('layouts.app')

@section('title', 'Incoming Leads - Konnectix')

@section('page-title', 'Leads Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Incoming Leads</h4>
                <div>
                    <button onclick="window.testButton()" class="btn btn-info btn-sm me-2">
                        <i class="fa fa-bug"></i> Test JS
                    </button>
                    <a href="{{ route('leads.create', 'incoming') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i> Add New Lead
                    </a>
                </div>
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
                                    @if($lead->remarks)
                                        <span class="badge badge-light-secondary">{{ $lead->remarks }}</span>
                                    @else
                                        <span class="text-muted">No remarks</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-primary btn-sm" onclick="console.log('Callback clicked for lead {{ $lead->id }}'); scheduleCallback({{ $lead->id }})">
                                            <i class="fa fa-phone"></i> Call Back
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm" onclick="console.log('Did not receive clicked for lead {{ $lead->id }}'); markDidNotReceive({{ $lead->id }})">
                                            <i class="fa fa-phone-slash"></i> Did Not Receive
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="console.log('Not required clicked for lead {{ $lead->id }}'); markNotRequired({{ $lead->id }})">
                                            <i class="fa fa-times"></i> Not Required
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" onclick="console.log('Meeting clicked for lead {{ $lead->id }}'); scheduleMeeting({{ $lead->id }})">
                                            <i class="fa fa-calendar"></i> Meeting
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
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

<!-- Schedule Callback Modal -->
<div class="modal fade" id="callbackModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Callback</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                        <small class="text-muted">Email notification will be sent to customer and admin (bdm.konnectixtech@gmail.com)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule Meeting</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Lead Status</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="statusForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
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
                    <div class="form-group">
                        <label for="status_remarks">Remarks</label>
                        <select class="form-control" id="status_remarks_dropdown" onchange="handleStatusRemarksChange()">
                            <option value="">Select Remarks</option>
                            @foreach($remarksOptions as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                            <option value="custom">Custom...</option>
                        </select>
                        <textarea class="form-control mt-2" id="status_notes" name="notes" rows="3" style="display: none;" placeholder="Enter custom remarks..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Global variables
let currentLeadId = null;

// Ensure CSRF token is available
window.Laravel = window.Laravel || {};
window.Laravel.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Global functions for onclick handlers
// Schedule Callback
function scheduleCallback(leadId) {
    console.log('Scheduling callback for lead:', leadId);
    currentLeadId = leadId;
    
    // Reset form
    document.getElementById('callbackForm').reset();
    
    // Set minimum date to current date and time
    const now = new Date();
    const minDateTime = now.toISOString().slice(0, 16);
    document.getElementById('callback_date').min = minDateTime;
    
    // Show modal
    $('#callbackModal').modal('show');
}

// Schedule Meeting
function scheduleMeeting(leadId) {
    console.log('Scheduling meeting for lead:', leadId);
    currentLeadId = leadId;
    
    // Reset form
    document.getElementById('meetingForm').reset();
    
    // Set minimum date to current date and time
    const now = new Date();
    const minDateTime = now.toISOString().slice(0, 16);
    document.getElementById('meeting_date').min = minDateTime;
    
    // Show modal
    $('#meetingModal').modal('show');
}

// Mark as Did Not Receive
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
        .then(response => response.json())
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
            showAlert('error', 'An error occurred while updating the status.');
        });
    }
}

// Mark as Not Required
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
        .then(response => response.json())
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
            showAlert('error', 'An error occurred while updating the status.');
        });
    }
}

// Convert to Customer
function convertToCustomer(leadId) {
    if (confirm('Are you sure you want to convert this lead to a customer? This action will move the details to Customer Management section.')) {
        fetch(`/leads/${leadId}/convert-to-customer`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
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
            showAlert('error', 'An error occurred while converting the lead.');
        });
    }
}

// Alert function for better user feedback
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
    
    // Remove existing alerts
    $('.alert').remove();
    
    // Add new alert at the top of the content area
    $('.card').first().before(alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}

// Wait for DOM to be fully loaded
$(document).ready(function() {
    console.log('Incoming leads JavaScript loaded');
    
    // Check if required libraries are loaded
    if (typeof $ === 'undefined') {
        console.error('jQuery not loaded');
        return;
    }
    
    console.log('jQuery loaded successfully');
    
    if (typeof $.fn.DataTable === 'undefined') {
        console.error('DataTables not loaded - using basic table functionality');
        // Add basic filtering without DataTables
        $('#filter_customer, #filter_platform, #filter_remarks').on('change', function() {
            console.log('Basic filtering not implemented yet');
        });
    } else {
        console.log('DataTables loaded successfully');
        try {
            var table = $('#leadsTable').DataTable({
                "order": [[ 1, "desc" ]], // Order by DATE column
                "pageLength": 25,
                "responsive": true,
                "dom": 'rtip' // Remove default search and show controls since we have custom filters
            });

            // Custom filtering functions
            $('#filter_customer').on('change', function() {
                var filterValue = this.value;
                if (filterValue === '') {
                    table.column(4).search('').draw(); // Column 4 is CUSTOMER NAME
                } else {
                    // Extract just the name part before the hyphen for filtering
                    var customerName = filterValue.split(' - ')[0];
                    table.column(4).search(customerName).draw();
                }
            });

            $('#filter_platform').on('change', function() {
                var filterValue = this.value;
                table.column(3).search(filterValue).draw(); // Column 3 is PLATFORM
            });

            $('#filter_remarks').on('change', function() {
                var filterValue = this.value;
                table.column(7).search(filterValue).draw(); // Column 7 is REMARKS
            });
        } catch (error) {
            console.error('Error initializing DataTables:', error);
        }
    }
    
    // Test button functionality
    window.testButton = function() {
        console.log('Test button clicked');
        alert('JavaScript is working!');
    };
    
    // Fallback functions for debugging
    window.debugScheduleCallback = function(leadId) {
        alert('Schedule Callback clicked for lead: ' + leadId);
    };
    
    window.debugMarkDidNotReceive = function(leadId) {
        alert('Mark Did Not Receive clicked for lead: ' + leadId);
    };
    
    window.debugMarkNotRequired = function(leadId) {
        alert('Mark Not Required clicked for lead: ' + leadId);
    };
    
    window.debugScheduleMeeting = function(leadId) {
        alert('Schedule Meeting clicked for lead: ' + leadId);
    };
    
    // Handle callback form submission
    $('#callbackForm').on('submit', function(e) {
        e.preventDefault();
        
        const callbackDate = $('#callback_date').val();
        const callbackNotes = $('#callback_notes').val();
        
        if (!callbackDate) {
            showAlert('error', 'Callback date and time is required.');
            return;
        }
        
        fetch(`/leads/${currentLeadId}/schedule-callback`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                callback_date: callbackDate,
                callback_notes: callbackNotes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message + ' This will appear in your dashboard under upcoming work.');
                $('#callbackModal').modal('hide');
                document.getElementById('callbackForm').reset();
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', 'Error scheduling callback: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while scheduling the callback.');
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
        
        fetch(`/leads/${currentLeadId}/schedule-meeting`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                meeting_date: meetingDate,
                meeting_address: meetingAddress,
                meeting_person_name: personName,
                meeting_phone_number: phoneNumber,
                meeting_summary: summary
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message + ' Email notifications have been sent.');
                $('#meetingModal').modal('hide');
                document.getElementById('meetingForm').reset();
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while scheduling the meeting.');
        });
    });
    
    console.log('All JavaScript functions loaded');
});
</script>
@endsection
    console.log('Scheduling callback for lead:', leadId);
    currentLeadId = leadId;
    
    // Reset form
    document.getElementById('callbackForm').reset();
    
    // Set minimum date to current date and time
    const now = new Date();
    const minDateTime = now.toISOString().slice(0, 16);
    document.getElementById('callback_date').min = minDateTime;
    
    // Show modal
    $('#callbackModal').modal('show');
}

// Schedule Meeting
function scheduleMeeting(leadId) {
    console.log('Scheduling meeting for lead:', leadId);
    currentLeadId = leadId;
    
    // Reset form
    document.getElementById('meetingForm').reset();
    
    // Set minimum date to current date and time
    const now = new Date();
    const minDateTime = now.toISOString().slice(0, 16);
    document.getElementById('meeting_date').min = minDateTime;
    
    // Show modal
    $('#meetingModal').modal('show');
}

// Mark as Did Not Receive
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
        .then(response => response.json())
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
            showAlert('error', 'An error occurred while updating the status.');
        });
    }
}

// Mark as Not Required
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
        .then(response => response.json())
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
            showAlert('error', 'An error occurred while updating the status.');
        });
    }
}

// Convert to Customer
function convertToCustomer(leadId) {
    if (confirm('Are you sure you want to convert this lead to a customer? This action will move the details to Customer Management section.')) {
        fetch(`/leads/${leadId}/convert-to-customer`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                location.reload();
            } else {
                showAlert('error', 'Error converting lead: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while converting the lead.');
        });
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
    
    fetch(`/leads/${currentLeadId}/schedule-callback`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.Laravel.csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            callback_date: callbackDate,
            callback_notes: callbackNotes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message + ' This will appear in your dashboard under upcoming work.');
            $('#callbackModal').modal('hide');
            document.getElementById('callbackForm').reset();
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('error', 'Error scheduling callback: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while scheduling the callback.');
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
    
    fetch(`/leads/${currentLeadId}/schedule-meeting`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.Laravel.csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            meeting_date: meetingDate,
            meeting_address: meetingAddress,
            meeting_person_name: personName,
            meeting_phone_number: phoneNumber,
            meeting_summary: summary
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message + ' Email notifications have been sent.');
            $('#meetingModal').modal('hide');
            document.getElementById('meetingForm').reset();
            setTimeout(() => location.reload(), 1500);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while scheduling the meeting.');
    });
});

// Alert function for better user feedback
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
    
    // Remove existing alerts
    $('.alert').remove();
    
    // Add new alert at the top of the content area
    $('.card').first().before(alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endsection