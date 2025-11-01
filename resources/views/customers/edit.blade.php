@extends('layouts.app')

@section('title', 'Edit Customer - Konnectix')

@section('page-title', 'Edit Customer')

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Customer Information</h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form action="{{ route('customers.update', $customer) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                    name="customer_name" value="{{ old('customer_name', $customer->customer_name) }}" placeholder="Enter customer name" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                    name="company_name" value="{{ old('company_name', $customer->company_name) }}" placeholder="Enter company name">
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('number') is-invalid @enderror" 
                                    name="number" value="{{ old('number', $customer->number) }}" placeholder="Enter phone number" required>
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Alternate Number</label>
                                <input type="text" class="form-control @error('alternate_number') is-invalid @enderror" 
                                    name="alternate_number" value="{{ old('alternate_number', $customer->alternate_number) }}" placeholder="Enter alternate number">
                                @error('alternate_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                name="address" rows="3" placeholder="Enter address">{{ old('address', $customer->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">GST Number</label>
                                <input type="text" class="form-control @error('gst_number') is-invalid @enderror" 
                                    name="gst_number" value="{{ old('gst_number', $customer->gst_number) }}" placeholder="Enter GST number">
                                @error('gst_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">State Code</label>
                                <input type="text" class="form-control @error('state_code') is-invalid @enderror" 
                                    name="state_code" value="{{ old('state_code', $customer->state_code) }}" placeholder="e.g., 27">
                                @error('state_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">State Name</label>
                                <input type="text" class="form-control @error('state_name') is-invalid @enderror" 
                                    name="state_name" value="{{ old('state_name', $customer->state_name) }}" placeholder="e.g., Maharashtra">
                                @error('state_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check custom-checkbox mb-3">
                                <input type="checkbox" class="form-check-input" id="active" name="active" 
                                    value="1" {{ old('active', $customer->active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="active">Active Customer</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('customers.index') }}" class="btn btn-light me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Customer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
