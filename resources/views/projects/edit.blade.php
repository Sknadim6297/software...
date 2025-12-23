@extends('layouts.app')

@section('title', 'Edit Project')

@section('page-title', 'Edit Project')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Project Details</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('projects.update', $project) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-control" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $project->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }} - {{ $customer->phone }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Name <span class="text-danger">*</span></label>
                            <input type="text" name="project_name" class="form-control" value="{{ $project->project_name }}" required>
                            @error('project_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Type <span class="text-danger">*</span></label>
                            <select name="project_type" class="form-control" required>
                                <option value="">Select Project Type</option>
                                <option value="Website" {{ $project->project_type == 'Website' ? 'selected' : '' }}>Website</option>
                                <option value="Software" {{ $project->project_type == 'Software' ? 'selected' : '' }}>Software</option>
                                <option value="Application" {{ $project->project_type == 'Application' ? 'selected' : '' }}>Application</option>
                            </select>
                            @error('project_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" value="{{ $project->start_date->format('Y-m-d') }}" required>
                            @error('start_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Valuation <span class="text-danger">*</span></label>
                            <input type="number" name="project_valuation" class="form-control" step="0.01" value="{{ $project->project_valuation }}" required>
                            @error('project_valuation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Coordinator <span class="text-danger">*</span></label>
                            <select name="project_coordinator_id" class="form-control" required>
                                <option value="">Select Coordinator</option>
                                @foreach($coordinators as $coordinator)
                                    <option value="{{ $coordinator->id }}" {{ $project->project_coordinator_id == $coordinator->id ? 'selected' : '' }}>
                                        {{ $coordinator->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_coordinator_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Status <span class="text-danger">*</span></label>
                            <select name="project_status" class="form-control" required>
                                <option value="In Progress" {{ $project->project_status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="Completed" {{ $project->project_status == 'Completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('project_status')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <h5 class="mt-4 mb-3">Payment Structure</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Upfront Payment</label>
                            <input type="number" name="upfront_payment" class="form-control" step="0.01" value="{{ $project->upfront_payment }}">
                            @error('upfront_payment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label">First Installment</label>
                            <input type="number" name="first_installment" class="form-control" step="0.01" value="{{ $project->first_installment }}">
                            @error('first_installment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Second Installment</label>
                            <input type="number" name="second_installment" class="form-control" step="0.01" value="{{ $project->second_installment }}">
                            @error('second_installment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Third Installment</label>
                            <input type="number" name="third_installment" class="form-control" step="0.01" value="{{ $project->third_installment }}">
                            @error('third_installment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Project</button>
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
