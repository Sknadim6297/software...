@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create New Proposal - Step 1</h4>
                    <p class="mb-0 text-muted">Select where to fetch customer details from</p>
                </div>
                <div class="card-body">
                    <form action="{{ route('proposals.select-customer') }}" method="POST">
                        @csrf
                        
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="text-center mb-4">
                                    <h5>Where do you want to fetch customer details from?</h5>
                                    <p class="text-muted">Only customers with meeting scheduled or marked as interested will be shown</p>
                                </div>
                                
                                <div class="row g-3">
                                    <!-- Incoming Leads Option -->
                                    <div class="col-md-6">
                                        <label class="custom-radio-card">
                                            <input type="radio" name="lead_type" value="incoming" required checked>
                                            <div class="card h-100 border-2 cursor-pointer hover-card">
                                                <div class="card-body text-center">
                                                    <i class="flaticon-381-download text-success mb-3" style="font-size: 48px;"></i>
                                                    <h5 class="card-title">Incoming Leads</h5>
                                                    <p class="card-text text-muted">
                                                        Customers who contacted us or filled inquiry forms
                                                    </p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    
                                    <!-- Outgoing Leads Option -->
                                    <div class="col-md-6">
                                        <label class="custom-radio-card">
                                            <input type="radio" name="lead_type" value="outgoing" required>
                                            <div class="card h-100 border-2 cursor-pointer hover-card">
                                                <div class="card-body text-center">
                                                    <i class="flaticon-381-upload text-primary mb-3" style="font-size: 48px;"></i>
                                                    <h5 class="card-title">Outgoing Leads</h5>
                                                    <p class="card-text text-muted">
                                                        Customers we contacted proactively
                                                    </p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mt-4 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="flaticon-381-next me-2"></i> Next: Select Customer
                                    </button>
                                    <a href="{{ route('proposals.index') }}" class="btn btn-secondary btn-lg px-5 ms-2">
                                        <i class="flaticon-381-back me-2"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.custom-radio-card {
    cursor: pointer;
    display: block;
}

.custom-radio-card input[type="radio"] {
    display: none;
}

.custom-radio-card .card {
    transition: all 0.3s ease;
    border-color: #e9ecef;
}

.custom-radio-card:hover .card {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.custom-radio-card input[type="radio"]:checked + .card {
    border-color: var(--primary);
    background-color: rgba(var(--primary-rgb), 0.05);
    box-shadow: 0 0 15px rgba(var(--primary-rgb), 0.3);
}

.hover-card {
    transition: all 0.3s ease;
}
</style>
@endsection
