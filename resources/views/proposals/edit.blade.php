@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <form action="{{ route('proposals.update', $proposal->id) }}" method="POST" id="proposalForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ $proposal->customer_name }}</p>
                        <p><strong>Email:</strong> {{ $proposal->customer_email }}</p>
                        <p><strong>Phone:</strong> {{ $proposal->customer_phone }}</p>
                        <p><strong>Lead Type:</strong> {{ ucfirst($proposal->lead_type) }}</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Edit Proposal</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Project Type <span class="text-danger">*</span></label>
                                <select name="project_type" class="form-control" required>
                                    @foreach($projectTypes as $type)
                                        <option value="{{ $type }}" {{ $proposal->project_type === $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Proposed Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="currency" class="form-control" style="max-width: 80px;" required>
                                        <option value="INR" {{ $proposal->currency === 'INR' ? 'selected' : '' }}>INR</option>
                                        <option value="USD" {{ $proposal->currency === 'USD' ? 'selected' : '' }}>USD</option>
                                        <option value="EUR" {{ $proposal->currency === 'EUR' ? 'selected' : '' }}>EUR</option>
                                    </select>
                                    <input type="number" name="proposed_amount" class="form-control" step="0.01" min="0" value="{{ $proposal->proposed_amount }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Project Description</label>
                            <textarea name="project_description" class="form-control" rows="3">{{ $proposal->project_description }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Estimated Duration (Days)</label>
                                <input type="number" name="estimated_days" class="form-control" min="1" value="{{ $proposal->estimated_days }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Payment Terms</label>
                                <input type="text" name="payment_terms" class="form-control" value="{{ $proposal->payment_terms }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deliverables</label>
                            <textarea name="deliverables" class="form-control" rows="4">{{ $proposal->deliverables }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Proposal Content <span class="text-danger">*</span></label>
                            <textarea name="proposal_content" id="proposal_content" class="form-control" rows="15" required>{{ $proposal->proposal_content }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('proposals.show', $proposal->id) }}" class="btn btn-secondary">
                                <i class="flaticon-381-back me-2"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="flaticon-381-save me-2"></i> Update Proposal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
