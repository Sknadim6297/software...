@extends('layouts.app')

@section('title', 'Projects - Konnectix Software')

@section('page-title', 'Website, Software & Application Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Projects</h4>
                <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa fa-plus"></i> Add New Project
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
                                <th><strong>Mobile No.</strong></th>
                                <th><strong>Project Name</strong></th>
                                <th><strong>Project Type</strong></th>
                                <th><strong>Start Date</strong></th>
                                <th><strong>Valuation</strong></th>
                                <th><strong>Total Paid</strong></th>
                                <th><strong>Coordinator</strong></th>
                                <th><strong>Status</strong></th>
                                <th><strong>Actions</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projects as $index => $project)
                            <tr>
                                <td>{{ $projects->firstItem() + $index }}</td>
                                <td>{{ $project->customer->name }}</td>
                                <td>{{ $project->customer->phone }}</td>
                                <td>{{ $project->project_name }}</td>
                                <td><span class="badge badge-info">{{ $project->project_type }}</span></td>
                                <td>{{ $project->start_date->format('d M, Y') }}</td>
                                <td>₹{{ number_format($project->project_valuation, 2) }}</td>
                                <td>
                                    ₹{{ number_format($project->getTotalPaid(), 2) }}
                                    <small class="text-muted d-block">
                                        {{ number_format(($project->getTotalPaid() / $project->project_valuation) * 100, 0) }}%
                                    </small>
                                </td>
                                <td>{{ $project->coordinator->name }}</td>
                                <td>
                                    @if($project->project_status === 'In Progress')
                                        <span class="badge badge-warning">In Progress</span>
                                    @else
                                        <span class="badge badge-success">Completed</span>
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
                                            <a class="dropdown-item" href="{{ route('projects.show', $project) }}">View Details</a>
                                            <a class="dropdown-item" href="{{ route('projects.edit', $project) }}">Edit</a>
                                            
                                            @if($project->project_status === 'In Progress' && $project->getNextInstallment())
                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); if(confirm('Generate invoice and send to customer?')) document.getElementById('take-payment-form-{{ $project->id }}').submit();">
                                                    Take Payment
                                                </a>
                                            @endif
                                            
                                            @if($project->project_status === 'Completed' && !$project->maintenanceContract)
                                                <a class="dropdown-item" href="{{ route('projects.maintenance-contract.create', $project) }}">
                                                    Create Maintenance Contract
                                                </a>
                                            @endif
                                            
                                            <form id="take-payment-form-{{ $project->id }}" action="{{ route('projects.take-payment', $project) }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center">No projects found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-3">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
