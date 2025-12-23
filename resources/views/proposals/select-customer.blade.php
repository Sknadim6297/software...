@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New Proposal - Step 2</h4>
                    <p class="mb-0 text-muted">
                        Select a customer from {{ ucfirst($leadType) }} Leads
                        <span class="badge badge-info ms-2">
                            <i class="flaticon-381-{{ $leadType === 'incoming' ? 'download' : 'upload' }} me-1"></i>
                            {{ ucfirst($leadType) }} Leads
                        </span>
                    </p>
                </div>
                <div class="card-body">
                    @if($eligibleLeads->isEmpty())
                        <div class="alert alert-warning">
                            <i class="flaticon-381-info-1 me-2"></i>
                            No eligible customers found in {{ $leadType }} leads. 
                            <br><small>Only leads marked as <strong>"Interested"</strong> are shown here. Customer identification is based on mobile number.</small>
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('proposals.create') }}" class="btn btn-secondary">
                                <i class="flaticon-381-back me-2"></i> Go Back
                            </a>
                            <a href="{{ route('leads.' . $leadType) }}" class="btn btn-primary ms-2">
                                <i class="flaticon-381-{{ $leadType === 'incoming' ? 'download' : 'upload' }} me-2"></i> 
                                View {{ ucfirst($leadType) }} Leads
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Project Type</th>
                                        <th>Status</th>
                                        <th>Last Activity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($eligibleLeads as $lead)
                                        <tr>
                                            <td>
                                                <strong>{{ $lead->name }}</strong>
                                            </td>
                                            <td>{{ $lead->email }}</td>
                                            <td>{{ $lead->phone_number }}</td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{ str_replace('_', ' ', ucwords($lead->project_type)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">
                                                    <i class="flaticon-381-like me-1"></i>
                                                    Interested
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $lead->updated_at->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td>
                                                <form action="{{ route('proposals.create-with-customer') }}" method="GET" style="display: inline;">
                                                    <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                                                    <input type="hidden" name="lead_type" value="{{ $leadType }}">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="flaticon-381-add me-1"></i> Create Proposal
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('proposals.create') }}" class="btn btn-secondary">
                                <i class="flaticon-381-back me-2"></i> Change Lead Type
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
