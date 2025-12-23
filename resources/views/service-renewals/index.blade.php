@extends('layouts.app')

@section('title', 'Service Renewals - Konnectix Software')

@section('page-title', 'Renewal & Service Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Service Renewals</h4>
                <a href="{{ route('service-renewals.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Add New Service
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th><strong>S.No</strong></th>
                                <th><strong>Customer Name</strong></th>
                                <th><strong>Email</strong></th>
                                <th><strong>Phone</strong></th>
                                <th><strong>Service Type</strong></th>
                                <th><strong>Start Date</strong></th>
                                <th><strong>Renewal Date</strong></th>
                                <th><strong>Renewal Type</strong></th>
                                <th><strong>Amount</strong></th>
                                <th><strong>Status</strong></th>
                                <th><strong>Actions</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $index => $service)
                            <tr>
                                <td>{{ $services->firstItem() + $index }}</td>
                                <td>{{ $service->customer->name }}</td>
                                <td>{{ $service->customer->email }}</td>
                                <td>{{ $service->customer->phone }}</td>
                                <td><span class="badge badge-info">{{ $service->service_type }}</span></td>
                                <td>{{ $service->start_date->format('d M, Y') }}</td>
                                <td>
                                    {{ $service->renewal_date->format('d M, Y') }}
                                    @if($service->isDueSoon())
                                        <span class="badge badge-warning">Due Soon</span>
                                    @elseif($service->isOverdue())
                                        <span class="badge badge-danger">Overdue</span>
                                    @endif
                                </td>
                                <td>{{ $service->renewal_type }}</td>
                                <td>₹{{ number_format($service->amount, 2) }}</td>
                                <td>
                                    @if($service->service_status === 'Active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Deactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary light sharp btn-sm" data-bs-toggle="dropdown">
                                            <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24"/>
                                                    <circle fill="#000000" cx="5" cy="12" r="2"/>
                                                    <circle fill="#000000" cx="12" cy="12" r="2"/>
                                                    <circle fill="#000000" cx="19" cy="12" r="2"/>
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('service-renewals.show', $service) }}">View Details</a>
                                            <a class="dropdown-item" href="{{ route('service-renewals.edit', $service) }}">Edit</a>
                                            
                                            @if($service->service_status === 'Active')
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#renewalModal{{ $service->id }}">
                                                    Process Renewal
                                                </a>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#stopModal{{ $service->id }}">
                                                    Stop Renewal
                                                </a>
                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('send-reminder-form-{{ $service->id }}').submit();">
                                                    Send Reminder
                                                </a>
                                                @if($service->transaction_id && !$service->verified_at)
                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('verify-form-{{ $service->id }}').submit();">
                                                    Verify Renewal
                                                </a>
                                                @endif
                                            @endif
                                            
                                            <form id="send-reminder-form-{{ $service->id }}" action="{{ route('service-renewals.send-reminder', $service) }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                            
                                            @if($service->transaction_id && !$service->verified_at)
                                            <form id="verify-form-{{ $service->id }}" action="{{ route('service-renewals.verify', $service) }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Renewal Modal -->
                            <div class="modal fade" id="renewalModal{{ $service->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Process Renewal</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('service-renewals.process-renewal', $service) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Transaction ID</label>
                                                    <input type="text" name="transaction_id" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Stop Renewal Modal -->
                            <div class="modal fade" id="stopModal{{ $service->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Stop Renewal Service</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('service-renewals.stop-renewal', $service) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Reason for Stopping Service</label>
                                                    <textarea name="stop_reason" class="form-control" rows="4" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger">Stop Service</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center">No service renewals found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $services->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
