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
                                        <label for="name">Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $lead->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                             <label for="email">Email</label>
                                             <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                 id="email" name="email" value="{{ old('email', $lead->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone *</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone', $lead->phone) }}" required>
                                        @error('phone')
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
                                        <label for="platform">Platform / Source Type *</label>
                                        <select class="form-control @error('platform') is-invalid @enderror" 
                                                id="platform" name="platform" required>
                                            <option value="">Select Platform / Source</option>
                                            <option value="facebook" {{ old('platform', $lead->platform ?? '') === 'facebook' ? 'selected' : '' }}>Facebook</option>
                                            <option value="instagram" {{ old('platform', $lead->platform ?? '') === 'instagram' ? 'selected' : '' }}>Instagram</option>
                                            <option value="website" {{ old('platform', $lead->platform ?? '') === 'website' ? 'selected' : '' }}>Website</option>
                                            <option value="google" {{ old('platform', $lead->platform ?? '') === 'google' ? 'selected' : '' }}>Google</option>
                                            <option value="justdial" {{ old('platform', $lead->platform ?? '') === 'justdial' ? 'selected' : '' }}>Just Dial</option>
                                            <option value="other" {{ old('platform', $lead->platform ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('platform')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6" id="platform_other_group" style="display: none;">
                                    <div class="form-group">
                                        <label for="platform_other">Please specify the source</label>
                                        <input type="text" class="form-control @error('platform_other') is-invalid @enderror" 
                                               id="platform_other" name="platform_other" placeholder="Please specify the source" 
                                               value="{{ old('platform_other', $lead->platform_custom ?? '') }}">
                                        @error('platform_other')
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
                                        <label for="callback_scheduled_at">Next Callback</label>
                                        <input type="datetime-local" class="form-control @error('callback_scheduled_at') is-invalid @enderror" 
                                               id="callback_scheduled_at" name="callback_scheduled_at" 
                                               value="{{ old('callback_scheduled_at', $lead->callback_scheduled_at ? $lead->callback_scheduled_at->format('Y-m-d\TH:i') : '') }}">
                                        @error('callback_scheduled_at')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="meeting_scheduled_at">Next Meeting</label>
                                        <input type="datetime-local" class="form-control @error('meeting_scheduled_at') is-invalid @enderror" 
                                               id="meeting_scheduled_at" name="meeting_scheduled_at" 
                                               value="{{ old('meeting_scheduled_at', $lead->meeting_scheduled_at ? $lead->meeting_scheduled_at->format('Y-m-d\TH:i') : '') }}">
                                        @error('meeting_scheduled_at')
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
                                        <label for="meeting_agenda">Meeting Agenda</label>
                                        <input type="text" class="form-control @error('meeting_agenda') is-invalid @enderror" 
                                               id="meeting_agenda" name="meeting_agenda" 
                                               value="{{ old('meeting_agenda', $lead->meeting_agenda) }}">
                                        @error('meeting_agenda')
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

                        @if($lead->callback_scheduled_at)
                        <div class="mb-3">
                            <strong>Next Callback:</strong><br>
                            <span class="text-warning">{{ $lead->callback_scheduled_at->format('M d, Y g:i A') }}</span>
                        </div>
                        @endif

                        @if($lead->meeting_scheduled_at)
                        <div class="mb-3">
                            <strong>Next Meeting:</strong><br>
                            <span class="text-info">{{ $lead->meeting_scheduled_at->format('M d, Y g:i A') }}</span>
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
    var platformSelect = document.getElementById('platform');
    var otherGroup = document.getElementById('platform_other_group');
    var otherInput = document.getElementById('platform_other');
    function toggleOther() {
        if (!platformSelect) return;
        if (platformSelect.value === 'other') {
            otherGroup.style.display = 'block';
            if (otherInput) otherInput.setAttribute('required', 'required');
        } else {
            otherGroup.style.display = 'none';
            if (otherInput) {
                otherInput.removeAttribute('required');
            }
        }
    }
    if (platformSelect) {
        platformSelect.addEventListener('change', toggleOther);
        toggleOther();
    }
});

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
}

// Auto-hide meeting type and agenda if no meeting scheduled
document.getElementById('meeting_scheduled_at').addEventListener('change', function() {
    const meetingType = document.getElementById('meeting_type');
    const meetingAgenda = document.getElementById('meeting_agenda');
    
    if (this.value) {
        meetingType.closest('.form-group').style.display = 'block';
        meetingAgenda.closest('.form-group').style.display = 'block';
    } else {
        meetingType.value = '';
        meetingAgenda.value = '';
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const meetingScheduled = document.getElementById('meeting_scheduled_at').value;
    if (!meetingScheduled) {
        document.getElementById('meeting_type').value = '';
        document.getElementById('meeting_agenda').value = '';
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