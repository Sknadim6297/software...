<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectInstallment;
use App\Models\MaintenanceContract;
use App\Models\Customer;
use App\Models\User;
use App\Models\BDM;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Contract;
use App\Mail\PaymentRequestMail;
use App\Mail\MaintenanceContractMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index(Request $request)
    {
        $baseQuery = Project::with(['customer', 'coordinator', 'bdm']);

        // Apply filters (status)
        $applyStatusFilter = function ($query) use ($request) {
            if ($request->has('status')) {
                if ($request->status === 'in-progress') {
                    $query->inProgress();
                } elseif ($request->status === 'completed') {
                    $query->completed();
                }
            }
        };

        // Build filtered query with BDM scope
        $filteredQuery = clone $baseQuery;
        $applyStatusFilter($filteredQuery);

        // Filter by BDM (if not admin)
        $bdmFiltered = false;
        if (Auth::user()->bdm) {
            $filteredQuery->where('bdm_id', Auth::user()->bdm->id);
            $bdmFiltered = true;
        }
        $projects = $filteredQuery->latest()->paginate(15);

        // If the BDM has no projects, fall back to showing all projects as demo data
        $demoFallback = false;
        if ($bdmFiltered && $projects->isEmpty()) {
            $fallbackQuery = clone $baseQuery;
            $applyStatusFilter($fallbackQuery);
            $projects = $fallbackQuery->latest()->paginate(15);
            $demoFallback = true;
        }

        return view('projects.index', compact('projects', 'demoFallback'));
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
            'notes' => 'nullable|string'
        ]);

        // Get customer data
        $customer = Customer::find($validated['customer_id']);
        $validated['customer_name'] = $customer->customer_name;
        $validated['customer_mobile'] = $customer->number;
        $validated['customer_email'] = $customer->email;

        // Get coordinator name
        $coordinator = User::find($validated['project_coordinator_id']);
        $validated['project_coordinator'] = $coordinator->name;

        // Add BDM ID
        if (Auth::user()->bdm) {
            $validated['bdm_id'] = Auth::user()->bdm->id;
        }

        $validated['status'] = 'In Progress';
        $validated['project_status'] = 'In Progress';

        $project = Project::create($validated);

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Project created successfully!');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        $project->load(['customer', 'coordinator', 'bdm', 'invoices', 'maintenanceContract']);
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
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required|string|max:255',
            'customer_mobile' => 'required|string|max:20',
            'customer_email' => 'nullable|email',
            'project_name' => 'required|string|max:255',
            'project_type' => 'required|in:Website,Software,Application',
            'start_date' => 'required|date',
            'project_valuation' => 'required|numeric|min:0',
            'upfront_payment' => 'nullable|numeric|min:0',
            'first_installment' => 'nullable|numeric|min:0',
            'second_installment' => 'nullable|numeric|min:0',
            'third_installment' => 'nullable|numeric|min:0',
            'project_coordinator' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $validated['project_start_date'] = $validated['start_date'];

        $project->update($validated);

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Project updated successfully!');
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
        $nextInstallment = $project->next_pending_installment;
        
        if (!$nextInstallment) {
            return redirect()->back()
                ->with('error', 'All installments have been paid.');
        }

        return view('projects.take-payment', compact('project', 'nextInstallment'));
    }

    /**
     * Process Payment
     */
    public function processPayment(Request $request, Project $project)
    {
        $validated = $request->validate([
            'installment_type' => 'required|in:upfront,first,second,third',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'transaction_reference' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $installmentType = $validated['installment_type'];
            $paymentDate = $validated['payment_date'];

            // Mark installment as paid
            $project->{$installmentType . '_paid'} = true;
            $project->{$installmentType . '_paid_date'} = $paymentDate;

            // Get installment amount
            $amount = $project->{$installmentType . '_payment'};
            if ($installmentType != 'upfront') {
                $amount = $project->{$installmentType . '_installment'};
            }

            // Generate Invoice
            $invoice = Invoice::create([
                'customer_id' => $project->customer_id,
                'project_id' => $project->id,
                'invoice_number' => 'INV-' . strtoupper(uniqid()),
                'invoice_date' => $paymentDate,
                'due_date' => $paymentDate,
                'subtotal' => $amount,
                'tax' => $amount * 0.18, // 18% GST
                'total_amount' => $amount * 1.18,
                'status' => 'paid',
                'payment_status' => 'Paid',
                'paid_date' => $paymentDate,
                'payment_method' => $validated['payment_method'] ?? 'Cash',
                'notes' => ucfirst($installmentType) . ' payment for ' . $project->project_name
            ]);

            // Store invoice ID in project
            $invoices = $project->payment_invoices ?? [];
            $invoices[$installmentType] = $invoice->id;
            $project->payment_invoices = $invoices;

            // Check if all payments completed
            if ($project->is_fully_paid) {
                $project->status = 'Completed';
                $project->project_status = 'Completed';
            }

            $project->save();

            DB::commit();

            // If fully paid, redirect to completion form
            if ($project->is_fully_paid) {
                return redirect()->route('projects.complete', $project->id)
                    ->with('success', 'Final payment received! Please complete project details.');
            }

            return redirect()->route('projects.show', $project->id)
                ->with('success', 'Payment processed successfully! Invoice #' . $invoice->invoice_number . ' generated.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    /**
     * Show completion form (for domain, hosting, and maintenance)
     */
    public function complete(Project $project)
    {
        if (!$project->is_fully_paid) {
            return redirect()->route('projects.show', $project->id)
                ->with('error', 'Project must be fully paid before completion.');
        }

        return view('projects.complete', compact('project'));
    }

    /**
     * Store completion details and create maintenance contract
     */
    public function storeCompletion(Request $request, Project $project)
    {
        $validated = $request->validate([
            'domain_name' => 'nullable|string|max:255',
            'domain_purchase_date' => 'nullable|date',
            'domain_amount' => 'nullable|numeric|min:0',
            'domain_renewal_cycle' => 'nullable|in:Monthly,Yearly',
            'domain_renewal_date' => 'nullable|date',
            'hosting_provider' => 'nullable|string|max:255',
            'hosting_purchase_date' => 'nullable|date',
            'hosting_amount' => 'nullable|numeric|min:0',
            'hosting_renewal_cycle' => 'nullable|in:Monthly,Yearly',
            'hosting_renewal_date' => 'nullable|date',
            'maintenance_enabled' => 'required|boolean',
            'maintenance_type' => 'required_if:maintenance_enabled,1|in:Free,Chargeable',
            'maintenance_months' => 'required_if:maintenance_type,Free|nullable|integer|min:1',
            'maintenance_charge' => 'required_if:maintenance_type,Chargeable|nullable|numeric|min:0',
            'maintenance_billing_cycle' => 'required_if:maintenance_type,Chargeable|nullable|in:Monthly,Quarterly,Annually'
        ]);

        DB::beginTransaction();
        try {
            // Update project with domain/hosting details
            $project->update($validated);
            $project->status = 'Completed';
            $project->project_status = 'Completed';
            $project->save();

            // Create Maintenance Contract if enabled
            if ($validated['maintenance_enabled']) {
                $this->createMaintenanceContract($project, $validated);
            }

            DB::commit();

            return redirect()->route('projects.show', $project->id)
                ->with('success', 'Project completed successfully! Maintenance contract has been created.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Completion failed: ' . $e->getMessage());
        }
    }

    /**
     * Create Maintenance Contract
     */
    private function createMaintenanceContract(Project $project, array $data)
    {
        $startDate = Carbon::now();
        $endDate = null;

        if ($data['maintenance_type'] === 'Free') {
            $endDate = $startDate->copy()->addMonths($data['maintenance_months']);
        }

        // Create Contract
        $contract = Contract::create([
            'customer_id' => $project->customer_id,
            'project_id' => $project->id,
            'contract_type' => 'Maintenance',
            'contract_number' => 'MC-' . strtoupper(uniqid()),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_amount' => $data['maintenance_type'] === 'Free' ? 0 : $data['maintenance_charge'],
            'payment_terms' => $data['maintenance_type'] === 'Free' 
                ? 'Free maintenance for ' . $data['maintenance_months'] . ' months'
                : 'Chargeable - ' . $data['maintenance_billing_cycle'],
            'status' => 'active',
            'terms_and_conditions' => 'Standard maintenance terms apply.'
        ]);

        // Update project with contract ID
        $project->maintenance_contract_id = $contract->id;
        $project->maintenance_start_date = $startDate;
        $project->save();

        // If chargeable, create invoice
        if ($data['maintenance_type'] === 'Chargeable') {
            $amount = $data['maintenance_charge'];
            
            Invoice::create([
                'customer_id' => $project->customer_id,
                'project_id' => $project->id,
                'contract_id' => $contract->id,
                'invoice_number' => 'INV-MC-' . strtoupper(uniqid()),
                'invoice_date' => Carbon::now(),
                'due_date' => Carbon::now()->addDays(30),
                'subtotal' => $amount,
                'tax' => $amount * 0.18,
                'total_amount' => $amount * 1.18,
                'status' => 'pending',
                'payment_status' => 'Pending',
                'notes' => 'Maintenance contract - ' . $data['maintenance_billing_cycle'] . ' billing'
            ]);
        }

        return $contract;
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
}
