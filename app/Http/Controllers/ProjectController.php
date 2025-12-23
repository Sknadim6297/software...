<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectInstallment;
use App\Models\MaintenanceContract;
use App\Models\Customer;
use App\Models\User;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Mail\PaymentRequestMail;
use App\Mail\MaintenanceContractMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index()
    {
        $projects = Project::with(['customer', 'coordinator'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $customers = Customer::all();
        $coordinators = User::all();
        return view('projects.create', compact('customers', 'coordinators'));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'project_name' => 'required|string|max:255',
            'project_type' => 'required|in:Website,Software,Application',
            'start_date' => 'required|date',
            'project_valuation' => 'required|numeric|min:0',
            'upfront_payment' => 'nullable|numeric|min:0',
            'first_installment' => 'nullable|numeric|min:0',
            'second_installment' => 'nullable|numeric|min:0',
            'third_installment' => 'nullable|numeric|min:0',
            'project_coordinator_id' => 'required|exists:users,id',
        ]);

        $project = Project::create($validated);

        // Create installment records
        if ($validated['upfront_payment'] > 0) {
            ProjectInstallment::create([
                'project_id' => $project->id,
                'installment_type' => 'Upfront',
                'amount' => $validated['upfront_payment'],
            ]);
        }
        if ($validated['first_installment'] > 0) {
            ProjectInstallment::create([
                'project_id' => $project->id,
                'installment_type' => 'First',
                'amount' => $validated['first_installment'],
            ]);
        }
        if ($validated['second_installment'] > 0) {
            ProjectInstallment::create([
                'project_id' => $project->id,
                'installment_type' => 'Second',
                'amount' => $validated['second_installment'],
            ]);
        }
        if ($validated['third_installment'] > 0) {
            ProjectInstallment::create([
                'project_id' => $project->id,
                'installment_type' => 'Third',
                'amount' => $validated['third_installment'],
            ]);
        }

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        $project->load(['customer', 'coordinator', 'installments', 'maintenanceContract']);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        $customers = Customer::all();
        $coordinators = User::all();
        return view('projects.edit', compact('project', 'customers', 'coordinators'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'project_name' => 'required|string|max:255',
            'project_type' => 'required|in:Website,Software,Application',
            'start_date' => 'required|date',
            'project_valuation' => 'required|numeric|min:0',
            'upfront_payment' => 'nullable|numeric|min:0',
            'first_installment' => 'nullable|numeric|min:0',
            'second_installment' => 'nullable|numeric|min:0',
            'third_installment' => 'nullable|numeric|min:0',
            'project_coordinator_id' => 'required|exists:users,id',
            'project_status' => 'required|in:In Progress,Completed',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    /**
     * Process payment for next installment.
     */
    public function takePayment(Project $project)
    {
        $nextInstallment = $project->getNextInstallment();
        
        if (!$nextInstallment) {
            return redirect()->back()
                ->with('error', 'All installments have been paid.');
        }

        // Create invoice for the installment
        $invoice = Invoice::create([
            'customer_id' => $project->customer_id,
            'invoice_number' => Invoice::generateInvoiceNumber('tax_invoice'),
            'invoice_type' => 'tax_invoice',
            'invoice_date' => Carbon::now(),
            'due_date' => Carbon::now()->addDays(15),
            'payment_status' => 'unpaid',
            'subtotal' => $nextInstallment['amount'],
            'tax_total' => 0,
            'grand_total' => $nextInstallment['amount'],
            'notes' => "Project: {$project->project_name} - {$nextInstallment['type']} Installment",
        ]);

        // Create invoice item
        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'product_description' => "{$project->project_name} - {$nextInstallment['type']} Installment Payment",
            'quantity' => 1,
            'rate' => $nextInstallment['amount'],
            'total_amount' => $nextInstallment['amount'],
        ]);

        // Update installment record
        $installment = ProjectInstallment::where('project_id', $project->id)
            ->where('installment_type', $nextInstallment['type'])
            ->first();
        
        if ($installment) {
            $installment->update(['invoice_id' => $invoice->id]);
        }

        // Send payment request email
        try {
            Mail::to($project->customer->email)
                ->send(new PaymentRequestMail($project, $invoice, $nextInstallment));
        } catch (\Exception $e) {
            // Continue even if email fails
        }

        return redirect()->route('projects.show', $project)
            ->with('success', 'Invoice generated and sent to customer for ' . $nextInstallment['type'] . ' installment.');
    }

    /**
     * Mark installment as paid.
     */
    public function markInstallmentPaid(Request $request, Project $project)
    {
        $validated = $request->validate([
            'installment_type' => 'required|in:Upfront,First,Second,Third',
            'transaction_id' => 'required|string|max:255',
        ]);

        $installmentType = strtolower($validated['installment_type']);
        
        // Update project installment status
        switch ($validated['installment_type']) {
            case 'Upfront':
                $project->update(['upfront_paid' => true, 'current_installment' => 1]);
                break;
            case 'First':
                $project->update(['first_installment_paid' => true, 'current_installment' => 2]);
                break;
            case 'Second':
                $project->update(['second_installment_paid' => true, 'current_installment' => 3]);
                break;
            case 'Third':
                $project->update(['third_installment_paid' => true, 'current_installment' => 4]);
                break;
        }

        // Update installment record
        $installment = ProjectInstallment::where('project_id', $project->id)
            ->where('installment_type', $validated['installment_type'])
            ->first();
        
        if ($installment) {
            $installment->update([
                'paid' => true,
                'paid_at' => Carbon::now(),
                'transaction_id' => $validated['transaction_id'],
            ]);

            // Update invoice status
            if ($installment->invoice_id) {
                Invoice::find($installment->invoice_id)->update(['payment_status' => 'paid']);
            }
        }

        // Check if all installments are paid
        if ($project->areAllInstallmentsPaid()) {
            $project->update(['project_status' => 'Completed']);
            
            return redirect()->route('projects.maintenance-contract.create', $project)
                ->with('success', 'Final payment received. Project completed! Please create maintenance contract.');
        }

        return redirect()->route('projects.show', $project)
            ->with('success', 'Installment marked as paid successfully.');
    }

    /**
     * Show form to create maintenance contract.
     */
    public function createMaintenanceContract(Project $project)
    {
        if ($project->project_status !== 'Completed') {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Project must be completed before creating maintenance contract.');
        }

        if ($project->maintenanceContract) {
            return redirect()->route('projects.show', $project)
                ->with('error', 'Maintenance contract already exists for this project.');
        }

        return view('projects.maintenance-contract', compact('project'));
    }

    /**
     * Store maintenance contract.
     */
    public function storeMaintenanceContract(Request $request, Project $project)
    {
        $validated = $request->validate([
            'contract_type' => 'required|in:Free,Chargeable',
            'free_months' => 'required_if:contract_type,Free|nullable|integer|min:1',
            'charges' => 'required_if:contract_type,Chargeable|nullable|numeric|min:0',
            'charge_frequency' => 'required_if:contract_type,Chargeable|nullable|in:Monthly,Quarterly,Annually',
            'domain_purchase_date' => 'nullable|date',
            'domain_amount' => 'nullable|numeric|min:0',
            'domain_renewal_date' => 'nullable|date|after:domain_purchase_date',
            'hosting_purchase_date' => 'nullable|date',
            'hosting_amount' => 'nullable|numeric|min:0',
            'hosting_renewal_date' => 'nullable|date|after:hosting_purchase_date',
        ]);

        $contractStartDate = Carbon::now();
        $contractEndDate = null;

        if ($validated['contract_type'] === 'Free') {
            $contractEndDate = $contractStartDate->copy()->addMonths($validated['free_months']);
        }

        $maintenanceContract = MaintenanceContract::create([
            'project_id' => $project->id,
            'customer_id' => $project->customer_id,
            'contract_type' => $validated['contract_type'],
            'free_months' => $validated['free_months'] ?? null,
            'charges' => $validated['charges'] ?? null,
            'charge_frequency' => $validated['charge_frequency'] ?? null,
            'domain_purchase_date' => $validated['domain_purchase_date'] ?? null,
            'domain_amount' => $validated['domain_amount'] ?? null,
            'domain_renewal_date' => $validated['domain_renewal_date'] ?? null,
            'hosting_purchase_date' => $validated['hosting_purchase_date'] ?? null,
            'hosting_amount' => $validated['hosting_amount'] ?? null,
            'hosting_renewal_date' => $validated['hosting_renewal_date'] ?? null,
            'contract_start_date' => $contractStartDate,
            'contract_end_date' => $contractEndDate,
            'status' => 'Active',
        ]);

        // Create invoice for chargeable maintenance
        if ($validated['contract_type'] === 'Chargeable') {
            $invoice = Invoice::create([
                'customer_id' => $project->customer_id,
                'invoice_number' => Invoice::generateInvoiceNumber('tax_invoice'),
                'invoice_type' => 'tax_invoice',
                'invoice_date' => Carbon::now(),
                'due_date' => Carbon::now()->addDays(15),
                'payment_status' => 'unpaid',
                'subtotal' => $validated['charges'],
                'tax_total' => 0,
                'grand_total' => $validated['charges'],
                'notes' => "Maintenance Contract: {$project->project_name}",
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_description' => "{$project->project_name} - {$validated['charge_frequency']} Maintenance",
                'quantity' => 1,
                'rate' => $validated['charges'],
                'total_amount' => $validated['charges'],
            ]);

            $maintenanceContract->update(['invoice_id' => $invoice->id]);

            // Send email to admin and customer
            try {
                Mail::to($project->customer->email)
                    ->send(new MaintenanceContractMail($maintenanceContract, $invoice));
            } catch (\Exception $e) {
                // Continue even if email fails
            }
        }

        return redirect()->route('projects.show', $project)
            ->with('success', 'Maintenance contract created successfully.');
    }
}
