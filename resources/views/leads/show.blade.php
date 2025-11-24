@extends('layouts.app')

@section('title', $lead->customer_name . ' - Lead Details')

@section('page-title', 'Lead Details')

@section('content')

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Lead Information</h4>
                <div class="dropdown">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-cog"></i> Actions
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('leads.edit', $lead) }}">
                            <i class="fa fa-edit text-primary"></i> Edit Lead
                        </a>
                        <a class="dropdown-item" href="#" onclick="scheduleCallback()">
                            <i class="fa fa-phone text-info"></i> Schedule Callback
                        </a>
                        <a class="dropdown-item" href="#" onclick="scheduleMeeting()">
                            <i class="fa fa-calendar text-warning"></i> Schedule Meeting
                        </a>
                        <a class="dropdown-item" href="#" onclick="updateStatus()">
                            <i class="fa fa-check-circle text-success"></i> Update Status
                        </a>
                        @if($lead->status !== 'won' && $lead->status !== 'converted')
                        <a class="dropdown-item" href="#" onclick="convertToCustomer()">
                            <i class="fa fa-user-plus text-success"></i> Convert to Customer
                        </a>
                        @endif
                    </div>
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

                <!-- Lead Details Grid -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label text-muted">Platform/Source</label>
                            <div>
                                <span class="badge badge-primary">{{ ucfirst($lead->platform) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label text-muted">Project Type</label>
                            <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $lead->project_type)) }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label text-muted">Project Valuation</label>
                            <p class="mb-0">
                                @if($lead->project_valuation)
                                    <strong>₹{{ number_format($lead->project_valuation, 2) }}</strong>
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label text-muted">Assigned To</label>
                            <p class="mb-0">
                                @if($lead->assignedUser)
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-2">
                                            <span class="avatar-title rounded-circle bg-soft-info text-info">
                                                {{ strtoupper(substr($lead->assignedUser->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <span>{{ $lead->assignedUser->name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted">Unassigned</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label text-muted">Current Status</label>
                            <div>
                                @switch($lead->status)
                                    @case('new')
                                        <span class="badge badge-light-info">New</span>
                                        @break
                                    @case('contacted')
                                        <span class="badge badge-light-warning">Contacted</span>
                                        @break
                                    @case('qualified')
                                        <span class="badge badge-light-primary">Qualified</span>
                                        @break
                                    @case('meeting_scheduled')
                                        <span class="badge badge-light-secondary">Meeting Scheduled</span>
                                        @break
                                    @case('proposal_sent')
                                        <span class="badge badge-light-info">Proposal Sent</span>
                                        @break
                                    @case('negotiation')
                                        <span class="badge badge-light-warning">Negotiation</span>
                                        @break
                                    @case('won')
                                        <span class="badge badge-light-success">Won</span>
                                        @break
                                    @case('lost')
                                        <span class="badge badge-light-danger">Lost</span>
                                        @break
                                    @case('converted')
                                        <span class="badge badge-light-success">Converted to Customer</span>
                                        @break
                                    @default
                                        <span class="badge badge-light-secondary">{{ ucfirst($lead->status) }}</span>
                                @endswitch
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label text-muted">Date Added</label>
                            <p class="mb-0">{{ $lead->created_at->format('d M Y, g:i A') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label text-muted">Last Updated</label>
                            <p class="mb-0">{{ $lead->updated_at->format('d M Y, g:i A') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label text-muted">Days in System</label>
                            <p class="mb-0">
                                <span class="badge badge-light-primary">{{ $daysInSystem }} days</span>
                            </p>
                        </div>
                    </div>
                </div>

                @if($lead->remarks)
                <div class="row">
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label class="form-label text-muted">Remarks/Notes</label>
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <p class="mb-0">{{ $lead->remarks }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Scheduled Activities -->
                @if($lead->callback_time || $lead->meeting_time)
                <hr class="my-4">
                <h5 class="mb-3">Scheduled Activities</h5>
                <div class="row">
                    @if($lead->callback_time)
                    <div class="col-md-6">
                        <div class="card border-warning">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fa fa-phone fa-2x text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Next Callback</h6>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($lead->callback_time)->format('d M Y, g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($lead->meeting_time)
                    <div class="col-md-6">
                        <div class="card border-info">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fa fa-calendar fa-2x text-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Next Meeting</h6>
                                        <p class="mb-0">{{ \Carbon\Carbon::parse($lead->meeting_time)->format('d M Y, g:i A') }}</p>
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

        <!-- Activity Timeline -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Activity Timeline</h4>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-date">{{ $lead->created_at->format('d M Y, g:i A') }}</div>
                        <div class="timeline-content">
                            <h6>Lead Created</h6>
                            <p>Lead was added to the system via {{ ucfirst($lead->platform) }}</p>
                            <small class="text-muted">System</small>
                        </div>
                    </div>
                    
                    @if($lead->callback_time)
                    <div class="timeline-item">
                        <div class="timeline-date">{{ \Carbon\Carbon::parse($lead->callback_time)->format('d M Y, g:i A') }}</div>
                        <div class="timeline-content">
                            <h6>Callback Scheduled</h6>
                            <p>Follow-up call scheduled</p>
                            <small class="text-muted">{{ $lead->assignedUser->name ?? 'System' }}</small>
                        </div>
                    </div>
                    @endif

                    @if($lead->meeting_time)
                    <div class="timeline-item">
                        <div class="timeline-date">{{ \Carbon\Carbon::parse($lead->meeting_time)->format('d M Y, g:i A') }}</div>
                        <div class="timeline-content">
                            <h6>Meeting Scheduled</h6>
                            <p>Client meeting scheduled</p>
                            <small class="text-muted">{{ $lead->assignedUser->name ?? 'System' }}</small>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Quick Actions</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <button class="btn btn-primary" onclick="scheduleCallback()">
                        <i class="fa fa-phone me-2"></i> Schedule Callback
                    </button>
                    <button class="btn btn-info" onclick="scheduleMeeting()">
                        <i class="fa fa-calendar me-2"></i> Schedule Meeting
                    </button>
                    <button class="btn btn-warning" onclick="updateStatus()">
                        <i class="fa fa-check-circle me-2"></i> Update Status
                    </button>
                    @if($lead->status !== 'won' && $lead->status !== 'converted')
                    <button class="btn btn-success" onclick="convertToCustomer()">
                        <i class="fa fa-user-plus me-2"></i> Convert to Customer
                    </button>
                    @endif
                    <a href="{{ route('leads.edit', $lead) }}" class="btn btn-secondary">
                        <i class="fa fa-edit me-2"></i> Edit Lead
                    </a>
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

        <!-- Contact Information -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Contact Information</h4>
            </div>
            <div class="card-body">
                <div class="contact-info">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="fa fa-envelope text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <a href="mailto:{{ $lead->email }}" class="text-decoration-none">{{ $lead->email }}</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="fa fa-phone text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <a href="tel:{{ $lead->phone_number }}" class="text-decoration-none">{{ $lead->phone_number }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include the same modals from incoming.blade.php -->
<!-- Schedule Callback Modal -->
<div class="modal fade" id="callbackModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Callback</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="callbackForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="callback_date">Callback Date & Time</label>
                        <input type="datetime-local" class="form-control" id="callback_date" name="callback_date" required>
                    </div>
                    <div class="form-group">
                        <label for="callback_notes">Notes</label>
                        <textarea class="form-control" id="callback_notes" name="notes" rows="3"></textarea>
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
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Meeting</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="meetingForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="meeting_date">Meeting Date & Time</label>
                        <input type="datetime-local" class="form-control" id="meeting_date" name="meeting_date" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="meeting_person_name">Person Name</label>
                            <input type="text" class="form-control" id="meeting_person_name" name="meeting_person_name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="meeting_phone_number">Phone Number</label>
                            <input type="text" class="form-control" id="meeting_phone_number" name="meeting_phone_number" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="meeting_address">Meeting Address</label>
                        <textarea class="form-control" id="meeting_address" name="meeting_address" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="meeting_summary">Meeting Summary</label>
                        <textarea class="form-control" id="meeting_summary" name="meeting_summary" rows="3" required></textarea>
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
                            <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>New</option>
                            <option value="contacted" {{ $lead->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="qualified" {{ $lead->status === 'qualified' ? 'selected' : '' }}>Qualified</option>
                            <option value="meeting_scheduled" {{ $lead->status === 'meeting_scheduled' ? 'selected' : '' }}>Meeting Scheduled</option>
                            <option value="proposal_sent" {{ $lead->status === 'proposal_sent' ? 'selected' : '' }}>Proposal Sent</option>
                            <option value="negotiation" {{ $lead->status === 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                            <option value="won" {{ $lead->status === 'won' ? 'selected' : '' }}>Won</option>
                            <option value="lost" {{ $lead->status === 'lost' ? 'selected' : '' }}>Lost</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status_notes">Notes</label>
                        <textarea class="form-control" id="status_notes" name="notes" rows="3"></textarea>
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
function scheduleCallback() {
    hideAllModals();
    showModalById('callbackModal');
}

function scheduleMeeting() {
    hideAllModals();
    showModalById('meetingModal');
}

function updateStatus() {
    hideAllModals();
    showModalById('statusModal');
}

function convertToCustomer() {
    if (confirm('Are you sure you want to convert this lead to a customer?')) {
        fetch(`/leads/{{ $lead->id }}/convert-to-customer`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Lead converted to customer successfully!');
                location.reload();
            } else {
                alert('Error converting lead: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while converting the lead.');
        });
    }
}

// Handle callback form submission
$('#callbackForm').on('submit', function(e) {
    e.preventDefault();
    
    fetch(`/leads/{{ $lead->id }}/schedule-callback`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            callback_time: $('#callback_date').val(),
            call_notes: $('#callback_notes').val()
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Callback scheduled successfully!');
            var inst = bootstrap.Modal.getInstance(document.getElementById('callbackModal')) || new bootstrap.Modal(document.getElementById('callbackModal'));
            inst.hide();
            location.reload();
        } else {
            alert('Error scheduling callback: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while scheduling the callback.');
    });
});

// Handle meeting form submission
$('#meetingForm').on('submit', function(e) {
    e.preventDefault();
    
    fetch(`/leads/{{ $lead->id }}/schedule-meeting`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            meeting_time: $('#meeting_date').val(),
            meeting_address: $('#meeting_address').val(),
            meeting_person_name: $('#meeting_person_name').val(),
            meeting_phone_number: $('#meeting_phone_number').val(),
            meeting_summary: $('#meeting_summary').val()
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Meeting scheduled successfully!');
            var inst = bootstrap.Modal.getInstance(document.getElementById('meetingModal')) || new bootstrap.Modal(document.getElementById('meetingModal'));
            inst.hide();
            location.reload();
        } else {
            alert('Error scheduling meeting: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while scheduling the meeting.');
    });
});

// Handle status form submission
$('#statusForm').on('submit', function(e) {
    e.preventDefault();
    
    fetch(`/leads/{{ $lead->id }}/update-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            status: $('#status').val(),
            notes: $('#status_notes').val()
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Status updated successfully!');
            var inst = bootstrap.Modal.getInstance(document.getElementById('statusModal')) || new bootstrap.Modal(document.getElementById('statusModal'));
            inst.hide();
            location.reload();
        } else {
            alert('Error updating status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status.');
    });
});

// Bootstrap 5 modal helpers
function showModalById(id) {
    var modalEl = document.getElementById(id);
    if (!modalEl) return;
    if (modalEl.parentElement !== document.body) document.body.appendChild(modalEl);
    var modal = new bootstrap.Modal(modalEl, { backdrop: true, keyboard: true });
    modal.show();
    return modal;
}

function hideAllModals() {
    var openModals = document.querySelectorAll('.modal.show');
    openModals.forEach(function(el) {
        var inst = bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
        try { inst.hide(); } catch(e) { }
    });
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #007bff;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
    padding-left: 30px;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -19px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #007bff;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #007bff;
}

.timeline-date {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 5px;
}

.timeline-content h6 {
    margin-bottom: 10px;
    color: #343a40;
}

.timeline-content p {
    margin-bottom: 5px;
    color: #6c757d;
}

.btn-block {
    width: 100%;
    margin-bottom: 10px;
}
</style>
@endsection