@extends('layouts.app')

@section('title', 'Edit Lead - ' . $lead->customer_name)

@section('page-title', 'Edit Lead')

@section('content')

        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Lead Information</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('leads.update', $lead) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_name">Name *</label>
                                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                               id="customer_name" name="customer_name" value="{{ old('customer_name', $lead->customer_name) }}" required>
                                        @error('customer_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                             <label for="customer_email">Email</label>
                                             <input type="email" class="form-control @error('customer_email') is-invalid @enderror" 
                                                 id="customer_email" name="customer_email" value="{{ old('customer_email', $lead->customer_email) }}">
                                        @error('customer_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer_phone">Phone *</label>
                                        <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" 
                                               id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $lead->customer_phone) }}" required>
                                        @error('customer_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="company_name">Company Name</label>
                                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                               id="company_name" name="company_name" value="{{ old('company_name', $lead->company_name) }}">
                                        @error('company_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="source">Platform / Source Type *</label>
                                        <select class="form-control @error('source') is-invalid @enderror" 
                                                id="source" name="source" required>
                                            <option value="">Select Platform / Source</option>
                                            <option value="facebook" {{ old('source', $lead->source ?? '') === 'facebook' ? 'selected' : '' }}>Facebook</option>
                                            <option value="instagram" {{ old('source', $lead->source ?? '') === 'instagram' ? 'selected' : '' }}>Instagram</option>
                                            <option value="website" {{ old('source', $lead->source ?? '') === 'website' ? 'selected' : '' }}>Website</option>
                                            <option value="google" {{ old('source', $lead->source ?? '') === 'google' ? 'selected' : '' }}>Google</option>
                                            <option value="justdial" {{ old('source', $lead->source ?? '') === 'justdial' ? 'selected' : '' }}>Just Dial</option>
                                            <option value="other" {{ old('source', $lead->source ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('source')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6" id="source_other_group" style="display: none;">
                                    <div class="form-group">
                                        <label for="source_other">Please specify the source</label>
                                        <input type="text" class="form-control @error('source_other') is-invalid @enderror" 
                                               id="source_other" name="source_other" placeholder="Please specify the source" 
                                               value="{{ old('source_other', $lead->source_custom ?? '') }}">
                                        @error('source_other')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_type">Project Type *</label>
                                        <select class="form-control @error('project_type') is-invalid @enderror" 
                                                id="project_type" name="project_type" required>
                                            <option value="">Select Type</option>
                                            <option value="web_development" {{ old('project_type', $lead->project_type) === 'web_development' ? 'selected' : '' }}>Web Development</option>
                                            <option value="mobile_app" {{ old('project_type', $lead->project_type) === 'mobile_app' ? 'selected' : '' }}>Mobile App</option>
                                            <option value="desktop_software" {{ old('project_type', $lead->project_type) === 'desktop_software' ? 'selected' : '' }}>Desktop Software</option>
                                            <option value="e_commerce" {{ old('project_type', $lead->project_type) === 'e_commerce' ? 'selected' : '' }}>E-commerce</option>
                                            <option value="cms" {{ old('project_type', $lead->project_type) === 'cms' ? 'selected' : '' }}>CMS</option>
                                            <option value="api_integration" {{ old('project_type', $lead->project_type) === 'api_integration' ? 'selected' : '' }}>API Integration</option>
                                            <option value="maintenance" {{ old('project_type', $lead->project_type) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                            <option value="consulting" {{ old('project_type', $lead->project_type) === 'consulting' ? 'selected' : '' }}>Consulting</option>
                                            <option value="other" {{ old('project_type', $lead->project_type) === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('project_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row" id="project_type_other_group" style="display: none;">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="project_type_other">Please specify the project type</label>
                                        <input type="text" class="form-control @error('project_type_other') is-invalid @enderror" 
                                               id="project_type_other" name="project_type_other" 
                                               value="{{ old('project_type_other', $lead->project_type_other ?? '') }}" 
                                               placeholder="Please specify the project type">
                                        @error('project_type_other')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="budget_range">Budget Range</label>
                                        <input type="text" class="form-control @error('budget_range') is-invalid @enderror" 
                                               id="budget_range" name="budget_range" value="{{ old('budget_range', $lead->budget_range) }}" 
                                               placeholder="e.g., $5,000 - $10,000">
                                        @error('budget_range')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bdm_id">Assign BDM</label>
                                        <select class="form-control @error('bdm_id') is-invalid @enderror" 
                                                id="bdm_id" name="bdm_id">
                                            <option value="">Select BDM</option>
                                            @foreach($bdms as $bdm)
                                                <option value="{{ $bdm->id }}" {{ old('bdm_id', $lead->bdm_id) == $bdm->id ? 'selected' : '' }}>
                                                    {{ $bdm->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('bdm_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control @error('status') is-invalid @enderror" 
                                                id="status" name="status">
                                            <option value="new" {{ old('status', $lead->status) === 'new' ? 'selected' : '' }}>New</option>
                                            <option value="contacted" {{ old('status', $lead->status) === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                            <option value="qualified" {{ old('status', $lead->status) === 'qualified' ? 'selected' : '' }}>Qualified</option>
                                            <option value="meeting_scheduled" {{ old('status', $lead->status) === 'meeting_scheduled' ? 'selected' : '' }}>Meeting Scheduled</option>
                                            <option value="proposal_sent" {{ old('status', $lead->status) === 'proposal_sent' ? 'selected' : '' }}>Proposal Sent</option>
                                            <option value="negotiation" {{ old('status', $lead->status) === 'negotiation' ? 'selected' : '' }}>Negotiation</option>
                                            <option value="won" {{ old('status', $lead->status) === 'won' ? 'selected' : '' }}>Won</option>
                                            <option value="lost" {{ old('status', $lead->status) === 'lost' ? 'selected' : '' }}>Lost</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="priority">Priority</label>
                                        <select class="form-control @error('priority') is-invalid @enderror" 
                                                id="priority" name="priority">
                                            <option value="low" {{ old('priority', $lead->priority ?? 'medium') === 'low' ? 'selected' : '' }}>Low</option>
                                            <option value="medium" {{ old('priority', $lead->priority ?? 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                                            <option value="high" {{ old('priority', $lead->priority ?? 'medium') === 'high' ? 'selected' : '' }}>High</option>
                                            <option value="urgent" {{ old('priority', $lead->priority ?? 'medium') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                        </select>
                                        @error('priority')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="requirements">Requirements</label>
                                <textarea class="form-control @error('requirements') is-invalid @enderror" 
                                          id="requirements" name="requirements" rows="4">{{ old('requirements', $lead->requirements) }}</textarea>
                                @error('requirements')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3">{{ old('notes', $lead->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            <h5>Schedule Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="callback_time">Next Callback</label>
                                        <input type="datetime-local" class="form-control @error('callback_time') is-invalid @enderror" 
                                               id="callback_time" name="callback_time" 
                                               value="{{ old('callback_time', $lead->callback_time ? \Carbon\Carbon::parse($lead->callback_time)->format('Y-m-d\TH:i') : '') }}">
                                        @error('callback_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meeting_time">Next Meeting</label>
                                        <input type="datetime-local" class="form-control @error('meeting_time') is-invalid @enderror" 
                                               id="meeting_time" name="meeting_time" 
                                               value="{{ old('meeting_time', $lead->meeting_time ? \Carbon\Carbon::parse($lead->meeting_time)->format('Y-m-d\TH:i') : '') }}">
                                        @error('meeting_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meeting_type">Meeting Type</label>
                                        <select class="form-control @error('meeting_type') is-invalid @enderror" 
                                                id="meeting_type" name="meeting_type">
                                            <option value="">Select Type</option>
                                            <option value="phone" {{ old('meeting_type', $lead->meeting_type) === 'phone' ? 'selected' : '' }}>Phone Call</option>
                                            <option value="video" {{ old('meeting_type', $lead->meeting_type) === 'video' ? 'selected' : '' }}>Video Call</option>
                                            <option value="in_person" {{ old('meeting_type', $lead->meeting_type) === 'in_person' ? 'selected' : '' }}>In Person</option>
                                        </select>
                                        @error('meeting_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meeting_summary">Meeting Summary/Agenda</label>
                                        <input type="text" class="form-control @error('meeting_summary') is-invalid @enderror" 
                                               id="meeting_summary" name="meeting_summary" 
                                               value="{{ old('meeting_summary', $lead->meeting_summary) }}">
                                        @error('meeting_summary')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">Update Lead</button>
                                <a href="{{ route('leads.show', $lead) }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Lead Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Current Status:</strong>
                            @switch($lead->status)
                                @case('new')
                                    <span class="badge badge-info">New</span>
                                    @break
                                @case('contacted')
                                    <span class="badge badge-warning">Contacted</span>
                                    @break
                                @case('qualified')
                                    <span class="badge badge-primary">Qualified</span>
                                    @break
                                @case('meeting_scheduled')
                                    <span class="badge badge-secondary">Meeting Scheduled</span>
                                    @break
                                @case('proposal_sent')
                                    <span class="badge badge-info">Proposal Sent</span>
                                    @break
                                @case('negotiation')
                                    <span class="badge badge-warning">Negotiation</span>
                                    @break
                                @case('won')
                                    <span class="badge badge-success">Won</span>
                                    @break
                                @case('lost')
                                    <span class="badge badge-danger">Lost</span>
                                    @break
                            @endswitch
                        </div>
                        
                        <div class="mb-3">
                            <strong>Added:</strong> {{ $lead->created_at->format('M d, Y') }}
                        </div>
                        
                        <div class="mb-3">
                            <strong>Last Updated:</strong> {{ $lead->updated_at->format('M d, Y') }}
                        </div>

                        @if($lead->callback_time)
                        <div class="mb-3">
                            <strong>Next Callback:</strong><br>
                            <span class="text-warning">{{ \Carbon\Carbon::parse($lead->callback_time)->format('M d, Y g:i A') }}</span>
                        </div>
                        @endif

                        @if($lead->meeting_time)
                        <div class="mb-3">
                            <strong>Next Meeting:</strong><br>
                            <span class="text-info">{{ \Carbon\Carbon::parse($lead->meeting_time)->format('M d, Y g:i A') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('leads.show', $lead) }}" class="btn btn-info btn-block">
                                <i class="fa fa-eye"></i> View Lead
                            </a>
                            @if($lead->status !== 'won' && $lead->status !== 'converted')
                            <button class="btn btn-success btn-block" onclick="convertToCustomer()">
                                <i class="fa fa-user-plus"></i> Convert to Customer
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Toggle Other source input in Edit form
document.addEventListener('DOMContentLoaded', function() {
    var sourceSelect = document.getElementById('source');
    var otherGroup = document.getElementById('source_other_group');
    var otherInput = document.getElementById('source_other');
    function toggleOther() {
        if (!sourceSelect) return;
        if (sourceSelect.value === 'other') {
            otherGroup.style.display = 'block';
            if (otherInput) otherInput.setAttribute('required', 'required');
        } else {
            otherGroup.style.display = 'none';
            if (otherInput) {
                otherInput.removeAttribute('required');
            }
        }
    }
    if (sourceSelect) {
        sourceSelect.addEventListener('change', toggleOther);
        toggleOther();
    }

    // Project Type "Other" handling
    var projectTypeSelect = document.getElementById('project_type');
    var projectTypeOtherGroup = document.getElementById('project_type_other_group');
    var projectTypeOtherInput = document.getElementById('project_type_other');
    
    function toggleProjectTypeOther() {
        if (!projectTypeSelect) return;
        if (projectTypeSelect.value === 'other') {
            projectTypeOtherGroup.style.display = 'block';
            if (projectTypeOtherInput) projectTypeOtherInput.setAttribute('required', 'required');
        } else {
            projectTypeOtherGroup.style.display = 'none';
            if (projectTypeOtherInput) {
                projectTypeOtherInput.removeAttribute('required');
            }
        }
    }
    if (projectTypeSelect) {
        projectTypeSelect.addEventListener('change', toggleProjectTypeOther);
        toggleProjectTypeOther();
    }
});

window.convertToCustomer = function() {
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
                window.location.href = '/customers';
            } else {
                alert('Error converting lead: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while converting the lead.');
        });
    }
};

// Auto-hide meeting type and summary if no meeting scheduled + Check availability
document.getElementById('meeting_time').addEventListener('change', function() {
    const meetingType = document.getElementById('meeting_type');
    const meetingSummary = document.getElementById('meeting_summary');
    
    if (this.value) {
        meetingType.closest('.form-group').style.display = 'block';
        meetingSummary.closest('.form-group').style.display = 'block';
        
        // Check meeting availability for selected date
        checkMeetingAvailability(this.value);
    } else {
        meetingType.value = '';
        meetingSummary.value = '';
        clearMeetingAlert();
    }
});

// Function to check meeting availability
function checkMeetingAvailability(meetingDateTime) {
    if (!meetingDateTime) return;
    
    const date = meetingDateTime.split('T')[0]; // Extract date part
    const bdmId = document.getElementById('bdm_id').value || {{ Auth::id() }};
    
    fetch('/api/check-meeting-availability', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            date: date,
            bdm_id: bdmId,
            exclude_lead_id: {{ $lead->id }}
        })
    })
    .then(response => response.json())
    .then(data => {
        showMeetingAlert(data);
    })
    .catch(error => {
        console.error('Error checking meeting availability:', error);
    });
}

// Function to show meeting availability alert
function showMeetingAlert(data) {
    clearMeetingAlert();
    
    const meetingTimeGroup = document.getElementById('meeting_time').closest('.form-group');
    const alertClass = data.available ? 'alert-info' : 'alert-warning';
    const alertHtml = `
        <div class="alert ${alertClass} mt-2" id="meeting-alert">
            <small><i class="fa fa-info-circle"></i> ${data.message}</small>
        </div>
    `;
    
    meetingTimeGroup.insertAdjacentHTML('beforeend', alertHtml);
    
    // Disable submit if not available
    const submitBtn = document.querySelector('button[type="submit"]');
    if (!data.available) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Date Full - Choose Different Date';
    } else {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Update Lead';
    }
}

// Function to clear meeting alert
function clearMeetingAlert() {
    const existingAlert = document.getElementById('meeting-alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    
    // Re-enable submit button
    const submitBtn = document.querySelector('button[type="submit"]');
    submitBtn.disabled = false;
    submitBtn.textContent = 'Update Lead';
}

// Function to check for duplicate contact information
function checkDuplicateContact(type, value, excludeLeadId) {
    if (!value) return;
    
    fetch('/api/check-duplicate-contact', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            type: type,
            value: value,
            exclude_lead_id: excludeLeadId
        })
    })
    .then(response => response.json())
    .then(data => {
        clearContactAlert(type);
        if (data.exists) {
            showContactAlert(type, data);
        }
    })
    .catch(error => {
        console.error('Error checking duplicate contact:', error);
    });
}

// Function to show duplicate contact alert
function showContactAlert(type, data) {
    const fieldId = type === 'phone' ? 'customer_phone' : 'customer_email';
    const field = document.getElementById(fieldId);
    const fieldGroup = field.closest('.form-group');
    
    const alertHtml = `
        <div class="alert alert-warning mt-2" id="${type}-duplicate-alert">
            <small><i class="fa fa-exclamation-triangle"></i> 
            This ${type} already exists: <strong>${data.customer_name}</strong> 
            (Added: ${data.created_date})
            ${data.lead_id ? `<a href="/leads/${data.lead_id}" target="_blank" class="alert-link">View Lead</a>` : ''}
            ${data.customer_id ? `<a href="/customers/${data.customer_id}" target="_blank" class="alert-link">View Customer</a>` : ''}
            </small>
        </div>
    `;
    
    fieldGroup.insertAdjacentHTML('beforeend', alertHtml);
}

// Function to clear contact alert
function clearContactAlert(type) {
    const existingAlert = document.getElementById(`${type}-duplicate-alert`);
    if (existingAlert) {
        existingAlert.remove();
    }
}

    // Also check when BDM is changed
    document.getElementById('bdm_id').addEventListener('change', function() {
        const meetingTime = document.getElementById('meeting_time').value;
        if (meetingTime) {
            checkMeetingAvailability(meetingTime);
        }
    });

    // Check for duplicate phone number
    document.getElementById('customer_phone').addEventListener('blur', function() {
        const phone = this.value.trim();
        if (phone) {
            checkDuplicateContact('phone', phone, {{ $lead->id }});
        }
    });

    // Check for duplicate email
    document.getElementById('customer_email').addEventListener('blur', function() {
        const email = this.value.trim();
        if (email) {
            checkDuplicateContact('email', email, {{ $lead->id }});
        }
    });// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const meetingScheduled = document.getElementById('meeting_time').value;
    if (!meetingScheduled) {
        document.getElementById('meeting_type').value = '';
        document.getElementById('meeting_summary').value = '';
    } else {
        // Check availability for existing meeting
        checkMeetingAvailability(meetingScheduled);
    }
});
</script>

<style>
.btn-block {
    width: 100%;
    margin-bottom: 10px;
}
</style>
@endsection