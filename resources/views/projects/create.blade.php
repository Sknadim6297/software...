@extends('layouts.app')

@section('title', 'Add Project')

@section('page-title', 'Add New Project')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Project Details</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('projects.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-control" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->customer_name }} - {{ $customer->number }}</option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Name <span class="text-danger">*</span></label>
                            <input type="text" name="project_name" class="form-control" required>
                            @error('project_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Type <span class="text-danger">*</span></label>
                            <select name="project_type" class="form-control" required>
                                <option value="">Select Project Type</option>
                                <option value="Website">Website</option>
                                <option value="Software">Software</option>
                                <option value="Application">Application</option>
                            </select>
                            @error('project_type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" class="form-control" required>
                            @error('start_date')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Valuation <span class="text-danger">*</span></label>
                            <input type="number" name="project_valuation" class="form-control" step="0.01" required>
                            @error('project_valuation')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Project Coordinator <span class="text-danger">*</span></label>
                            <select name="project_coordinator_id" class="form-control" required>
                                <option value="">Select Coordinator</option>
                                @foreach($coordinators as $coordinator)
                                    <option value="{{ $coordinator->id }}">{{ $coordinator->name }}</option>
                                @endforeach
                            </select>
                            @error('project_coordinator_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <h5 class="mt-4 mb-3">Payment Structure</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Upfront Payment</label>
                            <input type="number" name="upfront_payment" class="form-control" step="0.01" value="0">
                            @error('upfront_payment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label">First Installment</label>
                            <input type="number" name="first_installment" class="form-control" step="0.01" value="0">
                            @error('first_installment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Second Installment</label>
                            <input type="number" name="second_installment" class="form-control" step="0.01" value="0">
                            @error('second_installment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Third Installment</label>
                            <input type="number" name="third_installment" class="form-control" step="0.01" value="0">
                            @error('third_installment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Create Project</button>
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
