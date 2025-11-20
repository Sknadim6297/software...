<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Invoice;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    /**
     * Display a listing of contracts
     */
    public function index()
    {
        $contracts = Contract::with('proposal', 'invoices')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('contracts.index', compact('contracts'));
    }

    /**
     * Display the specified contract
     */
    public function show(Contract $contract)
    {
        $contract->load('proposal', 'invoices');
        return view('contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified contract
     */
    public function edit(Contract $contract)
    {
        return view('contracts.edit', compact('contract'));
    }

    /**
     * Update the specified contract
     */
    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'final_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'expected_completion_date' => 'required|date|after:start_date',
            'deliverables' => 'nullable|string',
            'milestones' => 'nullable|string',
            'payment_schedule' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
            'status' => 'required|in:pending_signature,active,completed,cancelled',
        ]);
        
        $contract->update($validated);
        
        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Contract updated successfully!');
    }

    /**
     * Cancel contract
     */
    public function cancel(Request $request, Contract $contract)
    {
        $contract->update(['status' => 'cancelled']);
        
        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Contract cancelled successfully.');
    }

    /**
     * Mark contract as completed
     */
    public function complete(Contract $contract)
    {
        $contract->update(['status' => 'completed']);
        
        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Contract marked as completed!');
    }
}
