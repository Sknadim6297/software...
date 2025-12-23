<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Contract;
use App\Models\Lead;
use App\Models\InvoiceItem;
use App\Exports\InvoicesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Dompdf\Dompdf;
use Dompdf\Options;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index(Request $request)
    {
        $query = Invoice::with('customer');

        // Apply filters
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('invoice_type')) {
            $query->where('invoice_type', $request->invoice_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->latest()->paginate(15);
        $customers = Customer::where('active', true)->orderBy('customer_name')->get();
        
        return view('invoices.index', compact('invoices', 'customers'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        $customers = Customer::where('active', true)->orderBy('customer_name')->get();
        $contracts = Contract::where('status', 'active')->orderBy('contract_number')->get();
        $invoiceNumber = Invoice::generateInvoiceNumber('tax_invoice');
        return view('invoices.create', compact('customers', 'contracts', 'invoiceNumber'));
    }
    
    /**
     * Get contract details for AJAX
     */
    public function getContractDetails($id)
    {
        $contract = Contract::with('proposal')->findOrFail($id);
        
        // Try to get customer_id from proposal's lead if it exists
        $customerId = null;
        if ($contract->proposal && $contract->proposal->lead_id) {
            $lead = Lead::find($contract->proposal->lead_id);
            if ($lead && $lead->customer_id) {
                $customerId = $lead->customer_id;
            }
        }
        
        // If no customer_id found, try to find customer by name
        if (!$customerId) {
            $customer = Customer::where('customer_name', $contract->customer_name)->first();
            $customerId = $customer ? $customer->id : null;
        }
        
        return response()->json([
            'contract_number' => $contract->contract_number,
            'customer_id' => $customerId,
            'customer_name' => $contract->customer_name,
            'customer_email' => $contract->customer_email,
            'customer_phone' => $contract->customer_phone,
            'final_amount' => $contract->final_amount,
            'total_amount' => $contract->total_amount ?? $contract->final_amount,
            'project_type' => $contract->project_type,
        ]);
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'customer_id' => 'nullable|exists:customers,id',
            'invoice_type' => 'required|in:proforma,tax_invoice,money_receipt',
            'invoice_date' => 'required|date',
            'invoice_ref_no' => 'nullable|string',
            'invoice_ref_date' => 'nullable|date',
            'remarks' => 'nullable|string',
            'customer_gstin' => 'nullable|string',
            'customer_state_code' => 'nullable|string',
            'customer_state_name' => 'nullable|string',
            'tcs_amount' => 'nullable|numeric|min:0',
            'round_off' => 'nullable|numeric',
            'payment_status' => 'nullable|in:paid,unpaid,partially_paid',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_description' => 'required|string',
            'items.*.sac_hsn_code' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.cgst_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.sgst_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.igst_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Generate invoice number
            $invoiceNumber = Invoice::generateInvoiceNumber($validated['invoice_type']);

            // Calculate totals
            $subtotal = 0;
            $taxTotal = 0;
            $discountAmount = 0;

            $invoiceItems = [];
            foreach ($validated['items'] as $item) {
                $quantity = $item['quantity'];
                $rate = $item['rate'];
                $itemTotal = $quantity * $rate;
                
                $discount = isset($item['discount_percentage']) ? ($itemTotal * $item['discount_percentage'] / 100) : 0;
                $discountAmount += $discount;
                
                $taxableAmount = $itemTotal - $discount;
                
                $cgst = isset($item['cgst_percentage']) ? ($taxableAmount * $item['cgst_percentage'] / 100) : 0;
                $sgst = isset($item['sgst_percentage']) ? ($taxableAmount * $item['sgst_percentage'] / 100) : 0;
                $igst = isset($item['igst_percentage']) ? ($taxableAmount * $item['igst_percentage'] / 100) : 0;
                
                $taxAmount = $cgst + $sgst + $igst;
                $taxTotal += $taxAmount;
                
                $totalAmount = $taxableAmount + $taxAmount;
                $subtotal += $itemTotal;

                $invoiceItems[] = [
                    'product_description' => $item['product_description'],
                    'sac_hsn_code' => $item['sac_hsn_code'],
                    'quantity' => $quantity,
                    'rate' => $rate,
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'cgst_percentage' => $item['cgst_percentage'] ?? 0,
                    'sgst_percentage' => $item['sgst_percentage'] ?? 0,
                    'igst_percentage' => $item['igst_percentage'] ?? 0,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                ];
            }

            $tcsAmount = $validated['tcs_amount'] ?? 0;
            $roundOff = $validated['round_off'] ?? 0;
            $grandTotal = $subtotal - $discountAmount + $taxTotal + $tcsAmount + $roundOff;

            // Create invoice
            $invoice = Invoice::create([
                'contract_id' => $validated['contract_id'],
                'customer_id' => $validated['customer_id'],
                'invoice_number' => $invoiceNumber,
                'invoice_type' => $validated['invoice_type'],
                'invoice_date' => $validated['invoice_date'],
                'invoice_ref_no' => $validated['invoice_ref_no'] ?? null,
                'invoice_ref_date' => $validated['invoice_ref_date'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'customer_gstin' => $validated['customer_gstin'] ?? null,
                'customer_state_code' => $validated['customer_state_code'] ?? null,
                'customer_state_name' => $validated['customer_state_name'] ?? null,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_total' => $taxTotal,
                'tcs_amount' => $tcsAmount,
                'round_off' => $roundOff,
                'grand_total' => $grandTotal,
                'payment_status' => $validated['payment_status'] ?? 'unpaid',
                'notes' => $validated['notes'],
            ]);

            // Create invoice items
            foreach ($invoiceItems as $item) {
                $invoice->items()->create($item);
            }

            DB::commit();

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('customer', 'items');
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit(Invoice $invoice)
    {
        $customers = Customer::where('active', true)->orderBy('customer_name')->get();
        $contracts = Contract::with('proposal')->where('status', 'active')->orderBy('contract_number')->get();
        $invoice->load('items', 'contract');
        return view('invoices.edit', compact('invoice', 'customers', 'contracts'));
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'customer_id' => 'nullable|exists:customers,id',
            'invoice_date' => 'required|date',
            'invoice_ref_no' => 'nullable|string',
            'invoice_ref_date' => 'nullable|date',
            'remarks' => 'nullable|string',
            'customer_gstin' => 'nullable|string',
            'customer_state_code' => 'nullable|string',
            'customer_state_name' => 'nullable|string',
            'tcs_amount' => 'nullable|numeric|min:0',
            'round_off' => 'nullable|numeric',
            'payment_status' => 'nullable|in:paid,unpaid,partially_paid',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_description' => 'required|string',
            'items.*.sac_hsn_code' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.cgst_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.sgst_percentage' => 'nullable|numeric|min:0|max:100',
            'items.*.igst_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = 0;
            $taxTotal = 0;
            $discountAmount = 0;

            $invoiceItems = [];
            foreach ($validated['items'] as $item) {
                $quantity = $item['quantity'];
                $rate = $item['rate'];
                $itemTotal = $quantity * $rate;
                
                $discount = isset($item['discount_percentage']) ? ($itemTotal * $item['discount_percentage'] / 100) : 0;
                $discountAmount += $discount;
                
                $taxableAmount = $itemTotal - $discount;
                
                $cgst = isset($item['cgst_percentage']) ? ($taxableAmount * $item['cgst_percentage'] / 100) : 0;
                $sgst = isset($item['sgst_percentage']) ? ($taxableAmount * $item['sgst_percentage'] / 100) : 0;
                $igst = isset($item['igst_percentage']) ? ($taxableAmount * $item['igst_percentage'] / 100) : 0;
                
                $taxAmount = $cgst + $sgst + $igst;
                $taxTotal += $taxAmount;
                
                $totalAmount = $taxableAmount + $taxAmount;
                $subtotal += $itemTotal;

                $invoiceItems[] = [
                    'product_description' => $item['product_description'],
                    'sac_hsn_code' => $item['sac_hsn_code'],
                    'quantity' => $quantity,
                    'rate' => $rate,
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'cgst_percentage' => $item['cgst_percentage'] ?? 0,
                    'sgst_percentage' => $item['sgst_percentage'] ?? 0,
                    'igst_percentage' => $item['igst_percentage'] ?? 0,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                ];
            }

            $tcsAmount = $validated['tcs_amount'] ?? 0;
            $roundOff = $validated['round_off'] ?? 0;
            $grandTotal = $subtotal - $discountAmount + $taxTotal + $tcsAmount + $roundOff;

            // Update invoice
            $invoice->update([
                'contract_id' => $validated['contract_id'],
                'customer_id' => $validated['customer_id'],
                'invoice_date' => $validated['invoice_date'],
                'invoice_ref_no' => $validated['invoice_ref_no'] ?? null,
                'invoice_ref_date' => $validated['invoice_ref_date'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
                'customer_gstin' => $validated['customer_gstin'] ?? null,
                'customer_state_code' => $validated['customer_state_code'] ?? null,
                'customer_state_name' => $validated['customer_state_name'] ?? null,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_total' => $taxTotal,
                'tcs_amount' => $tcsAmount,
                'round_off' => $roundOff,
                'grand_total' => $grandTotal,
                'payment_status' => $validated['payment_status'] ?? $invoice->payment_status,
                'notes' => $validated['notes'],
            ]);

            // Delete old items and create new ones
            $invoice->items()->delete();
            foreach ($invoiceItems as $item) {
                $invoice->items()->create($item);
            }

            DB::commit();

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating invoice: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Export invoices to Excel
     */
    public function exportExcel(Request $request)
    {
        $filters = $request->only(['customer_id', 'status', 'invoice_type', 'date_from', 'date_to']);
        
        return Excel::download(new InvoicesExport($filters), 'invoices_' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Export invoices to PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Invoice::with('customer');

        // Apply same filters as Excel export
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('invoice_type')) {
            $query->where('invoice_type', $request->invoice_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->latest()->get();

        $html = view('invoices.pdf', compact('invoices'))->render();
        
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="invoices_' . date('Y-m-d') . '.pdf"');
    }

    /**
     * Generate single invoice PDF
     */
    public function generatePdf(Invoice $invoice)
    {
        $invoice->load(['customer', 'items']);
        
        // Select the appropriate template based on invoice type
        $viewName = match($invoice->invoice_type) {
            'proforma' => 'invoices.proforma-invoice-pdf',
            'money_receipt' => 'invoices.money-receipt-pdf',
            default => 'invoices.tax-invoice-pdf',
        };
        
        $html = view($viewName, compact('invoice'))->render();
        
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = match($invoice->invoice_type) {
            'proforma' => 'proforma_invoice_',
            'money_receipt' => 'money_receipt_',
            default => 'tax_invoice_',
        };
        
        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . $invoice->invoice_number . '.pdf"');
    }
    
    /**
     * Get invoice number based on type (AJAX)
     */
    public function getInvoiceNumber(Request $request)
    {
        $type = $request->input('type', 'tax_invoice');
        $invoiceNumber = Invoice::generateInvoiceNumber($type);
        
        return response()->json(['invoice_number' => $invoiceNumber]);
    }
}
