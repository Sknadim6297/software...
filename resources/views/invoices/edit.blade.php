@extends('layouts.app')

@section('title', 'Edit Invoice - Konnectix')

@section('page-title', 'Edit Invoice #' . $invoice->invoice_number)

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Invoice Information</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoiceForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Contract <span class="text-danger">*</span></label>
                            <select class="form-control @error('contract_id') is-invalid @enderror" name="contract_id" id="contractSelect" required>
                                <option value="">Select Contract</option>
                                @foreach($contracts as $contract)
                                    <option value="{{ $contract->id }}" 
                                        {{ old('contract_id', $invoice->contract_id) == $contract->id ? 'selected' : '' }}
                                        data-customer-name="{{ $contract->customer_name }}"
                                        data-amount="{{ $contract->final_amount }}"
                                        data-project-type="{{ $contract->project_type }}">
                                        {{ $contract->contract_number }} - {{ $contract->customer_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('contract_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Select contract to auto-fill customer and rate details</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Customer <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customerNameDisplay" readonly 
                                value="{{ $invoice->contract ? $invoice->contract->customer_name : ($invoice->customer ? $invoice->customer->customer_name : '') }}" 
                                placeholder="Auto-filled from contract">
                            <input type="hidden" name="customer_id" id="customerId" value="{{ old('customer_id', $invoice->customer_id) }}">
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Invoice Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('invoice_type') is-invalid @enderror" name="invoice_type" id="invoiceType" required disabled>
                                <option value="tax_invoice" {{ old('invoice_type', $invoice->invoice_type) == 'tax_invoice' ? 'selected' : '' }}>Tax Invoice</option>
                                <option value="proforma" {{ old('invoice_type', $invoice->invoice_type) == 'proforma' ? 'selected' : '' }}>Proforma Invoice</option>
                                <option value="money_receipt" {{ old('invoice_type', $invoice->invoice_type) == 'money_receipt' ? 'selected' : '' }}>Money Receipt</option>
                            </select>
                            @error('invoice_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Invoice type cannot be changed</small>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('invoice_date') is-invalid @enderror" 
                                name="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" required>
                            @error('invoice_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Invoice Ref No.</label>
                            <input type="text" class="form-control @error('invoice_ref_no') is-invalid @enderror" 
                                name="invoice_ref_no" value="{{ old('invoice_ref_no', $invoice->invoice_ref_no) }}" placeholder="e.g., 00127">
                            @error('invoice_ref_no')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Invoice Ref Date</label>
                            <input type="date" class="form-control @error('invoice_ref_date') is-invalid @enderror" 
                                name="invoice_ref_date" value="{{ old('invoice_ref_date', $invoice->invoice_ref_date ? $invoice->invoice_ref_date->format('Y-m-d') : '') }}">
                            @error('invoice_ref_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Customer GSTIN</label>
                            <input type="text" class="form-control @error('customer_gstin') is-invalid @enderror" 
                                name="customer_gstin" value="{{ old('customer_gstin', $invoice->customer_gstin) }}" placeholder="e.g., 19BNZPS8515D1Z7">
                            @error('customer_gstin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Customer State Code</label>
                            <input type="text" class="form-control @error('customer_state_code') is-invalid @enderror" 
                                name="customer_state_code" value="{{ old('customer_state_code', $invoice->customer_state_code) }}" placeholder="e.g., 19">
                            @error('customer_state_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Customer State Name</label>
                            <input type="text" class="form-control @error('customer_state_name') is-invalid @enderror" 
                                name="customer_state_name" value="{{ old('customer_state_name', $invoice->customer_state_name) }}" placeholder="e.g., West Bengal">
                            @error('customer_state_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Payment Status</label>
                            <select class="form-control @error('payment_status') is-invalid @enderror" name="payment_status">
                                <option value="unpaid" {{ old('payment_status', $invoice->payment_status) == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ old('payment_status', $invoice->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="partially_paid" {{ old('payment_status', $invoice->payment_status) == 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                            </select>
                            @error('payment_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Contract Amount</label>
                            <input type="text" class="form-control" id="contractAmount" readonly 
                                value="{{ $invoice->contract ? '₹' . number_format($invoice->contract->final_amount, 2) : '' }}"
                                placeholder="Auto-filled from contract">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Project Type</label>
                            <input type="text" class="form-control" id="projectType" readonly 
                                value="{{ $invoice->contract ? $invoice->contract->project_type : '' }}"
                                placeholder="Auto-filled from contract">
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Remarks</label>
                            <input type="text" class="form-control @error('remarks') is-invalid @enderror" 
                                name="remarks" value="{{ old('remarks', $invoice->remarks) }}" placeholder="e.g., PROFORMA INVOICE">
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <h5>Invoice Items</h5>
                            <p class="text-muted">Edit products/services in the invoice</p>
                        </div>
                    </div>

                    <div id="invoice-items">
                        <!-- Existing invoice items will be populated here -->
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
                                <textarea class="form-control" name="notes" rows="4" placeholder="Add any notes or additional information">{{ old('notes', $invoice->notes) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5>Invoice Summary</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td>Subtotal:</td>
                                            <td class="text-end"><strong id="subtotalDisplay">₹{{ number_format($invoice->subtotal, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Discount:</td>
                                            <td class="text-end"><strong id="discountDisplay">₹{{ number_format($invoice->discount_amount, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Tax Total:</td>
                                            <td class="text-end"><strong id="taxDisplay">₹{{ number_format($invoice->tax_total, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>TCS:</label>
                                                <input type="number" class="form-control form-control-sm" name="tcs_amount" id="tcsAmount" 
                                                    step="0.01" min="0" value="{{ old('tcs_amount', $invoice->tcs_amount ?? 0) }}" style="width: 100px; display: inline-block;">
                                            </td>
                                            <td class="text-end"><strong id="tcsDisplay">₹{{ number_format($invoice->tcs_amount ?? 0, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <label>Round Off:</label>
                                                <input type="number" class="form-control form-control-sm" name="round_off" id="roundOff" 
                                                    step="0.01" value="{{ old('round_off', $invoice->round_off ?? 0) }}" style="width: 100px; display: inline-block;">
                                            </td>
                                            <td class="text-end"><strong id="roundOffDisplay">₹{{ number_format($invoice->round_off ?? 0, 2) }}</strong></td>
                                        </tr>
                                        <tr class="border-top">
                                            <td><h5>Grand Total:</h5></td>
                                            <td class="text-end"><h5 id="grandTotalDisplay">₹{{ number_format($invoice->grand_total, 2) }}</h5></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('invoices.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Invoice</button>
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
let contractRate = 0;

// Existing invoice items from server
const existingItems = @json($invoice->items);

document.addEventListener('DOMContentLoaded', function() {
    // Load existing items
    loadExistingItems();
    
    // Add item button
    document.getElementById('addItemBtn').addEventListener('click', addInvoiceItem);
    
    // Handle contract selection
    document.getElementById('contractSelect').addEventListener('change', function() {
        const contractId = this.value;
        if (contractId) {
            fetchContractDetails(contractId);
        } else {
            clearContractDetails();
        }
    });
    
    // Set initial contract rate if contract is already selected
    const initialContract = document.getElementById('contractSelect');
    if (initialContract.value) {
        const selectedOption = initialContract.options[initialContract.selectedIndex];
        contractRate = parseFloat(selectedOption.getAttribute('data-amount')) || 0;
    }
});

function fetchContractDetails(contractId) {
    fetch(`/invoices/contract/${contractId}/details`)
        .then(response => response.json())
        .then(data => {
            // Populate customer details
            document.getElementById('customerNameDisplay').value = data.customer_name;
            document.getElementById('customerId').value = data.customer_id || '';
            document.getElementById('contractAmount').value = '₹' + parseFloat(data.final_amount).toFixed(2);
            document.getElementById('projectType').value = data.project_type || '-';
            
            // Store contract rate for items
            contractRate = parseFloat(data.final_amount);
        })
        .catch(error => {
            console.error('Error fetching contract details:', error);
            alert('Error loading contract details. Please try again.');
        });
}

function clearContractDetails() {
    document.getElementById('customerNameDisplay').value = '';
    document.getElementById('customerId').value = '';
    document.getElementById('contractAmount').value = '';
    document.getElementById('projectType').value = '';
    contractRate = 0;
}

function loadExistingItems() {
    existingItems.forEach((item, index) => {
        addInvoiceItem(item, index);
    });
    
    // Set itemIndex to continue from existing items
    itemIndex = existingItems.length;
}

function addInvoiceItem(existingItem = null, existingIndex = null) {
    const index = existingIndex !== null ? existingIndex : itemIndex;
    const isExisting = existingItem !== null;
    
    const container = document.getElementById('invoice-items');
    const itemHtml = `
        <div class="card mb-3 invoice-item" data-index="${index}">
            <div class="card-body">
                ${isExisting ? `<input type="hidden" name="items[${index}][id]" value="${existingItem.id}">` : ''}
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Product Description / Service <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="items[${index}][product_description]" 
                            placeholder="e.g., Website Design and Development" required
                            value="${isExisting ? existingItem.product_description : ''}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">SAC/HSN Code</label>
                        <input type="text" class="form-control" name="items[${index}][sac_hsn_code]" 
                            placeholder="9983" value="${isExisting ? existingItem.sac_hsn_code : '9983'}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" class="form-control item-quantity" name="items[${index}][quantity]" 
                            min="1" required data-index="${index}" value="${isExisting ? existingItem.quantity : '1'}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Rate (₹) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control item-rate" name="items[${index}][rate]" 
                            step="0.01" min="0" placeholder="15000" required data-index="${index}"
                            value="${isExisting ? existingItem.rate : ''}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Discount (%)</label>
                        <input type="number" class="form-control item-discount" name="items[${index}][discount_percentage]" 
                            step="0.01" min="0" max="100" data-index="${index}"
                            value="${isExisting ? existingItem.discount_percentage : '0'}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">CGST (%)</label>
                        <input type="number" class="form-control item-cgst" name="items[${index}][cgst_percentage]" 
                            step="0.01" min="0" max="100" data-index="${index}"
                            value="${isExisting ? existingItem.cgst_percentage : '9'}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">SGST (%)</label>
                        <input type="number" class="form-control item-sgst" name="items[${index}][sgst_percentage]" 
                            step="0.01" min="0" max="100" data-index="${index}"
                            value="${isExisting ? existingItem.sgst_percentage : '9'}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">IGST (%)</label>
                        <input type="number" class="form-control item-igst" name="items[${index}][igst_percentage]" 
                            step="0.01" min="0" max="100" data-index="${index}"
                            value="${isExisting ? existingItem.igst_percentage : '0'}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tax Amount</label>
                        <input type="text" class="form-control item-tax-amount" readonly data-index="${index}" value="₹0.00">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Item Total</label>
                        <input type="text" class="form-control item-total" readonly data-index="${index}" value="₹0.00">
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm w-100 remove-item-btn" data-index="${index}">
                            <i class="fa fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', itemHtml);
    
    // Attach event listeners for calculations
    attachItemCalculationEvents(index);
    
    // Calculate initial totals for existing items
    if (isExisting) {
        calculateItemTotal(index);
    }
    
    if (existingIndex === null) {
        itemIndex++;
    }
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
    
    const tcsAmount = parseFloat(document.getElementById('tcsAmount')?.value) || 0;
    const roundOff = parseFloat(document.getElementById('roundOff')?.value) || 0;
    const grandTotal = subtotal - totalDiscount + totalTax + tcsAmount + roundOff;
    
    document.getElementById('subtotalDisplay').textContent = '₹' + subtotal.toFixed(2);
    document.getElementById('discountDisplay').textContent = '₹' + totalDiscount.toFixed(2);
    document.getElementById('taxDisplay').textContent = '₹' + totalTax.toFixed(2);
    document.getElementById('tcsDisplay').textContent = '₹' + tcsAmount.toFixed(2);
    document.getElementById('roundOffDisplay').textContent = '₹' + roundOff.toFixed(2);
    document.getElementById('grandTotalDisplay').textContent = '₹' + grandTotal.toFixed(2);
}

// Add event listeners for TCS and Round Off
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('tcsAmount')?.addEventListener('input', updateInvoiceSummary);
    document.getElementById('roundOff')?.addEventListener('input', updateInvoiceSummary);
});

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