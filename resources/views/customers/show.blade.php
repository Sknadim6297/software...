@extends('layouts.app')

@section('title', 'View Customer - Konnectix')

@section('page-title', 'Customer Details')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Customer Information</h4>
                <div class="d-flex">
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary btn-sm me-2">
                        <i class="fa fa-pencil"></i> Edit Customer
                    </a>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Customer Name</label>
                            <p class="form-control-static h5">{{ $customer->customer_name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Company Name</label>
                            <p class="form-control-static h5">{{ $customer->company_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Phone Number</label>
                            <p class="form-control-static">
                                <i class="fa fa-phone text-primary me-2"></i>
                                <a href="tel:{{ $customer->number }}" class="text-decoration-none">{{ $customer->number }}</a>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Alternate Number</label>
                            <p class="form-control-static">
                                @if($customer->alternate_number)
                                    <i class="fa fa-phone text-secondary me-2"></i>
                                    <a href="tel:{{ $customer->alternate_number }}" class="text-decoration-none">{{ $customer->alternate_number }}</a>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Email Address</label>
                            <p class="form-control-static">
                                @if($customer->email)
                                    <i class="fa fa-envelope text-info me-2"></i>
                                    <a href="mailto:{{ $customer->email }}" class="text-decoration-none">{{ $customer->email }}</a>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Project Type</label>
                            <p class="form-control-static">
                                @if($customer->project_type)
                                    <span class="badge badge-info light">{{ ucwords(str_replace('_', ' ', $customer->project_type)) }}</span>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Project Valuation</label>
                            <p class="form-control-static">
                                @if($customer->project_valuation)
                                    <i class="fa fa-rupee-sign text-success me-2"></i>
                                    ₹{{ number_format($customer->project_valuation, 2) }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Project Start Date</label>
                            <p class="form-control-static">
                                @if($customer->project_start_date)
                                    <i class="fa fa-calendar text-primary me-2"></i>
                                    {{ \Carbon\Carbon::parse($customer->project_start_date)->format('F d, Y') }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Payment Terms</label>
                            <p class="form-control-static">
                                @if($customer->payment_terms)
                                    <span class="badge badge-warning light">{{ ucwords(str_replace('_', ' ', $customer->payment_terms)) }}</span>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Lead Source</label>
                            <p class="form-control-static">
                                @if($customer->lead_source)
                                    <span class="badge badge-primary light">{{ ucwords(str_replace('_', ' ', $customer->lead_source)) }}</span>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">GST Number</label>
                            <p class="form-control-static">{{ $customer->gst_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">State Code</label>
                            <p class="form-control-static">{{ $customer->state_code ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">State</label>
                            <p class="form-control-static">{{ $customer->state_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <p class="form-control-static">
                                @if($customer->active)
                                    <span class="badge badge-success light">
                                        <i class="fa fa-circle text-success me-1"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="badge badge-danger light">
                                        <i class="fa fa-circle text-danger me-1"></i>
                                        Inactive
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                @if($customer->address)
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label text-muted">Address</label>
                            <p class="form-control-static">{{ $customer->address }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($customer->remarks)
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label text-muted">Remarks</label>
                            <p class="form-control-static">{{ $customer->remarks }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Created Date</label>
                            <p class="form-control-static">
                                <i class="fa fa-calendar text-info me-2"></i>
                                {{ $customer->created_at->format('F d, Y \a\t g:i A') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Last Updated</label>
                            <p class="form-control-static">
                                <i class="fa fa-clock text-warning me-2"></i>
                                {{ $customer->updated_at->format('F d, Y \a\t g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-lg-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Quick Actions</h4>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-primary">
                        <i class="fa fa-pencil me-2"></i> Edit Customer
                    </a>
                    <button type="button" class="btn btn-success" onclick="window.print()">
                        <i class="fa fa-print me-2"></i> Print Details
                    </button>
                    <a href="mailto:{{ $customer->email ?? '' }}" class="btn btn-info {{ !$customer->email ? 'disabled' : '' }}">
                        <i class="fa fa-envelope me-2"></i> Send Email
                    </a>
                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.');" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fa fa-trash me-2"></i> Delete Customer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-control-static {
        padding: 0.5rem 0;
        margin-bottom: 0;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: transparent;
        border: none;
    }
    
    .badge {
        font-size: 0.875rem;
    }
    
    @media print {
        .card-header .d-flex,
        .btn,
        .quick-actions {
            display: none !important;
        }
    }
</style>
@endpush
@endsection