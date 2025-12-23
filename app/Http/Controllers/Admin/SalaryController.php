<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BDM;
use App\Models\BDMSalary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SalaryController extends Controller
{
    public function index()
    {
        $salaries = BDMSalary::with('bdm')
            ->latest('salary_month')
            ->paginate(20);
        
        return view('admin.salaries.index', compact('salaries'));
    }

    public function create()
    {
        $bdms = BDM::where('status', 'active')->get();
        return view('admin.salaries.create', compact('bdms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bdm_id' => 'required|exists:bdms,id',
            'month_year' => 'required|string',
            'basic_salary' => 'required|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'salary_slip' => 'nullable|file|mimes:pdf|max:2048',
            'remarks' => 'nullable|string',
        ]);

        $gross = ($request->basic_salary ?? 0) + ($request->hra ?? 0) + ($request->other_allowances ?? 0);
        
        $salarySlipPath = null;
        if ($request->hasFile('salary_slip')) {
            $salarySlipPath = $request->file('salary_slip')->store('bdm/salary_slips', 'public');
        }

        BDMSalary::create([
            'bdm_id' => $request->bdm_id,
            'month_year' => $request->month_year,
            'basic_salary' => $request->basic_salary,
            'hra' => $request->hra ?? 0,
            'other_allowances' => $request->other_allowances ?? 0,
            'gross_salary' => $gross,
            'deductions' => $request->deductions ?? 0,
            'net_salary' => $request->net_salary,
            'salary_slip_path' => $salarySlipPath,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('admin.salaries.index')
            ->with('success', 'Salary record created successfully!');
    }

    public function show(BDMSalary $salary)
    {
        $salary->load('bdm');
        return view('admin.salaries.show', compact('salary'));
    }

    public function edit(BDMSalary $salary)
    {
        $bdms = BDM::where('status', 'active')->get();
        return view('admin.salaries.edit', compact('salary', 'bdms'));
    }

    public function update(Request $request, BDMSalary $salary)
    {
        $request->validate([
            'month_year' => 'required|string',
            'basic_salary' => 'required|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'other_allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'salary_slip' => 'nullable|file|mimes:pdf|max:2048',
            'remarks' => 'nullable|string',
        ]);

        if ($request->hasFile('salary_slip')) {
            // Delete old slip
            if ($salary->salary_slip_path) {
                Storage::disk('public')->delete($salary->salary_slip_path);
            }
            $salarySlipPath = $request->file('salary_slip')->store('bdm/salary_slips', 'public');
            $salary->salary_slip_path = $salarySlipPath;
        }

        $gross = ($request->basic_salary ?? 0) + ($request->hra ?? 0) + ($request->other_allowances ?? 0);

        $salary->update([
            'month_year' => $request->month_year,
            'basic_salary' => $request->basic_salary,
            'hra' => $request->hra ?? 0,
            'other_allowances' => $request->other_allowances ?? 0,
            'gross_salary' => $gross,
            'deductions' => $request->deductions ?? 0,
            'net_salary' => $request->net_salary,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('admin.salaries.show', $salary->id)
            ->with('success', 'Salary record updated successfully!');
    }

    public function destroy(BDMSalary $salary)
    {
        if ($salary->salary_slip_path) {
            Storage::disk('public')->delete($salary->salary_slip_path);
        }
        
        $salary->delete();

        return redirect()->route('admin.salaries.index')
            ->with('success', 'Salary record deleted successfully!');
    }

    public function download(BDMSalary $salary)
    {
        if (!$salary->salary_slip_path || !Storage::disk('public')->exists($salary->salary_slip_path)) {
            return back()->with('error', 'Salary slip not found!');
        }

        return Storage::disk('public')->download(
            $salary->salary_slip_path,
            'salary_slip_' . $salary->bdm->employee_code . '_' . str_replace('-', '_', $salary->month_year) . '.pdf'
        );
    }

    public function uploadSlip(Request $request, BDMSalary $salary)
    {
        $request->validate([
            'salary_slip' => 'required|file|mimes:pdf|max:2048',
        ]);

        if ($salary->salary_slip_path) {
            Storage::disk('public')->delete($salary->salary_slip_path);
        }

        $salarySlipPath = $request->file('salary_slip')->store('bdm/salary_slips', 'public');
        
        $salary->update([
            'salary_slip_path' => $salarySlipPath,
        ]);

        return back()->with('success', 'Salary slip uploaded successfully!');
    }
}
