@extends('layouts.app')

@section('title', 'All Leads - Konnectix BDM')

@section('page-title', 'BDM - All Leads Management')

@section('content')
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

function viewDetails(leadId) {
    console.log('Viewing details for lead:', leadId);
    // Redirect to lead details page or show modal
    window.location.href = `/leads/${leadId}`;
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

function clearAllFilters() {
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

.widget-stat .card-header {
    padding: 1.5rem;
}

.empty-state {
    text-align: center;
    padding: 2rem;
}
</style>
@endpush