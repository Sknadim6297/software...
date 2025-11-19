@extends('layouts.app')

@section('title', 'Invoices - Konnectix')

@section('page-title', 'Invoice Management')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filter Card -->
        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title">Filters & Export</h4>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('invoices.index') }}" id="filterForm">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Customer</label>
                            <select class="form-control" name="customer_id">
                                <option value="">All Customers</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label">Type</label>
                            <select class="form-control" name="invoice_type">
                                <option value="">All Types</option>
                                <option value="regular" {{ request('invoice_type') == 'regular' ? 'selected' : '' }}>Regular</option>
                                <option value="proforma" {{ request('invoice_type') == 'proforma' ? 'selected' : '' }}>Proforma</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label">From Date</label>
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label">To Date</label>
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-sync"></i> Reset Filters
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <a href="{{ route('invoices.export.excel') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" 
                                       class="btn btn-success btn-sm">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </a>
                                    <a href="{{ route('invoices.export.pdf') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" 
                                       class="btn btn-danger btn-sm">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Invoices Table Card -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">All Invoices ({{ $invoices->total() }})</h4>
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
                                <th><strong>STATUS</strong></th>
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
                                    <div>
                                        <span class="font-weight-bold">{{ $invoice->customer->customer_name ?? 'N/A' }}</span>
                                        @if($invoice->customer && $invoice->customer->company_name)
                                            <br><small class="text-muted">{{ $invoice->customer->company_name }}</small>
                                        @endif
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
                                <td>
                                    @php
                                        $statusClass = match($invoice->status) {
                                            'paid' => 'success',
                                            'pending' => 'warning',
                                            'overdue' => 'danger',
                                            'cancelled' => 'secondary',
                                            default => 'primary'
                                        };
                                    @endphp
                                    <span class="badge badge-{{ $statusClass }}">{{ ucfirst($invoice->status) }}</span>
                                </td>
                                <td>₹{{ number_format($invoice->subtotal, 2) }}</td>
                                <td>₹{{ number_format($invoice->tax_amount, 2) }}</td>
                                <td><strong>₹{{ number_format($invoice->total_amount, 2) }}</strong></td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-info shadow btn-xs sharp me-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-primary shadow btn-xs sharp me-1" title="Edit">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-secondary shadow btn-xs sharp me-1" title="Download PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
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
                                <td colspan="9" class="text-center">
                                    @if(request()->hasAny(['customer_id', 'status', 'invoice_type', 'date_from', 'date_to']))
                                        No invoices found matching your filters. <a href="{{ route('invoices.index') }}">Clear filters</a>
                                    @else
                                        No invoices found. <a href="{{ route('invoices.create') }}">Create your first invoice</a>
                                    @endif
                                </td>
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
                        {{ $invoices->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change
    const filterForm = document.getElementById('filterForm');
    const filterInputs = filterForm.querySelectorAll('select, input[type="date"]');
    
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Small delay to allow for multiple rapid changes
            setTimeout(() => {
                filterForm.submit();
            }, 100);
        });
    });
});
</script>
@endpush
