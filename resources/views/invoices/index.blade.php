@extends('layouts.app')

@section('title', 'Invoices - Konnectix')

@section('page-title', 'Invoice Management')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">All Invoices</h4>
                <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm">
                    <i class="flaticon-381-plus"></i> Create New Invoice
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th><strong>INVOICE #</strong></th>
                                <th><strong>CUSTOMER</strong></th>
                                <th><strong>DATE</strong></th>
                                <th><strong>TYPE</strong></th>
                                <th><strong>SUBTOTAL</strong></th>
                                <th><strong>TAX</strong></th>
                                <th><strong>TOTAL</strong></th>
                                <th><strong>ACTIONS</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                            <tr>
                                <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span>{{ $invoice->customer->customer_name }}</span>
                                    </div>
                                </td>
                                <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                                <td>
                                    @if($invoice->invoice_type === 'proforma')
                                        <span class="badge badge-info">Proforma</span>
                                    @else
                                        <span class="badge badge-primary">Regular</span>
                                    @endif
                                </td>
                                <td>₹{{ number_format($invoice->subtotal, 2) }}</td>
                                <td>₹{{ number_format($invoice->tax_total, 2) }}</td>
                                <td><strong>₹{{ number_format($invoice->grand_total, 2) }}</strong></td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-info shadow btn-xs sharp me-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger shadow btn-xs sharp" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No invoices found. <a href="{{ route('invoices.create') }}">Create your first invoice</a></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $invoices->firstItem() ?? 0 }} to {{ $invoices->lastItem() ?? 0 }} of {{ $invoices->total() }} invoices
                    </div>
                    <div>
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
