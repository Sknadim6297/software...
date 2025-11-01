@extends('layouts.app')

@section('title', 'View Invoice - Konnectix')

@section('page-title', 'Invoice Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">Invoice #{{ $invoice->invoice_number }}</h4>
                <div>
                    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-primary btn-sm me-2">
                        <i class="fas fa-pencil-alt"></i> Edit
                    </a>
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Customer Information</h5>
                        <p class="mb-1"><strong>Name:</strong> {{ $invoice->customer->customer_name }}</p>
                        @if($invoice->customer->company_name)
                            <p class="mb-1"><strong>Company:</strong> {{ $invoice->customer->company_name }}</p>
                        @endif
                        <p class="mb-1"><strong>Phone:</strong> {{ $invoice->customer->number }}</p>
                        @if($invoice->customer->address)
                            <p class="mb-1"><strong>Address:</strong> {{ $invoice->customer->address }}</p>
                        @endif
                        @if($invoice->customer->gst_number)
                            <p class="mb-1"><strong>GST Number:</strong> {{ $invoice->customer->gst_number }}</p>
                        @endif
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h5>Invoice Information</h5>
                        <p class="mb-1"><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                        <p class="mb-1"><strong>Invoice Date:</strong> {{ $invoice->invoice_date->format('d M Y') }}</p>
                        <p class="mb-1">
                            <strong>Type:</strong>
                            @if($invoice->invoice_type === 'proforma')
                                <span class="badge badge-info">Proforma</span>
                            @else
                                <span class="badge badge-primary">Regular</span>
                            @endif
                        </p>
                    </div>
                </div>

                <h5 class="mb-3">Invoice Items</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th>SAC/HSN</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Rate</th>
                                <th class="text-end">Discount</th>
                                <th class="text-end">CGST</th>
                                <th class="text-end">SGST</th>
                                <th class="text-end">IGST</th>
                                <th class="text-end">Tax Amt</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items as $item)
                            <tr>
                                <td>{{ $item->product_description }}</td>
                                <td>{{ $item->sac_hsn_code }}</td>
                                <td class="text-end">{{ $item->quantity }}</td>
                                <td class="text-end">₹{{ number_format($item->rate, 2) }}</td>
                                <td class="text-end">{{ $item->discount_percentage }}%</td>
                                <td class="text-end">{{ $item->cgst_percentage }}%</td>
                                <td class="text-end">{{ $item->sgst_percentage }}%</td>
                                <td class="text-end">{{ $item->igst_percentage }}%</td>
                                <td class="text-end">₹{{ number_format($item->tax_amount, 2) }}</td>
                                <td class="text-end"><strong>₹{{ number_format($item->total_amount, 2) }}</strong></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="9" class="text-end"><strong>Subtotal:</strong></td>
                                <td class="text-end"><strong>₹{{ number_format($invoice->subtotal, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="9" class="text-end"><strong>Discount:</strong></td>
                                <td class="text-end"><strong>-₹{{ number_format($invoice->discount_amount, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="9" class="text-end"><strong>Tax Total:</strong></td>
                                <td class="text-end"><strong>₹{{ number_format($invoice->tax_total, 2) }}</strong></td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="9" class="text-end"><h5 class="mb-0">Grand Total:</h5></td>
                                <td class="text-end"><h5 class="mb-0">₹{{ number_format($invoice->grand_total, 2) }}</h5></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($invoice->notes)
                <div class="mt-4">
                    <h5>Notes</h5>
                    <p>{{ $invoice->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
