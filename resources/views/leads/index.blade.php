@extends('layouts.app')

@section('title', 'All Leads - BDM Panel')

@section('page-title', 'All Leads')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">All Leads Management</h4>
                <a href="{{ route('leads.create') }}" class="btn btn-primary">
                    <i class="flaticon-381-add-1"></i> Add New Lead
                </a>
            </div>
            <div class="card-body">
                <!-- Filter Options -->
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
                        <select class="form-select" id="typeFilter">
                            <option value="">All Types</option>
                            <option value="incoming">Incoming</option>
                            <option value="outgoing">Outgoing</option>
                        </select>
                    </div>
                    <div class="col-xl-3 col-md-6 mb-2">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search by name, phone, email...">
                    </div>
                    <div class="col-xl-3 col-md-6 mb-2">
                        <button class="btn btn-secondary" onclick="clearFilters()">
                            <i class="flaticon-381-refresh"></i> Clear Filters
                        </button>
                    </div>
                </div>

                <!-- Leads Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="leadsTable">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Date/Time</th>
                                <th>Customer Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Project Type</th>
                                <th>Platform</th>
                                <th>Status</th>
                                <th>Assigned To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leads as $lead)
                                <tr data-status="{{ $lead->status }}" data-type="{{ $lead->type }}">
                                    <td>{{ $lead->id }}</td>
                                    <td>
                                        <div>{{ $lead->date->format('d M Y') }}</div>
                                        <small class="text-muted">{{ $lead->time->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $lead->customer_name }}</strong>
                                    </td>
                                    <td>
                                        <a href="tel:{{ $lead->phone_number }}" class="text-decoration-none">
                                            {{ $lead->phone_number }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($lead->email)
                                            <a href="mailto:{{ $lead->email }}" class="text-decoration-none">
                                                {{ $lead->email }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $lead->project_type }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($lead->platform_custom ?? $lead->platform) }}</span>
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
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('leads.show', $lead) }}">
                                                    <i class="flaticon-381-eye"></i> View Details
                                                </a></li>
                                                <li><a class="dropdown-item" href="{{ route('leads.edit', $lead) }}">
                                                    <i class="flaticon-381-edit"></i> Edit Lead
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-success" href="tel:{{ $lead->phone_number }}">
                                                    <i class="flaticon-381-phone-call"></i> Call Now
                                                </a></li>
                                                @if($lead->email)
                                                <li><a class="dropdown-item text-info" href="mailto:{{ $lead->email }}">
                                                    <i class="flaticon-381-email"></i> Send Email
                                                </a></li>
                                                @endif
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('leads.destroy', $lead) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this lead?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="flaticon-381-trash"></i> Delete Lead
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div>
                                            <i class="flaticon-381-file fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No leads found</h5>
                                            <p class="text-muted">Start by adding your first lead or check your filters.</p>
                                            <a href="{{ route('leads.create') }}" class="btn btn-primary">
                                                <i class="flaticon-381-add-1"></i> Add New Lead
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
                    <div class="d-flex justify-content-center mt-4">
                        {{ $leads->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Lead Summary Cards -->
<div class="row mt-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $totalLeads }}</h4>
                        <span>Total Leads</span>
                    </div>
                    <i class="flaticon-381-file fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $pendingLeads }}</h4>
                        <span>Pending Leads</span>
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
                        <h4>{{ $convertedLeads }}</h4>
                        <span>Converted</span>
                    </div>
                    <i class="flaticon-381-check fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $todayLeads }}</h4>
                        <span>Today's Leads</span>
                    </div>
                    <i class="flaticon-381-calendar fa-2x"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const statusFilter = document.getElementById('statusFilter');
        const typeFilter = document.getElementById('typeFilter');
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('leadsTable');
        const rows = table.querySelectorAll('tbody tr[data-status]');

        function applyFilters() {
            const statusValue = statusFilter.value.toLowerCase();
            const typeValue = typeFilter.value.toLowerCase();
            const searchValue = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const status = row.getAttribute('data-status').toLowerCase();
                const type = row.getAttribute('data-type').toLowerCase();
                const text = row.textContent.toLowerCase();

                const statusMatch = !statusValue || status === statusValue;
                const typeMatch = !typeValue || type === typeValue;
                const textMatch = !searchValue || text.includes(searchValue);

                if (statusMatch && typeMatch && textMatch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        statusFilter.addEventListener('change', applyFilters);
        typeFilter.addEventListener('change', applyFilters);
        searchInput.addEventListener('input', applyFilters);

        window.clearFilters = function() {
            statusFilter.value = '';
            typeFilter.value = '';
            searchInput.value = '';
            applyFilters();
        };
    });

    // Auto-refresh every 30 seconds for real-time updates
    setTimeout(function() {
        location.reload();
    }, 30000);
</script>
@endpush