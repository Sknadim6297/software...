<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index()
    {
        $invoices = Invoice::with('customer')->latest()->paginate(15);
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        $customers = Customer::where('active', true)->orderBy('customer_name')->get();
        $invoiceNumber = Invoice::generateInvoiceNumber('regular');
        return view('invoices.create', compact('customers', 'invoiceNumber'));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_type' => 'required|in:regular,proforma',
            'invoice_date' => 'required|date',
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

            $grandTotal = $subtotal - $discountAmount + $taxTotal;

            // Create invoice
            $invoice = Invoice::create([
                'customer_id' => $validated['customer_id'],
                'invoice_number' => $invoiceNumber,
                'invoice_type' => $validated['invoice_type'],
                'invoice_date' => $validated['invoice_date'],
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_total' => $taxTotal,
                'grand_total' => $grandTotal,
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
        $invoice->load('items');
        return view('invoices.edit', compact('invoice', 'customers'));
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => 'required|date',
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

            $grandTotal = $subtotal - $discountAmount + $taxTotal;

            // Update invoice
            $invoice->update([
                'customer_id' => $validated['customer_id'],
                'invoice_date' => $validated['invoice_date'],
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_total' => $taxTotal,
                'grand_total' => $grandTotal,
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
}
