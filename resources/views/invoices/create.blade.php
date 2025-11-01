@extends('layouts.app')

@section('title', 'Create Invoice - Konnectix')

@section('page-title', 'Create New Invoice')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Invoice Information</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Customer <span class="text-danger">*</span></label>
                            <select class="form-control @error('customer_id') is-invalid @enderror" name="customer_id" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->customer_name }} @if($customer->company_name) ({{ $customer->company_name }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Invoice Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('invoice_type') is-invalid @enderror" name="invoice_type" id="invoiceType" required>
                                <option value="regular" {{ old('invoice_type', 'regular') == 'regular' ? 'selected' : '' }}>Regular Invoice</option>
                                <option value="proforma" {{ old('invoice_type') == 'proforma' ? 'selected' : '' }}>Proforma Invoice</option>
                            </select>
                            @error('invoice_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('invoice_date') is-invalid @enderror" 
                                name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                            @error('invoice_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <h5>Invoice Items</h5>
                            <p class="text-muted">Add products/services to the invoice</p>
                        </div>
                    </div>

                    <div id="invoice-items">
                        <!-- Invoice items will be added here dynamically -->
                    </div>

                    <div class="row mb-4">
                        <div class="col-12">
                            <button type="button" class="btn btn-secondary btn-sm" id="addItemBtn">
                                <i class="flaticon-381-plus"></i> Add Line Item
                            </button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="4" placeholder="Add any notes or additional information">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5>Invoice Summary</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td>Subtotal:</td>
                                            <td class="text-end"><strong id="subtotalDisplay">₹0.00</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Discount:</td>
                                            <td class="text-end"><strong id="discountDisplay">₹0.00</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Tax Total:</td>
                                            <td class="text-end"><strong id="taxDisplay">₹0.00</strong></td>
                                        </tr>
                                        <tr class="border-top">
                                            <td><h5>Grand Total:</h5></td>
                                            <td class="text-end"><h5 id="grandTotalDisplay">₹0.00</h5></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('invoices.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let itemIndex = 0;

document.addEventListener('DOMContentLoaded', function() {
    // Add first item by default
    addInvoiceItem();
    
    // Add item button
    document.getElementById('addItemBtn').addEventListener('click', addInvoiceItem);
});

function addInvoiceItem() {
    const container = document.getElementById('invoice-items');
    const itemHtml = `
        <div class="card mb-3 invoice-item" data-index="${itemIndex}">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Product Description / Service <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="items[${itemIndex}][product_description]" 
                            placeholder="e.g., Website Design and Development" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">SAC/HSN Code</label>
                        <input type="text" class="form-control" name="items[${itemIndex}][sac_hsn_code]" 
                            value="9983" placeholder="9983">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control item-quantity" name="items[${itemIndex}][quantity]" 
                            value="1" min="1" required data-index="${itemIndex}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Rate (₹) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control item-rate" name="items[${itemIndex}][rate]" 
                            step="0.01" min="0" placeholder="15000" required data-index="${itemIndex}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Discount (%)</label>
                        <input type="number" class="form-control item-discount" name="items[${itemIndex}][discount_percentage]" 
                            step="0.01" min="0" max="100" value="0" data-index="${itemIndex}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">CGST (%)</label>
                        <input type="number" class="form-control item-cgst" name="items[${itemIndex}][cgst_percentage]" 
                            step="0.01" min="0" max="100" value="9" data-index="${itemIndex}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">SGST (%)</label>
                        <input type="number" class="form-control item-sgst" name="items[${itemIndex}][sgst_percentage]" 
                            step="0.01" min="0" max="100" value="9" data-index="${itemIndex}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">IGST (%)</label>
                        <input type="number" class="form-control item-igst" name="items[${itemIndex}][igst_percentage]" 
                            step="0.01" min="0" max="100" value="0" data-index="${itemIndex}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tax Amount</label>
                        <input type="text" class="form-control item-tax-amount" readonly data-index="${itemIndex}" value="₹0.00">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Item Total</label>
                        <input type="text" class="form-control item-total" readonly data-index="${itemIndex}" value="₹0.00">
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm w-100 remove-item-btn" data-index="${itemIndex}">
                            <i class="fa fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', itemHtml);
    
    // Attach event listeners for calculations
    attachItemCalculationEvents(itemIndex);
    
    itemIndex++;
}

function attachItemCalculationEvents(index) {
    const item = document.querySelector(`.invoice-item[data-index="${index}"]`);
    
    const quantity = item.querySelector('.item-quantity');
    const rate = item.querySelector('.item-rate');
    const discount = item.querySelector('.item-discount');
    const cgst = item.querySelector('.item-cgst');
    const sgst = item.querySelector('.item-sgst');
    const igst = item.querySelector('.item-igst');
    const removeBtn = item.querySelector('.remove-item-btn');
    
    [quantity, rate, discount, cgst, sgst, igst].forEach(input => {
        input.addEventListener('input', () => calculateItemTotal(index));
    });
    
    removeBtn.addEventListener('click', () => removeItem(index));
}

function calculateItemTotal(index) {
    const item = document.querySelector(`.invoice-item[data-index="${index}"]`);
    
    const quantity = parseFloat(item.querySelector('.item-quantity').value) || 0;
    const rate = parseFloat(item.querySelector('.item-rate').value) || 0;
    const discountPct = parseFloat(item.querySelector('.item-discount').value) || 0;
    const cgstPct = parseFloat(item.querySelector('.item-cgst').value) || 0;
    const sgstPct = parseFloat(item.querySelector('.item-sgst').value) || 0;
    const igstPct = parseFloat(item.querySelector('.item-igst').value) || 0;
    
    const itemTotal = quantity * rate;
    const discountAmount = (itemTotal * discountPct) / 100;
    const taxableAmount = itemTotal - discountAmount;
    
    const cgstAmount = (taxableAmount * cgstPct) / 100;
    const sgstAmount = (taxableAmount * sgstPct) / 100;
    const igstAmount = (taxableAmount * igstPct) / 100;
    
    const taxAmount = cgstAmount + sgstAmount + igstAmount;
    const total = taxableAmount + taxAmount;
    
    item.querySelector('.item-tax-amount').value = '₹' + taxAmount.toFixed(2);
    item.querySelector('.item-total').value = '₹' + total.toFixed(2);
    
    updateInvoiceSummary();
}

function updateInvoiceSummary() {
    let subtotal = 0;
    let totalDiscount = 0;
    let totalTax = 0;
    
    document.querySelectorAll('.invoice-item').forEach(item => {
        const index = item.dataset.index;
        const quantity = parseFloat(item.querySelector('.item-quantity').value) || 0;
        const rate = parseFloat(item.querySelector('.item-rate').value) || 0;
        const discountPct = parseFloat(item.querySelector('.item-discount').value) || 0;
        const cgstPct = parseFloat(item.querySelector('.item-cgst').value) || 0;
        const sgstPct = parseFloat(item.querySelector('.item-sgst').value) || 0;
        const igstPct = parseFloat(item.querySelector('.item-igst').value) || 0;
        
        const itemTotal = quantity * rate;
        subtotal += itemTotal;
        
        const discountAmount = (itemTotal * discountPct) / 100;
        totalDiscount += discountAmount;
        
        const taxableAmount = itemTotal - discountAmount;
        const taxAmount = (taxableAmount * (cgstPct + sgstPct + igstPct)) / 100;
        totalTax += taxAmount;
    });
    
    const grandTotal = subtotal - totalDiscount + totalTax;
    
    document.getElementById('subtotalDisplay').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('discountDisplay').textContent = '₹' + totalDiscount.toFixed(2);
    document.getElementById('taxDisplay').textContent = '₹' + totalTax.toFixed(2);
    document.getElementById('grandTotalDisplay').textContent = '₹' + grandTotal.toFixed(2);
}

function removeItem(index) {
    const item = document.querySelector(`.invoice-item[data-index="${index}"]`);
    const itemCount = document.querySelectorAll('.invoice-item').length;
    
    if (itemCount > 1) {
        item.remove();
        updateInvoiceSummary();
    } else {
        alert('At least one item is required in the invoice.');
    }
}
</script>
@endpush
