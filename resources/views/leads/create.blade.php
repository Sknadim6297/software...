@extends('layouts.app')

@section('title', 'Add New Lead - Konnectix BDM')

@section('page-title', 'Add New Lead')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ ucfirst($type) }} Lead Information</h4>
                <a href="{{ $type === 'incoming' ? route('leads.incoming') : route('leads.outgoing') }}" class="btn btn-secondary btn-sm">
                    <i class="fa fa-arrow-left me-2"></i> Back to {{ ucfirst($type) }} Leads
                </a>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('leads.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="customer_name" class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                       id="customer_name" name="customer_name" value="{{ old('customer_name') }}" 
                                       placeholder="Enter customer full name" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                        id="email" name="email" value="{{ old('email') }}" 
                                        placeholder="customer@example.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="phone_number" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                       id="phone_number" name="phone_number" value="{{ old('phone_number') }}" 
                                       placeholder="+91 9876543210" maxlength="15" required>
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="platform" class="form-label">Platform / Source Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('platform') is-invalid @enderror" id="platform" name="platform" required>
                                    <option value="">Select Platform / Source</option>
                                    <option value="facebook" {{ old('platform') == 'facebook' ? 'selected' : '' }}>Facebook</option>
                                    <option value="instagram" {{ old('platform') == 'instagram' ? 'selected' : '' }}>Instagram</option>
                                    <option value="website" {{ old('platform') == 'website' ? 'selected' : '' }}>Website</option>
                                    <option value="google" {{ old('platform') == 'google' ? 'selected' : '' }}>Google</option>
                                    <option value="justdial" {{ old('platform') == 'justdial' ? 'selected' : '' }}>Just Dial</option>
                                    <option value="other" {{ old('platform') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('platform')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row" id="platform_other_group" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="platform_other" class="form-label">Please specify the source</label>
                                <input type="text" class="form-control @error('platform_other') is-invalid @enderror" id="platform_other" name="platform_other" placeholder="Please specify the source" value="{{ old('platform_other') }}">
                                @error('platform_other')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Status / Stage <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="">Select Current Status</option>
                                    <option value="new" {{ old('status')=='new' ? 'selected' : '' }}>New</option>
                                    <option value="callback_scheduled" {{ old('status')=='callback_scheduled' ? 'selected' : '' }}>Call Back</option>
                                    <option value="meeting_scheduled" {{ old('status')=='meeting_scheduled' ? 'selected' : '' }}>Calendar (Schedule Meeting)</option>
                                    <option value="did_not_receive" {{ old('status')=='did_not_receive' ? 'selected' : '' }}>Did Not Receive</option>
                                    <option value="not_required" {{ old('status')=='not_required' ? 'selected' : '' }}>Not Required</option>
                                    <option value="interested" {{ old('status')=='interested' ? 'selected' : '' }}>Interested</option>
                                    <option value="not_interested" {{ old('status')=='not_interested' ? 'selected' : '' }}>Not Interested</option>
                                    <option value="converted" {{ old('status')=='converted' ? 'selected' : '' }}>Converted</option>
                                </select>
                                <small class="text-muted">Select the current lead stage. Choosing Call Back or Calendar will prompt for details.</small>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="project_type" class="form-label">Project Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('project_type') is-invalid @enderror" id="project_type" name="project_type" required>
                                    <option value="">Select Project Type</option>
                                    <option value="web_development" {{ old('project_type') == 'web_development' ? 'selected' : '' }}>Web Development</option>
                                    <option value="mobile_app" {{ old('project_type') == 'mobile_app' ? 'selected' : '' }}>Mobile App</option>
                                    <option value="software_development" {{ old('project_type') == 'software_development' ? 'selected' : '' }}>Software Development</option>
                                    <option value="ui_ux_design" {{ old('project_type') == 'ui_ux_design' ? 'selected' : '' }}>UI/UX Design</option>
                                    <option value="digital_marketing" {{ old('project_type') == 'digital_marketing' ? 'selected' : '' }}>Digital Marketing</option>
                                    <option value="social_media_marketing" {{ old('project_type') == 'social_media_marketing' ? 'selected' : '' }}>Social Media Marketing</option>
                                    <option value="youtube_marketing" {{ old('project_type') == 'youtube_marketing' ? 'selected' : '' }}>YouTube Marketing</option>
                                    <option value="graphic_designing" {{ old('project_type') == 'graphic_designing' ? 'selected' : '' }}>Graphic / Poster Designing</option>
                                    <option value="reels_design" {{ old('project_type') == 'reels_design' ? 'selected' : '' }}>Reels Design</option>
                                    <option value="consultation" {{ old('project_type') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                                    <option value="other" {{ old('project_type') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('project_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row" id="project_type_other_group" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="project_type_other" class="form-label">Please specify the project type</label>
                                <input type="text" class="form-control @error('project_type_other') is-invalid @enderror" id="project_type_other" name="project_type_other" placeholder="Please specify the project type" value="{{ old('project_type_other') }}">
                                @error('project_type_other')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="project_valuation" class="form-label">Project Valuation (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" class="form-control @error('project_valuation') is-invalid @enderror" 
                                           id="project_valuation" name="project_valuation" value="{{ old('project_valuation') }}" 
                                           placeholder="50000" min="0" step="1">
                                </div>
                                @error('project_valuation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="remarks" class="form-label">Remarks/Notes</label>
                                <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                          id="remarks" name="remarks" rows="3"
                                          placeholder="Enter any additional notes or remarks about this lead...">{{ old('remarks') }}</textarea>
                                @error('remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Hidden fields populated by modals -->
                    <input type="hidden" name="callback_time" id="callback_time" value="{{ old('callback_time') }}">
                    <input type="hidden" name="call_notes" id="call_notes" value="{{ old('call_notes') }}">

                    <input type="hidden" name="meeting_time" id="meeting_time" value="{{ old('meeting_time') }}">
                    <input type="hidden" name="meeting_address" id="meeting_address" value="{{ old('meeting_address') }}">
                    <input type="hidden" name="meeting_person_name" id="meeting_person_name" value="{{ old('meeting_person_name') }}">
                    <input type="hidden" name="meeting_phone_number" id="meeting_phone_number" value="{{ old('meeting_phone_number') }}">
                    <input type="hidden" name="meeting_summary" id="meeting_summary" value="{{ old('meeting_summary') }}">

                    <div class="row">
                        <div class="col-12">
                            <div id="statusDataSummary" class="alert alert-info d-none"></div>
                        </div>
                    </div>

                    <div class="form-group text-end">
                        <a href="{{ route('leads.incoming') }}" class="btn btn-secondary me-2">
                            <i class="fa fa-times me-2"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i> Save Lead
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
        // Platform / Source "Other" handling
        const platformSelect = document.getElementById('platform');
        const platformOtherGroup = document.getElementById('platform_other_group');
        const platformOtherInput = document.getElementById('platform_other');

        function togglePlatformOther() {
            if (!platformSelect) return;
            if (platformSelect.value === 'other') {
                platformOtherGroup.style.display = 'block';
                if (platformOtherInput) platformOtherInput.setAttribute('required', 'required');
            } else {
                platformOtherGroup.style.display = 'none';
                if (platformOtherInput) {
                    platformOtherInput.removeAttribute('required');
                    platformOtherInput.value = '';
                }
            }
        }
        if (platformSelect) {
            platformSelect.addEventListener('change', togglePlatformOther);
            // Initialize on load (in case of old input)
            togglePlatformOther();
        }

        // Project Type "Other" handling
        const projectTypeSelect = document.getElementById('project_type');
        const projectTypeOtherGroup = document.getElementById('project_type_other_group');
        const projectTypeOtherInput = document.getElementById('project_type_other');

        function toggleProjectTypeOther() {
            if (!projectTypeSelect) return;
            if (projectTypeSelect.value === 'other') {
                projectTypeOtherGroup.style.display = 'block';
                if (projectTypeOtherInput) projectTypeOtherInput.setAttribute('required', 'required');
            } else {
                projectTypeOtherGroup.style.display = 'none';
                if (projectTypeOtherInput) {
                    projectTypeOtherInput.removeAttribute('required');
                    projectTypeOtherInput.value = '';
                }
            }
        }
        if (projectTypeSelect) {
            projectTypeSelect.addEventListener('change', toggleProjectTypeOther);
            // Initialize on load (in case of old input)
            toggleProjectTypeOther();
        }
    // Auto-format phone number
    const phoneField = document.getElementById('phone_number');
    if (phoneField) {
        phoneField.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            if (value.length === 10 && !e.target.value.includes('+91')) {
                e.target.value = '+91 ' + value;
            }
        });
    }

    // Auto-format project valuation - Allow any amount
    const valuationField = document.getElementById('project_valuation');
    if (valuationField) {
        valuationField.addEventListener('input', function(e) {
            // Remove any step restrictions to allow any number
            e.target.step = '1';
        });
    }

    // Email validation
    const emailField = document.getElementById('email');
    if (emailField) {
        emailField.addEventListener('blur', function(e) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (e.target.value && !emailPattern.test(e.target.value)) {
                e.target.classList.add('is-invalid');
            } else {
                e.target.classList.remove('is-invalid');
            }
        });
    }

    // Phone number validation
    if (phoneField) {
        phoneField.addEventListener('blur', function(e) {
            const phonePattern = /^\+91\s[0-9]{10}$|^[0-9]{10}$/;
            if (e.target.value && !phonePattern.test(e.target.value)) {
                e.target.classList.add('is-invalid');
            } else {
                e.target.classList.remove('is-invalid');
                // Check for duplicates
                checkDuplicateContact('phone', e.target.value);
            }
        });
    }

    // Email duplicate check
    if (emailField) {
        const originalBlurHandler = emailField.onblur;
        emailField.addEventListener('blur', function(e) {
            if (originalBlurHandler) originalBlurHandler.call(this, e);
            if (e.target.value && !e.target.classList.contains('is-invalid')) {
                checkDuplicateContact('email', e.target.value);
            }
        });
    }

    // Form validation before submit
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = ['customer_name', 'phone_number', 'platform', 'project_type'];
            
            requiredFields.forEach(function(fieldName) {
                const field = document.getElementById(fieldName);
                if (field && !field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else if (field) {
                    field.classList.remove('is-invalid');
                }
            });
            
            // Email format validation
            const emailField = document.getElementById('email');
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailField && emailField.value && !emailPattern.test(emailField.value)) {
                emailField.classList.add('is-invalid');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                // Find first invalid field and focus on it
                const firstInvalidField = document.querySelector('.is-invalid');
                if (firstInvalidField) {
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                alert('Please fix the highlighted fields before submitting.');
                return;
            }

            // Additional logic: ensure status-dependent details captured before submit
            const statusVal = document.getElementById('status') ? document.getElementById('status').value : '';
            if (statusVal === 'callback_scheduled') {
                const cbTime = document.getElementById('callback_time').value;
                if (!cbTime) {
                    e.preventDefault();
                    openCallbackModal();
                    alert('Please fill callback date & time before saving the lead.');
                    return;
                }
            }
            if (statusVal === 'meeting_scheduled') {
                const mt = document.getElementById('meeting_time').value;
                const addr = document.getElementById('meeting_address').value;
                const person = document.getElementById('meeting_person_name').value;
                const phone = document.getElementById('meeting_phone_number').value;
                const summary = document.getElementById('meeting_summary').value;
                if (!mt || !addr || !person || !phone || !summary) {
                    e.preventDefault();
                    openMeetingModal();
                    alert('Please complete meeting details before saving the lead.');
                    return;
                }
            }
        });
    }

    // Clear validation errors when user starts typing
    document.querySelectorAll('.form-control').forEach(function(field) {
        field.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });

        // Status change handling -> open appropriate modal
        const statusSelect = document.getElementById('status');
        const statusSummary = document.getElementById('statusDataSummary');

        function resetStatusSupplemental() {
                document.getElementById('callback_time').value = '';
                document.getElementById('call_notes').value = '';
                document.getElementById('meeting_time').value = '';
                document.getElementById('meeting_address').value = '';
                document.getElementById('meeting_person_name').value = '';
                document.getElementById('meeting_phone_number').value = '';
                document.getElementById('meeting_summary').value = '';
                statusSummary.classList.add('d-none');
                statusSummary.innerHTML = '';
        }

        if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                        const val = this.value;
                        if (val === 'callback_scheduled') {
                                // Open callback modal
                                openCallbackModal();
                        } else if (val === 'meeting_scheduled') {
                                openMeetingModal();
                        } else {
                                resetStatusSupplemental();
                        }
                });
        }

        // Callback modal logic
        function openCallbackModal() {
                // Prepare min datetime
                const now = new Date();
                const minDateTime = now.toISOString().slice(0,16);
                document.getElementById('callback_modal_time').min = minDateTime;
                const modal = new bootstrap.Modal(document.getElementById('createCallbackModal'));
                modal.show();
        }

        function openMeetingModal() {
                const now = new Date();
                const minDateTime = now.toISOString().slice(0,16);
                document.getElementById('meeting_modal_time').min = minDateTime;
                const modal = new bootstrap.Modal(document.getElementById('createMeetingModal'));
                modal.show();
        }

        // Save callback details
        const saveCallbackBtn = document.getElementById('saveCallbackDetails');
        if (saveCallbackBtn) {
                saveCallbackBtn.addEventListener('click', function() {
                        const time = document.getElementById('callback_modal_time').value;
                        const notes = document.getElementById('callback_modal_notes').value;
                        if (!time) {
                                alert('Please select callback date & time');
                                return;
                        }
                        document.getElementById('callback_time').value = time;
                        document.getElementById('call_notes').value = notes;
                        statusSummary.classList.remove('d-none');
                        statusSummary.innerHTML = '<strong>Callback Scheduled:</strong> ' + new Date(time).toLocaleString() + (notes ? '<br><em>Notes:</em> '+notes : '');
                        bootstrap.Modal.getInstance(document.getElementById('createCallbackModal')).hide();
                });
        }

        // Save meeting details
        const saveMeetingBtn = document.getElementById('saveMeetingDetails');
        if (saveMeetingBtn) {
                saveMeetingBtn.addEventListener('click', function() {
                        const mt = document.getElementById('meeting_modal_time').value;
                        const addr = document.getElementById('meeting_modal_address').value.trim();
                        const person = document.getElementById('meeting_modal_person').value.trim();
                        const phone = document.getElementById('meeting_modal_phone').value.trim();
                        const summary = document.getElementById('meeting_modal_summary').value.trim();
                        if (!mt || !addr || !person || !phone || !summary) {
                                alert('Please fill all meeting fields.');
                                return;
                        }
                        document.getElementById('meeting_time').value = mt;
                        document.getElementById('meeting_address').value = addr;
                        document.getElementById('meeting_person_name').value = person;
                        document.getElementById('meeting_phone_number').value = phone;
                        document.getElementById('meeting_summary').value = summary;
                        statusSummary.classList.remove('d-none');
                        statusSummary.innerHTML = '<strong>Meeting Scheduled:</strong> ' + new Date(mt).toLocaleString() + '<br><em>With:</em> '+person+' ('+phone+')';
                        bootstrap.Modal.getInstance(document.getElementById('createMeetingModal')).hide();
                });
        }

        // If old status was callback or meeting, show summary on load
        if (statusSelect && (statusSelect.value === 'callback_scheduled' && document.getElementById('callback_time').value)) {
                statusSummary.classList.remove('d-none');
                statusSummary.innerHTML = '<strong>Callback Scheduled:</strong> ' + new Date(document.getElementById('callback_time').value).toLocaleString();
        }
        if (statusSelect && (statusSelect.value === 'meeting_scheduled' && document.getElementById('meeting_time').value)) {
                statusSummary.classList.remove('d-none');
                statusSummary.innerHTML = '<strong>Meeting Scheduled:</strong> ' + new Date(document.getElementById('meeting_time').value).toLocaleString();
        }

        // Functions for duplicate contact checking
        window.checkDuplicateContact = function(type, value) {
            if (!value) return;
            
            fetch('/api/check-duplicate-contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: type,
                    value: value
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
        };

        window.showContactAlert = function(type, data) {
            const fieldId = type === 'phone' ? 'phone_number' : 'email';
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
        };

        window.clearContactAlert = function(type) {
            const existingAlert = document.getElementById(`${type}-duplicate-alert`);
            if (existingAlert) {
                existingAlert.remove();
            }
        };
});
</script>

<!-- Callback Modal -->
<div class="modal fade" id="createCallbackModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Callback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                        <label class="form-label">Callback Date & Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="callback_modal_time">
                        <small class="text-muted">Will appear in Dashboard → Upcoming Work</small>
                </div>
                <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="callback_modal_notes" rows="3" placeholder="Any notes about this callback..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveCallbackDetails">Save Callback</button>
            </div>
        </div>
    </div>
</div>

<!-- Meeting Modal -->
<div class="modal fade" id="createMeetingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Meeting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Meeting Date & Time <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" id="meeting_modal_time">
                        <small class="text-muted">Maximum 3 meetings per day</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Person Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="meeting_modal_person" placeholder="Name of person attending">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="meeting_modal_phone" placeholder="Contact number">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Meeting Address <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="meeting_modal_address" rows="2" placeholder="Full meeting address"></textarea>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Brief Summary of Discussion <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="meeting_modal_summary" rows="3" placeholder="Brief discussion summary or agenda"></textarea>
                    <small class="text-muted">Email notification will be sent to customer and admin.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveMeetingDetails">Save Meeting</button>
            </div>
        </div>
    </div>
</div>
@endpush