@extends('layouts.app')

@section('title', 'Outgoing Leads - BDM Panel')

@section('page-title', 'Outgoing Leads')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Outgoing Leads Management</h4>
                <div class="card-action">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLeadModal">
                        <i class="flaticon-381-add-1"></i> Add Outgoing Lead
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter and Search -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-2">
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="callback_scheduled">Callback Scheduled</option>
                            <option value="meeting_scheduled">Meeting Scheduled</option>
                            <option value="did_not_receive">Did Not Receive</option>
                            <option value="not_required">Not Required</option>
                            <option value="not_interested">Not Interested</option>
                            <option value="interested">Interested</option>
                            <option value="converted">Converted</option>
                        </select>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-2">
                        <select class="form-select" id="assignedFilter">
                            <option value="">All BDMs</option>
                            @foreach($bdms as $bdm)
                                <option value="{{ $bdm->id }}">{{ $bdm->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-4 col-md-8 mb-2">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search by name, phone, email...">
                    </div>
                    <div class="col-xl-2 col-md-4 mb-2">
                        <button class="btn btn-secondary w-100" onclick="clearFilters()">
                            <i class="flaticon-381-refresh"></i> Clear
                        </button>
                    </div>
                </div>

                <!-- Outgoing Leads Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="outgoingLeadsTable">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Date/Time</th>
                                <th>Customer Info</th>
                                <th>Contact Details</th>
                                <th>Project Info</th>
                                <th>Status</th>
                                <th>Assigned BDM</th>
                                <th>Next Action</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leads as $lead)
                                <tr data-status="{{ $lead->status }}" data-assigned="{{ $lead->assigned_to }}">
                                    <td><strong>#{{ $lead->id }}</strong></td>
                                    <td>
                                        <div>{{ $lead->date->format('d M Y') }}</div>
                                        <small class="text-muted">{{ $lead->time->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $lead->customer_name }}</strong>
                                            <br>
                                            <span class="badge badge-info">{{ ucfirst($lead->platform) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <a href="tel:{{ $lead->phone_number }}" class="text-decoration-none">
                                                <i class="flaticon-381-phone-call text-success"></i> {{ $lead->phone_number }}
                                            </a>
                                        </div>
                                        @if($lead->email)
                                            <div class="mt-1">
                                                <a href="mailto:{{ $lead->email }}" class="text-decoration-none">
                                                    <i class="flaticon-381-email text-info"></i> {{ $lead->email }}
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $lead->project_type }}</div>
                                        @if($lead->project_valuation)
                                            <small class="text-success">₹{{ number_format($lead->project_valuation, 2) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @switch($lead->status)
                                            @case('pending')
                                                <span class="badge badge-warning">Pending</span>
                                                @break
                                            @case('callback_scheduled')
                                                <span class="badge badge-primary">Callback Scheduled</span>
                                                @break
                                            @case('meeting_scheduled')
                                                <span class="badge badge-info">Meeting Scheduled</span>
                                                @break
                                            @case('did_not_receive')
                                                <span class="badge badge-secondary">Did Not Receive</span>
                                                @break
                                            @case('not_required')
                                                <span class="badge badge-light text-dark">Not Required</span>
                                                @break
                                            @case('not_interested')
                                                <span class="badge badge-danger">Not Interested</span>
                                                @break
                                            @case('interested')
                                                <span class="badge badge-success">Interested</span>
                                                @break
                                            @case('converted')
                                                <span class="badge badge-dark">Converted</span>
                                                @break
                                            @default
                                                <span class="badge badge-secondary">{{ ucfirst($lead->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($lead->assignedUser)
                                            <span class="text-primary">{{ $lead->assignedUser->name }}</span>
                                        @else
                                            <span class="text-muted">Unassigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($lead->callback_time && $lead->callback_time->isFuture())
                                            <small class="text-warning">
                                                <i class="flaticon-381-phone-call"></i>
                                                {{ $lead->callback_time->format('d M, H:i') }}
                                            </small>
                                        @elseif($lead->meeting_time && $lead->meeting_time->isFuture())
                                            <small class="text-info">
                                                <i class="flaticon-381-calendar"></i>
                                                {{ $lead->meeting_time->format('d M, H:i') }}
                                            </small>
                                        @else
                                            <span class="text-muted">No scheduled action</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-success btn-sm" onclick="handleCallBack({{ $lead->id }})">
                                                <i class="flaticon-381-phone-call"></i>
                                            </button>
                                            <button class="btn btn-secondary btn-sm" onclick="handleDidNotReceive({{ $lead->id }})">
                                                <i class="flaticon-381-close"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm" onclick="handleNotRequired({{ $lead->id }})">
                                                <i class="flaticon-381-trash"></i>
                                            </button>
                                            <button class="btn btn-info btn-sm" onclick="handleMeeting({{ $lead->id }})">
                                                <i class="flaticon-381-calendar"></i>
                                            </button>
                                        </div>
                                        <div class="mt-2">
                                            <a href="{{ route('leads.show', $lead) }}" class="btn btn-primary btn-sm">
                                                <i class="flaticon-381-eye"></i> View
                                            </a>
                                            <a href="{{ route('leads.edit', $lead) }}" class="btn btn-dark btn-sm">
                                                <i class="flaticon-381-edit"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div>
                                            <i class="flaticon-381-upload fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No outgoing leads found</h5>
                                            <p class="text-muted">Start by adding your first outgoing lead campaign.</p>
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLeadModal">
                                                <i class="flaticon-381-add-1"></i> Add Outgoing Lead
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(method_exists($leads, 'hasPages') && $leads->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $leads->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mt-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $totalOutgoing }}</h4>
                        <span>Total Outgoing</span>
                    </div>
                    <i class="flaticon-381-upload fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $pendingOutgoing }}</h4>
                        <span>Pending Follow-ups</span>
                    </div>
                    <i class="flaticon-381-clock fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $interestedOutgoing }}</h4>
                        <span>Interested Prospects</span>
                    </div>
                    <i class="flaticon-381-heart fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $scheduledOutgoing }}</h4>
                        <span>Scheduled Actions</span>
                    </div>
                    <i class="flaticon-381-calendar fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Lead Modal -->
<div class="modal fade" id="addLeadModal" tabindex="-1">
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
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer Name *</label>
                            <input type="text" class="form-control" name="customer_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number *</label>
                            <input type="text" class="form-control" name="phone_number" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Type *</label>
                            <select class="form-select" name="project_type" required>
                                <option value="">Select Project Type</option>
                                <option value="Website Development">Website Development</option>
                                <option value="Mobile App">Mobile App</option>
                                <option value="E-commerce">E-commerce</option>
                                <option value="Digital Marketing">Digital Marketing</option>
                                <option value="Software Development">Software Development</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Platform/Source</label>
                            <select class="form-select" name="platform">
                                <option value="Cold Call">Cold Call</option>
                                <option value="LinkedIn">LinkedIn</option>
                                <option value="Email Campaign">Email Campaign</option>
                                <option value="Referral">Referral</option>
                                <option value="Social Media">Social Media</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Valuation</label>
                            <input type="number" class="form-control" name="project_valuation" step="0.01">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Assign to BDM</label>
                            <select class="form-select" name="assigned_to">
                                <option value="">Select BDM</option>
                                @foreach($bdms as $bdm)
                                    <option value="{{ $bdm->id }}">{{ $bdm->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Remarks/Notes</label>
                            <textarea class="form-control" name="remarks" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Outgoing Lead</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Action handlers (same as incoming leads)
    function handleCallBack(leadId) {
        // Handle callback scheduling logic
        console.log('Callback for lead:', leadId);
    }

    function handleDidNotReceive(leadId) {
        // Handle did not receive logic
        console.log('Did not receive for lead:', leadId);
    }

    function handleNotRequired(leadId) {
        // Handle not required logic
        console.log('Not required for lead:', leadId);
    }

    function handleMeeting(leadId) {
        // Handle meeting scheduling logic
        console.log('Meeting for lead:', leadId);
    }

    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('statusFilter');
        const assignedFilter = document.getElementById('assignedFilter');
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('outgoingLeadsTable');
        const rows = table.querySelectorAll('tbody tr[data-status]');

        function applyFilters() {
            const statusValue = statusFilter.value;
            const assignedValue = assignedFilter.value;
            const searchValue = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const assigned = row.getAttribute('data-assigned');
                const text = row.textContent.toLowerCase();

                const statusMatch = !statusValue || status === statusValue;
                const assignedMatch = !assignedValue || assigned === assignedValue;
                const textMatch = !searchValue || text.includes(searchValue);

                if (statusMatch && assignedMatch && textMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        statusFilter.addEventListener('change', applyFilters);
        assignedFilter.addEventListener('change', applyFilters);
        searchInput.addEventListener('input', applyFilters);

        window.clearFilters = function() {
            statusFilter.value = '';
            assignedFilter.value = '';
            searchInput.value = '';
            applyFilters();
        };
    });
</script>
@endpush