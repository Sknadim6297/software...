<?php

namespace App\Http\Controllers;

use App\Models\ServiceRenewal;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Mail\RenewalReminderMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ServiceRenewalController extends Controller
{
    /**
     * Display a listing of the service renewals.
     */
    public function index()
    {
        $services = ServiceRenewal::with(['customer', 'verifiedBy'])
            ->orderBy('renewal_date', 'asc')
            ->paginate(20);
        
        return view('service-renewals.index', compact('services'));
    }

    /**
     * Show the form for creating a new service renewal.
     */
    public function create()
    {
        $customers = Customer::all();
        return view('service-renewals.create', compact('customers'));
    }

    /**
     * Store a newly created service renewal in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_type' => 'required|in:Domain,Server,Digital Marketing,Website Maintenance,Application Maintenance,Software Maintenance',
            'start_date' => 'required|date',
            'renewal_date' => 'required|date|after:start_date',
            'renewal_type' => 'required|in:Monthly,Yearly,Quarterly',
            'amount' => 'required|numeric|min:0',
            'service_status' => 'required|in:Active,Deactive',
        ]);

        $service = ServiceRenewal::create($validated);

        return redirect()->route('service-renewals.index')
            ->with('success', 'Service renewal created successfully.');
    }

    /**
     * Display the specified service renewal.
     */
    public function show(ServiceRenewal $serviceRenewal)
    {
        $serviceRenewal->load(['customer', 'verifiedBy']);
        return view('service-renewals.show', compact('serviceRenewal'));
    }

    /**
     * Show the form for editing the specified service renewal.
     */
    public function edit(ServiceRenewal $serviceRenewal)
    {
        $customers = Customer::all();
        return view('service-renewals.edit', compact('serviceRenewal', 'customers'));
    }

    /**
     * Update the specified service renewal in storage.
     */
    public function update(Request $request, ServiceRenewal $serviceRenewal)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_type' => 'required|in:Domain,Server,Digital Marketing,Website Maintenance,Application Maintenance,Software Maintenance',
            'start_date' => 'required|date',
            'renewal_date' => 'required|date',
            'renewal_type' => 'required|in:Monthly,Yearly,Quarterly',
            'amount' => 'required|numeric|min:0',
            'service_status' => 'required|in:Active,Deactive',
        ]);

        $serviceRenewal->update($validated);

        return redirect()->route('service-renewals.index')
            ->with('success', 'Service renewal updated successfully.');
    }

    /**
     * Remove the specified service renewal from storage.
     */
    public function destroy(ServiceRenewal $serviceRenewal)
    {
        $serviceRenewal->delete();

        return redirect()->route('service-renewals.index')
            ->with('success', 'Service renewal deleted successfully.');
    }

    /**
     * Process renewal request - BDM enters transaction ID.
     */
    public function processRenewal(Request $request, ServiceRenewal $serviceRenewal)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|string|max:255',
        ]);

        $serviceRenewal->update([
            'transaction_id' => $validated['transaction_id'],
        ]);

        return redirect()->route('service-renewals.index')
            ->with('success', 'Transaction ID submitted. Waiting for admin verification.');
    }

    /**
     * Admin verifies the renewal transaction.
     */
    public function verifyRenewal(ServiceRenewal $serviceRenewal)
    {
        // Create invoice for the renewal
        $invoice = Invoice::create([
            'customer_id' => $serviceRenewal->customer_id,
            'invoice_number' => Invoice::generateInvoiceNumber('tax_invoice'),
            'invoice_type' => 'tax_invoice',
            'invoice_date' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(30),
            'payment_status' => 'paid',
            'subtotal' => $serviceRenewal->amount,
            'tax_total' => 0,
            'grand_total' => $serviceRenewal->amount,
            'notes' => "Service Renewal: {$serviceRenewal->service_type}",
        ]);

        // Create invoice item
        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'product_description' => "{$serviceRenewal->service_type} - {$serviceRenewal->renewal_type} Renewal",
            'quantity' => 1,
            'rate' => $serviceRenewal->amount,
            'total_amount' => $serviceRenewal->amount,
        ]);

        // Update service renewal
        $nextRenewalDate = $serviceRenewal->calculateNextRenewalDate();
        
        $serviceRenewal->update([
            'verified_by' => Auth::id(),
            'verified_at' => Carbon::now(),
            'renewal_date' => $nextRenewalDate,
            'transaction_id' => null, // Reset for next renewal
            'renewal_mail_sent' => false, // Reset mail flag
        ]);

        return redirect()->route('service-renewals.index')
            ->with('success', 'Renewal verified successfully. Invoice created and renewal date updated.');
    }

    /**
     * Stop renewal service.
     */
    public function stopRenewal(Request $request, ServiceRenewal $serviceRenewal)
    {
        $validated = $request->validate([
            'stop_reason' => 'required|string|min:10',
        ]);

        $serviceRenewal->update([
            'service_status' => 'Deactive',
            'auto_renewal' => false,
            'stop_reason' => $validated['stop_reason'],
        ]);

        return redirect()->route('service-renewals.index')
            ->with('success', 'Service renewal stopped successfully.');
    }

    /**
     * Send renewal reminder email manually.
     */
    public function sendRenewalReminder(ServiceRenewal $serviceRenewal)
    {
        try {
            // Create invoice for renewal
            $invoice = Invoice::create([
                'customer_id' => $serviceRenewal->customer_id,
                'invoice_number' => Invoice::generateInvoiceNumber('tax_invoice'),
                'invoice_type' => 'tax_invoice',
                'invoice_date' => Carbon::now(),
                'due_date' => $serviceRenewal->renewal_date,
                'payment_status' => 'unpaid',
                'subtotal' => $serviceRenewal->amount,
                'tax_total' => 0,
                'grand_total' => $serviceRenewal->amount,
                'notes' => "Service Renewal: {$serviceRenewal->service_type}",
            ]);

            // Create invoice item
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_description' => "{$serviceRenewal->service_type} - {$serviceRenewal->renewal_type} Renewal",
                'quantity' => 1,
                'rate' => $serviceRenewal->amount,
                'total_amount' => $serviceRenewal->amount,
            ]);

            // Send email with invoice
            Mail::to($serviceRenewal->customer->email)
                ->send(new RenewalReminderMail($serviceRenewal, $invoice));

            $serviceRenewal->update([
                'renewal_mail_sent' => true,
                'last_renewal_mail_sent_at' => Carbon::now(),
            ]);

            return redirect()->route('service-renewals.index')
                ->with('success', 'Renewal reminder email sent successfully.');
        } catch (\Exception $e) {
            return redirect()->route('service-renewals.index')
                ->with('error', 'Failed to send renewal reminder: ' . $e->getMessage());
        }
    }
}
