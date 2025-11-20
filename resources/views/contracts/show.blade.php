@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="flaticon-381-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3>Contract {{ $contract->contract_number }}</h3>
                            <p class="mb-0 text-muted">{{ $contract->project_type }} for {{ $contract->customer_name }}</p>
                        </div>
                        <div>
                            <span class="badge badge-{{ $contract->getStatusBadgeColor() }} badge-lg">
                                {{ ucfirst(str_replace('_', ' ', $contract->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5 class="card-title">Customer Details</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Name:</strong> {{ $contract->customer_name }}</p>
                                    <p><strong>Email:</strong> {{ $contract->customer_email }}</p>
                                    <p><strong>Phone:</strong> {{ $contract->customer_phone }}</p>
                                </div>
                            </div>

                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5 class="card-title">Contract Summary</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Amount:</strong><br>{{ $contract->currency }} {{ number_format($contract->final_amount, 2) }}</p>
                                    <p><strong>Start Date:</strong><br>{{ \Carbon\Carbon::parse($contract->start_date)->format('d M Y') }}</p>
                                    <p><strong>Completion Date:</strong><br>{{ \Carbon\Carbon::parse($contract->expected_completion_date)->format('d M Y') }}</p>
                                    <p><strong>Created:</strong><br>{{ $contract->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>

                            @if($contract->invoices->count() > 0)
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h5 class="card-title">Related Invoices</h5>
                                    </div>
                                    <div class="card-body">
                                        @foreach($contract->invoices as $invoice)
                                            <div class="mb-2">
                                                <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                                    {{ $invoice->invoice_number }} - {{ $invoice->currency }} {{ number_format($invoice->grand_total, 2) }}
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-xl-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Contract Content</h5>
                                </div>
                                <div class="card-body">
                                    <div class="p-4 bg-light border rounded">
                                        <pre style="white-space: pre-wrap; font-family: inherit;">{{ $contract->contract_content }}</pre>
                                    </div>

                                    @if($contract->deliverables)
                                        <div class="mt-4">
                                            <h6>Deliverables:</h6>
                                            <p style="white-space: pre-line;">{{ $contract->deliverables }}</p>
                                        </div>
                                    @endif

                                    @if($contract->milestones)
                                        <div class="mt-4">
                                            <h6>Milestones:</h6>
                                            <p style="white-space: pre-line;">{{ $contract->milestones }}</p>
                                        </div>
                                    @endif

                                    @if($contract->payment_schedule)
                                        <div class="mt-4">
                                            <h6>Payment Schedule:</h6>
                                            <p style="white-space: pre-line;">{{ $contract->payment_schedule }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('contracts.index') }}" class="btn btn-secondary">
                                    <i class="flaticon-381-back me-2"></i> Back to Contracts
                                </a>
                                <div>
                                    @if($contract->status === 'active')
                                        <form action="{{ route('contracts.complete', $contract->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Mark this contract as completed?')">
                                                <i class="flaticon-381-check me-2"></i> Mark Completed
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('proposals.show', $contract->proposal_id) }}" class="btn btn-info">
                                        <i class="flaticon-381-view me-2"></i> View Proposal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.badge-lg {
    font-size: 16px;
    padding: 8px 16px;
}
</style>
@endsection
