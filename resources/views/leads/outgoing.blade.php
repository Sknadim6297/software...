@extends('layouts.app')

@section('title', 'Outgoing Leads - BDM Panel')

@section('page-title', 'Outgoing Leads Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Outgoing Leads - Follow-up Tracker</h4>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addOutgoingLeadModal">
                    <i class="fa fa-plus me-2"></i> Add Outgoing Lead
                </button>
            </div>
            <div class="card-body">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
                        <div class="card border-left-primary shadow-sm">
                            <div class="card-body py-2">
                                <div class="text-primary text-uppercase mb-1 small">Total Leads</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $leads->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
                        <div class="card border-left-warning shadow-sm">
                            <div class="card-body py-2">
                                <div class="text-warning text-uppercase mb-1 small">Callbacks</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $leads->where('status', 'callback_scheduled')->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
                        <div class="card border-left-info shadow-sm">
                            <div class="card-body py-2">
                                <div class="text-info text-uppercase mb-1 small">Meetings</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $leads->where('status', 'meeting_scheduled')->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
                        <div class="card border-left-success shadow-sm">
                            <div class="card-body py-2">
                                <div class="text-success text-uppercase mb-1 small">Interested</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $leads->where('status', 'interested')->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
                        <div class="card border-left-danger shadow-sm">
                            <div class="card-body py-2">
                                <div class="text-danger text-uppercase mb-1 small">Not Interested</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $leads->where('status', 'not_interested')->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6 mb-3">
                        <div class="card border-left-secondary shadow-sm">
                            <div class="card-body py-2">
                                <div class="text-secondary text-uppercase mb-1 small">No Response</div>
                                <div class="h5 mb-0 font-weight-bold">{{ $leads->where('status', 'did_not_receive')->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-3 mb-2">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="callback_scheduled">Call Back</option>
                            <option value="meeting_scheduled">Meeting</option>
                            <option value="did_not_receive">Did Not Receive</option>
                            <option value="not_required">Not Required</option>
                            <option value="not_interested">Not Interested</option>
                            <option value="interested">Interested</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="date" class="form-control" id="dateFilter" placeholder="Filter by Date">
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search customer name, phone...">
                    </div>
                    <div class="col-md-2 mb-2">
                        <button class="btn btn-secondary w-100" onclick="clearFilters()">Clear</button>
                    </div>
                </div>

                <!-- Outgoing Leads Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="outgoingLeadsTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 120px;">Date & Time</th>
                                <th style="width: 100px;">Platform</th>
                                <th>Customer Name</th>
                                <th>Contact</th>
                                <th>Project Type</th>
                                <th style="width: 120px;">Valuation</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th style="width: 280px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leads as $lead)
                            <tr>
                                <td><strong>{{ $loop->iteration }}</strong></td>
                                <td>
                                    <div><strong>{{ $lead->date->format('d M Y') }}</strong></div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($lead->time)->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-{{ 
                                        $lead->platform == 'website' ? 'primary' : 
                                        ($lead->platform == 'facebook' ? 'info' : 
                                        ($lead->platform == 'whatsapp' ? 'success' : 'secondary')) 
                                    }}">
                                        {{ ucfirst($lead->platform) }}
                                    </span>
                                </td>
                                <td><strong>{{ $lead->customer_name }}</strong></td>
                                <td>
                                    <div><a href="tel:{{ $lead->phone_number }}" class="text-decoration-none"><i class="fa fa-phone text-success"></i> {{ $lead->phone_number }}</a></div>
                                    @if($lead->email)
                                        <small><a href="mailto:{{ $lead->email }}" class="text-muted"><i class="fa fa-envelope"></i> {{ $lead->email }}</a></small>
                                    @endif
                                </td>
                                <td>{{ $lead->project_type ? ucwords(str_replace('_', ' ', $lead->project_type)) : '-' }}</td>
                                <td>
                                    @if($lead->project_valuation)
                                        <strong>₹{{ number_format($lead->project_valuation) }}</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($lead->status == 'callback_scheduled')
                                        <span class="badge badge-warning">Call Back</span>
                                        @if($lead->callback_time)
                                            <br><small class="text-muted">{{ \Carbon\Carbon::parse($lead->callback_time)->format('d M, h:i A') }}</small>
                                        @endif
                                    @elseif($lead->status == 'meeting_scheduled')
                                        <span class="badge badge-info">Meeting</span>
                                        @if($lead->meeting_time)
                                            <br><small class="text-muted">{{ \Carbon\Carbon::parse($lead->meeting_time)->format('d M, h:i A') }}</small>
                                        @endif
                                    @elseif($lead->status == 'did_not_receive')
                                        <span class="badge badge-secondary">Did Not Receive</span>
                                    @elseif($lead->status == 'not_required')
                                        <span class="badge badge-dark">Not Required</span>
                                    @elseif($lead->status == 'not_interested')
                                        <span class="badge badge-danger">Not Interested</span>
                                    @elseif($lead->status == 'interested')
                                        <span class="badge badge-success">Interested</span>
                                    @else
                                        <span class="badge badge-light">{{ ucfirst($lead->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($lead->remarks)
                                        <small>{{ Str::limit($lead->remarks, 30) }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        <!-- Show "Complete Callback" button if callback is scheduled -->
                                        @if($lead->status === 'callback_scheduled')
                                            <button class="btn btn-gradient-success btn-xs" onclick="completeCallback({{ $lead->id }})" title="Complete Callback">
                                                <i class="fa fa-check-circle"></i> Complete Call
                                            </button>
                                        @endif
                                        
                                        <!-- Action Buttons -->
                                        <button class="btn btn-warning btn-xs" onclick="setCallBack({{ $lead->id }})" title="Call Back">
                                            <i class="fa fa-phone"></i>
                                        </button>
                                        <button class="btn btn-info btn-xs" onclick="scheduleMeeting({{ $lead->id }})" title="Meeting">
                                            <i class="fa fa-calendar"></i>
                                        </button>
                                        <button class="btn btn-secondary btn-xs" onclick="setDidNotReceive({{ $lead->id }})" title="Did Not Receive">
                                            <i class="fa fa-times-circle"></i>
                                        </button>
                                        <button class="btn btn-dark btn-xs" onclick="setNotRequired({{ $lead->id }})" title="Not Required">
                                            <i class="fa fa-ban"></i>
                                        </button>
                                        <button class="btn btn-danger btn-xs" onclick="setNotInterested({{ $lead->id }})" title="Not Interested">
                                            <i class="fa fa-thumbs-down"></i>
                                        </button>
                                        <button class="btn btn-success btn-xs" onclick="setInterested({{ $lead->id }})" title="Interested">
                                            <i class="fa fa-thumbs-up"></i>
                                        </button>
                                        <button class="btn btn-primary btn-xs" onclick="viewDetails({{ $lead->id }})" title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No outgoing leads found. Click "Add Outgoing Lead" to get started.</p>
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

<!-- Add Outgoing Lead Modal -->
<div class="modal fade" id="addOutgoingLeadModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Outgoing Lead</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('leads.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="outgoing">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="time" value="{{ date('H:i') }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Platform <span class="text-danger">*</span></label>
                            <select class="form-control" name="platform" required>
                                <option value="">Select Platform</option>
                                <option value="website">Website</option>
                                <option value="facebook">Facebook</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="instagram">Instagram</option>
                                <option value="linkedin">LinkedIn</option>
                                <option value="cold_call">Cold Call</option>
                                <option value="email">Email</option>
                                <option value="referral">Referral</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="customer_name" placeholder="Enter customer name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="phone_number" placeholder="+91 9876543210" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="customer@example.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Type <span class="text-danger">*</span></label>
                            <select class="form-control" name="project_type" required>
                                <option value="">Select Project Type</option>
                                <option value="website">Website</option>
                                <option value="application">Application</option>
                                <option value="software">Software</option>
                                <option value="digital_marketing">Digital Marketing</option>
                                <option value="seo">SEO</option>
                                <option value="smo">SMO</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Valuation (₹)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control" name="project_valuation" placeholder="50000" min="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea class="form-control" name="remarks" rows="3" placeholder="Enter any notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Call Back Modal -->
<div class="modal fade" id="callBackModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">📞 Schedule Call Back</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="callBackForm">
                @csrf
                <input type="hidden" name="lead_id" id="callback_lead_id">
                <input type="hidden" name="action" value="callback">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Note:</strong> Select the exact date and time when you plan to call this customer again.
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Call Back Date & Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" name="callback_time" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="call_notes" rows="2" placeholder="Reason for callback..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Schedule Call Back</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Meeting Modal -->
<div class="modal fade" id="meetingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">📅 Schedule Meeting</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="meetingForm">
                @csrf
                <input type="hidden" name="lead_id" id="meeting_lead_id">
                <input type="hidden" name="action" value="meeting">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>⚠️ Limit:</strong> Maximum 3 meetings per day allowed.
                        <br><strong>Today's Meetings:</strong> <span id="todayMeetingCount">0</span> / 3
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meeting Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control" name="meeting_time" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Person Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="meeting_person_name" placeholder="Contact person name" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="meeting_phone_number" placeholder="+91 9876543210" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Meeting Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="meeting_address" placeholder="Meeting location" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meeting Summary <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="meeting_summary" rows="3" placeholder="Brief summary of what will be discussed..." required></textarea>
                    </div>
                    <div class="alert alert-success">
                        <strong>📧 Email Notification:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Customer will receive meeting details</li>
                            <li>Admin (bdm.konnectixtech@gmail.com) will be notified</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Schedule Meeting & Send Emails</button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
// Set Call Back
window.setCallBack = function(leadId) {
    document.getElementById('callback_lead_id').value = leadId;
    const modal = new bootstrap.Modal(document.getElementById('callBackModal'));
    modal.show();
}

// Complete Callback Function
window.completeCallback = function(leadId) {
    document.getElementById('complete_callback_lead_id').value = leadId;
    const modal = new bootstrap.Modal(document.getElementById('completeCallbackModal'));
    modal.show();
}

// Handle callback outcome selection change
const callbackOutcome = document.getElementById('callback_outcome');
if (callbackOutcome) {
    callbackOutcome.addEventListener('change', function() {
        // Hide all conditional fields and messages
        document.getElementById('another_callback_fields').style.display = 'none';
        document.getElementById('meeting_fields').style.display = 'none';
        document.getElementById('interested_message').style.display = 'none';
        document.getElementById('not_interested_message').style.display = 'none';
        document.getElementById('did_not_receive_message').style.display = 'none';
        document.getElementById('not_required_message').style.display = 'none';

        // Show relevant fields based on selection
        switch(this.value) {
            case 'callback_scheduled':
                document.getElementById('another_callback_fields').style.display = 'block';
                break;
            case 'meeting_scheduled':
                document.getElementById('meeting_fields').style.display = 'block';
                break;
            case 'interested':
                document.getElementById('interested_message').style.display = 'block';
                break;
            case 'not_interested':
                document.getElementById('not_interested_message').style.display = 'block';
                break;
            case 'did_not_receive':
                document.getElementById('did_not_receive_message').style.display = 'block';
                break;
            case 'not_required':
                document.getElementById('not_required_message').style.display = 'block';
                break;
        }
    });
}

// Schedule Meeting
window.scheduleMeeting = function(leadId) {
    // Check meeting limit
    fetch('/api/check-meeting-limit')
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    throw new Error(text || 'Server error');
                });
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('todayMeetingCount').textContent = data.count;
            if (data.count >= 3) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Meeting Limit Reached',
                    text: 'You have already scheduled 3 meetings today. Maximum limit reached.',
                    confirmButtonText: 'OK'
                });
                return;
            }
            document.getElementById('meeting_lead_id').value = leadId;
            const modal = new bootstrap.Modal(document.getElementById('meetingModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error checking meeting limit:', error);
            Swal.fire('Error!', error.message || 'Failed to check meeting limit', 'error');
        });
}

// Set Did Not Receive
window.setDidNotReceive = function(leadId) {
    updateLeadStatus(leadId, 'did_not_receive', 'Customer did not pick up the call');
}

// Set Not Required
window.setNotRequired = function(leadId) {
    updateLeadStatus(leadId, 'not_required', 'Customer does not need the service');
}

// Set Not Interested
window.setNotInterested = function(leadId) {
    updateLeadStatus(leadId, 'not_interested', 'Customer is not interested');
}

// Set Interested
window.setInterested = function(leadId) {
    updateLeadStatus(leadId, 'interested', 'Customer is interested - follow up required');
}

// View Details
window.viewDetails = function(leadId) {
    window.location.href = `/leads/${leadId}`;
}

// Update Lead Status
function updateLeadStatus(leadId, status, message) {
    Swal.fire({
        title: 'Confirm Status Update',
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Update',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/leads/${leadId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: status })
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
                    Swal.fire('Success!', 'Status updated successfully', 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Error!', data.message || 'Failed to update status', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error!', error.message || 'Something went wrong', 'error');
            });
        }
    });
}

// Call Back Form Submit
const callBackForm = document.getElementById('callBackForm');
if (callBackForm) {
    callBackForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const leadId = formData.get('lead_id');
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('callBackModal'));
        
        fetch(`/leads/${leadId}/schedule-callback`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
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
            if (modal) modal.hide();
            
            if (data.success) {
                Swal.fire('Success!', 'Call back scheduled successfully', 'success')
                    .then(() => location.reload());
            } else {
                Swal.fire('Error!', data.message || 'Failed to schedule callback', 'error');
            }
        })
        .catch(error => {
            if (modal) modal.hide();
            console.error('Callback error:', error);
            Swal.fire('Error!', error.message || 'Something went wrong', 'error');
        });
    });
}

// Meeting Form Submit
const meetingForm = document.getElementById('meetingForm');
if (meetingForm) {
    meetingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const leadId = formData.get('lead_id');
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('meetingModal'));
        
        fetch(`/leads/${leadId}/schedule-meeting`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
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
            if (modal) modal.hide();
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Meeting Scheduled!',
                    html: 'Meeting scheduled successfully.<br>📧 Email notifications sent to customer and admin.',
                    confirmButtonText: 'OK'
                }).then(() => location.reload());
            } else {
                Swal.fire('Error!', data.message || 'Failed to schedule meeting', 'error');
            }
        })
        .catch(error => {
            if (modal) modal.hide();
            console.error('Meeting error:', error);
            Swal.fire('Error!', error.message || 'Something went wrong', 'error');
        });
    });
}

// Complete Callback Form Handler
const completeCallbackForm = document.getElementById('completeCallbackForm');
if (completeCallbackForm) {
    completeCallbackForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const leadId = document.getElementById('complete_callback_lead_id').value;
        const modal = bootstrap.Modal.getInstance(document.getElementById('completeCallbackModal'));
        
        fetch(`/leads/${leadId}/complete-callback`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
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
            if (modal) modal.hide();
            
            if (data.success) {
                let message = 'Callback completed and status updated successfully.';
                
                // Add context-specific messages
                if (data.meeting_scheduled) {
                    message += '<br>📅 Meeting scheduled!<br>📧 Email notifications sent to customer and admin.';
                } else if (data.another_callback_scheduled) {
                    message += '<br>📞 Next callback scheduled.';
                }
                
                Swal.fire({
                    icon: 'success',
                    title: 'Callback Completed!',
                    html: message,
                    confirmButtonText: 'OK'
                }).then(() => location.reload());
            } else {
                Swal.fire('Error!', data.message || 'Failed to complete callback', 'error');
            }
        })
        .catch(error => {
            if (modal) modal.hide();
            console.error('Complete callback error:', error);
            Swal.fire('Error!', error.message || 'Something went wrong', 'error');
        });
    });
}

// Clear Filters
window.clearFilters = function() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFilter').value = '';
    document.getElementById('searchInput').value = '';
    location.reload();
}

// Filter functionality
const statusFilterEl = document.getElementById('statusFilter');
const searchInputEl = document.getElementById('searchInput');

if (statusFilterEl) {
    statusFilterEl.addEventListener('change', filterTable);
}

if (searchInputEl) {
    searchInputEl.addEventListener('keyup', filterTable);
}

function filterTable() {
    const statusFilter = document.getElementById('statusFilter')?.value.toLowerCase() || '';
    const searchText = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const rows = document.querySelectorAll('#outgoingLeadsTable tbody tr');
    
    rows.forEach(row => {
        if (row.cells.length === 1) return; // Skip empty state row
        
        const status = row.cells[7]?.textContent.toLowerCase() || '';
        const name = row.cells[3]?.textContent.toLowerCase() || '';
        const contact = row.cells[4]?.textContent.toLowerCase() || '';
        
        const statusMatch = !statusFilter || status.includes(statusFilter);
        const searchMatch = !searchText || name.includes(searchText) || contact.includes(searchText);
        
        row.style.display = statusMatch && searchMatch ? '' : 'none';
    });
}

}); // End DOMContentLoaded
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
