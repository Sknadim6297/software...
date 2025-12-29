@extends('layouts.app')

@section('title', 'Take Payment')
@section('page-title', 'Process Installment Payment')

@section('content')
<div class="row">
    <div class="col-xl-8 offset-xl-2">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    <i class="flaticon-381-coin me-2"></i>Take Payment for {{ $project->project_name }}
                </h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Next Payment Due:</strong> {{ $nextInstallment['label'] }} - ₹{{ number_format($nextInstallment['amount'], 2) }}
                </div>

                <form action="{{ route('projects.process-payment', $project->id) }}" method="POST" id="paymentForm">
                    @csrf
                    <input type="hidden" name="installment_type" value="{{ $nextInstallment['type'] }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" class="form-control" value="{{ $project->customer_name }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Customer Mobile</label>
                            <input type="text" class="form-control" value="{{ $project->customer_mobile }}" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Payment Type</label>
                            <input type="text" class="form-control" value="{{ $nextInstallment['label'] }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Amount (₹) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{ number_format($nextInstallment['amount'], 2) }}" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="payment_date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Payment Method</label>
                            <select class="form-control" name="payment_method">
                                <option value="Cash">Cash</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="UPI">UPI</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Card">Card</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Transaction Reference (Optional)</label>
                            <input type="text" class="form-control" name="transaction_reference" placeholder="Enter transaction ID or reference number">
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <strong><i class="flaticon-381-information me-2"></i>Note:</strong>
                        <ul class="mb-0">
                            <li>Invoice will be automatically generated after payment confirmation</li>
                            <li>Invoice will be sent to the customer's email (if provided)</li>
                            <li>Admin will be notified of this payment</li>
                            @if($project->is_fully_paid && $nextInstallment['type'] == 'third')
                                <li class="text-success"><strong>This is the final payment! You'll be redirected to complete project details.</strong></li>
                            @endif
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-secondary">
                            <i class="flaticon-381-back-1 me-1"></i>Back
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="flaticon-381-check me-1"></i>Confirm Payment & Generate Invoice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
