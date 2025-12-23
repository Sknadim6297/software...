@extends('layouts.app')

@section('title', 'Service Renewal Details')

@section('page-title', 'Service Renewal Details')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $serviceRenewal->service_type }} - {{ $serviceRenewal->customer->name }}</h4>
                <a href="{{ route('service-renewals.index') }}" class="btn btn-secondary btn-sm">Back to Services</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Customer Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Customer Name:</th>
                                <td>{{ $serviceRenewal->customer->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $serviceRenewal->customer->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $serviceRenewal->customer->phone }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h5>Service Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Service Type:</th>
                                <td><span class="badge badge-info">{{ $serviceRenewal->service_type }}</span></td>
                            </tr>
                            <tr>
                                <th>Start Date:</th>
                                <td>{{ $serviceRenewal->start_date->format('d M, Y') }}</td>
                            </tr>
                            <tr>
                                <th>Renewal Date:</th>
                                <td>
                                    {{ $serviceRenewal->renewal_date->format('d M, Y') }}
                                    @if($serviceRenewal->isDueSoon())
                                        <span class="badge badge-warning">Due Soon</span>
                                    @elseif($serviceRenewal->isOverdue())
                                        <span class="badge badge-danger">Overdue</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Renewal Type:</th>
                                <td>{{ $serviceRenewal->renewal_type }}</td>
                            </tr>
                            <tr>
                                <th>Amount:</th>
                                <td>₹{{ number_format($serviceRenewal->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($serviceRenewal->service_status === 'Active')
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Deactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Auto Renewal:</th>
                                <td>
                                    @if($serviceRenewal->auto_renewal)
                                        <span class="badge badge-success">Enabled</span>
                                    @else
                                        <span class="badge badge-secondary">Disabled</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                @if($serviceRenewal->transaction_id)
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h5>Payment Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th width="20%">Transaction ID:</th>
                                <td>{{ $serviceRenewal->transaction_id }}</td>
                            </tr>
                            @if($serviceRenewal->verified_at)
                            <tr>
                                <th>Verified By:</th>
                                <td>{{ $serviceRenewal->verifiedBy->name }}</td>
                            </tr>
                            <tr>
                                <th>Verified At:</th>
                                <td>{{ $serviceRenewal->verified_at->format('d M, Y h:i A') }}</td>
                            </tr>
                            @else
                            <tr>
                                <th>Status:</th>
                                <td><span class="badge badge-warning">Pending Verification</span></td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
                @endif
                
                @if($serviceRenewal->renewal_mail_sent)
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h5>Renewal Reminder</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th width="20%">Last Sent At:</th>
                                <td>{{ $serviceRenewal->last_renewal_mail_sent_at->format('d M, Y h:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endif
                
                @if($serviceRenewal->stop_reason)
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h5>Service Stopped</h5>
                        <div class="alert alert-warning">
                            <strong>Reason:</strong> {{ $serviceRenewal->stop_reason }}
                        </div>
                    </div>
                </div>
                @endif
                
                <hr>
                <div class="mt-4">
                    <a href="{{ route('service-renewals.edit', $serviceRenewal) }}" class="btn btn-primary">Edit Service</a>
                    
                    @if($serviceRenewal->service_status === 'Active')
                        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#renewalModal">Process Renewal</button>
                        <button class="btn btn-warning" onclick="event.preventDefault(); if(confirm('Send renewal reminder to customer?')) document.getElementById('send-reminder-form').submit();">
                            Send Reminder
                        </button>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#stopModal">Stop Renewal</button>
                        
                        @if($serviceRenewal->transaction_id && !$serviceRenewal->verified_at)
                        <button class="btn btn-success" onclick="event.preventDefault(); if(confirm('Verify this renewal?')) document.getElementById('verify-form').submit();">
                            Verify Renewal
                        </button>
                        @endif
                    @endif
                    
                    <form id="send-reminder-form" action="{{ route('service-renewals.send-reminder', $serviceRenewal) }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    
                    @if($serviceRenewal->transaction_id && !$serviceRenewal->verified_at)
                    <form id="verify-form" action="{{ route('service-renewals.verify', $serviceRenewal) }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Renewal Modal -->
<div class="modal fade" id="renewalModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Process Renewal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('service-renewals.process-renewal', $serviceRenewal) }}" method="POST">
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
<div class="modal fade" id="stopModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Stop Renewal Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('service-renewals.stop-renewal', $serviceRenewal) }}" method="POST">
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
@endsection
