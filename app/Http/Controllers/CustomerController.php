<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     */
    public function index()
    {
        $customers = Customer::latest()->paginate(15);
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'number' => 'required|string|max:20',
            'alternate_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gst_number' => 'nullable|string|max:20',
            'state_code' => 'nullable|string|max:10',
            'state_name' => 'nullable|string|max:100',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active');

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'number' => 'required|string|max:20',
            'alternate_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gst_number' => 'nullable|string|max:20',
            'state_code' => 'nullable|string|max:10',
            'state_name' => 'nullable|string|max:100',
            'active' => 'boolean',
        ]);

        $validated['active'] = $request->has('active');

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
