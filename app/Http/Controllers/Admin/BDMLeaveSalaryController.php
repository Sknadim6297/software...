<?php

namespace App\Http\Controllers\Admin;

use App\Models\BDM;
use App\Models\BDMLeaveApplication;
use App\Models\BDMLeaveBalance;
use App\Models\BDMSalary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BDMLeaveSalaryController extends Controller
{
    /**
     * View all leave requests
     */
    public function index(Request $request)
    {
        $query = BDMLeaveApplication::with('bdm');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by BDM
        if ($request->filled('bdm_id')) {
            $query->where('bdm_id', $request->bdm_id);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('from_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('to_date', '<=', $request->to_date);
        }

        $leaves = $query->orderBy('from_date', 'desc')->paginate(15);
        $bdms = BDM::where('can_login', true)->get();
        $statuses = ['pending', 'approved', 'rejected'];

        return view('admin.leave-salary.index', compact('leaves', 'bdms', 'statuses'));
    }

    /**
     * View single leave request
     */
    public function show(BDMLeaveApplication $leave)
    {
        return view('admin.leave-salary.show', compact('leave'));
    }

    /**
     * Approve leave request
     */
    public function approve(Request $request, BDMLeaveApplication $leave)
    {
        if (!$leave->isPending()) {
            return back()->with('error', 'Only pending leaves can be approved.');
        }

        $validated = $request->validate([
            'remarks' => 'nullable|string|max:500',
        ]);

        // Get leave balance
        $leaveBalance = $leave->bdm->leaveBalance;
        if (!$leaveBalance) {
            return back()->with('error', 'Leave balance not found for this BDM.');
        }

        // Check balance
        if ($leave->leave_type !== 'unpaid') {
            $remainingDays = $leave->leave_type === 'casual'
                ? $leaveBalance->casual_leave_balance
                : $leaveBalance->sick_leave_balance;

            if ($remainingDays < $leave->number_of_days) {
                return back()->with('error', "Insufficient {$leave->leave_type} leave balance. Required: {$leave->number_of_days}, Available: {$remainingDays}");
            }
        }

        // Approve leave
        $leave->approve($validated['remarks'] ?? null);

        // Update leave balance
        if ($leave->leave_type !== 'unpaid') {
            $leaveBalance->updateAfterApprovedLeave($leave->leave_type, $leave->number_of_days);
        }

        return back()->with('success', 'Leave approved successfully.');
    }

    /**
     * Reject leave request
     */
    public function reject(Request $request, BDMLeaveApplication $leave)
    {
        if (!$leave->isPending()) {
            return back()->with('error', 'Only pending leaves can be rejected.');
        }

        $validated = $request->validate([
            'remarks' => 'required|string|min:5|max:500',
        ], [
            'remarks.required' => 'Remarks are required when rejecting a leave.',
        ]);

        // Reject leave
        $leave->reject($validated['remarks']);

        return back()->with('success', 'Leave rejected successfully.');
    }

    /**
     * View all salary slips
     */
    public function salaryIndex(Request $request)
    {
        $query = BDMSalary::with('bdm');

        if ($request->filled('bdm_id')) {
            $query->where('bdm_id', $request->bdm_id);
        }

        if ($request->filled('month_year')) {
            $query->where('month_year', $request->month_year);
        }

        $salaries = $query->orderBy('month_year', 'desc')->paginate(20);
        $bdms = BDM::where('can_login', true)->get();

        return view('admin.leave-salary.salary-index', compact('salaries', 'bdms'));
    }

    /**
     * View salary slip details
     */
    public function salaryShow(BDMSalary $salary)
    {
        return view('admin.leave-salary.salary-show', compact('salary'));
    }

    /**
     * Regenerate salary slip
     */
    public function regenerateSalary(Request $request, BDMSalary $salary)
    {
        $validated = $request->validate([
            'total_present_days' => 'required|integer|min:0|max:31',
            'casual_leave_taken' => 'required|integer|min:0',
            'sick_leave_taken' => 'required|integer|min:0',
            'unpaid_leave_taken' => 'required|integer|min:0',
            'remarks' => 'nullable|string|max:500',
        ]);

        // Recalculate salary
        $salary->update([
            'total_present_days' => $validated['total_present_days'],
            'casual_leave_taken' => $validated['casual_leave_taken'],
            'sick_leave_taken' => $validated['sick_leave_taken'],
            'unpaid_leave_taken' => $validated['unpaid_leave_taken'],
            'leave_deduction' => $salary->calculateLeaveDeduction(),
            'net_salary' => $salary->gross_salary - $salary->deductions - ($salary->per_day_salary * ($validated['casual_leave_taken'] + $validated['sick_leave_taken'] + $validated['unpaid_leave_taken'])),
            'remarks' => $validated['remarks'] ?? $salary->remarks,
        ]);

        $salary->regenerate(auth()->user()->email);

        return back()->with('success', 'Salary slip regenerated successfully.');
    }

    /**
     * Set leave allocation for BDM
     */
    public function setLeaveAllocation(Request $request, BDM $bdm)
    {
        $validated = $request->validate([
            'casual_leaves' => 'required|integer|min:0|max:30',
            'sick_leaves' => 'required|integer|min:0|max:30',
        ]);

        $leaveBalance = $bdm->leaveBalance ?? BDMLeaveBalance::create(['bdm_id' => $bdm->id]);
        $leaveBalance->setAllocation($validated['casual_leaves'], $validated['sick_leaves']);

        return back()->with('success', 'Leave allocation updated successfully.');
    }

    /**
     * View leave balance sheet
     */
    public function leaveBalances()
    {
        $bdms = BDM::with('leaveBalance')
            ->where('can_login', true)
            ->get();

        return view('admin.leave-salary.balances', compact('bdms'));
    }

    /**
     * View monthly leave report
     */
    public function monthlyLeaveReport(Request $request)
    {
        $month = $request->month ?? Carbon::now()->format('Y-m');

        $leaves = BDMLeaveApplication::whereYear('from_date', Carbon::parse($month)->year)
            ->whereMonth('from_date', Carbon::parse($month)->month)
            ->where('status', 'approved')
            ->with('bdm')
            ->orderBy('from_date')
            ->get()
            ->groupBy(function ($leave) {
                return $leave->bdm->name;
            });

        $summary = [
            'total_leaves' => BDMLeaveApplication::whereYear('from_date', Carbon::parse($month)->year)
                ->whereMonth('from_date', Carbon::parse($month)->month)
                ->where('status', 'approved')
                ->sum('number_of_days'),
            'casual_leaves' => BDMLeaveApplication::whereYear('from_date', Carbon::parse($month)->year)
                ->whereMonth('from_date', Carbon::parse($month)->month)
                ->where('leave_type', 'casual')
                ->where('status', 'approved')
                ->sum('number_of_days'),
            'sick_leaves' => BDMLeaveApplication::whereYear('from_date', Carbon::parse($month)->year)
                ->whereMonth('from_date', Carbon::parse($month)->month)
                ->where('leave_type', 'sick')
                ->where('status', 'approved')
                ->sum('number_of_days'),
        ];

        return view('admin.leave-salary.monthly-report', compact('leaves', 'summary', 'month'));
    }

    /**
     * Generate monthly salaries for all BDMs
     */
    public function generateMonthlySalaries(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
        ]);

        $monthYear = sprintf('%04d-%02d', $validated['year'], $validated['month']);

        // Check if salaries already exist
        $existingCount = BDMSalary::where('month_year', $monthYear)->count();
        if ($existingCount > 0) {
            return back()->with('error', "Salaries for {$monthYear} already exist. Delete existing records first.");
        }

        // Get all active BDMs
        $bdms = BDM::where('can_login', true)->get();
        $generated = 0;

        foreach ($bdms as $bdm) {
            // Get approved leaves for this month
            $casualLeaves = BDMLeaveApplication::where('bdm_id', $bdm->id)
                ->where('status', 'approved')
                ->where('leave_type', 'casual')
                ->whereYear('from_date', $validated['year'])
                ->whereMonth('from_date', $validated['month'])
                ->sum('number_of_days');

            $sickLeaves = BDMLeaveApplication::where('bdm_id', $bdm->id)
                ->where('status', 'approved')
                ->where('leave_type', 'sick')
                ->whereYear('from_date', $validated['year'])
                ->whereMonth('from_date', $validated['month'])
                ->sum('number_of_days');

            $unpaidLeaves = BDMLeaveApplication::where('bdm_id', $bdm->id)
                ->where('status', 'approved')
                ->where('leave_type', 'unpaid')
                ->whereYear('from_date', $validated['year'])
                ->whereMonth('from_date', $validated['month'])
                ->sum('number_of_days');

            // Calculate present days (assuming 30 days per month)
            $totalDays = Carbon::createFromDate($validated['year'], $validated['month'], 1)->daysInMonth;
            $totalLeaves = $casualLeaves + $sickLeaves + $unpaidLeaves;
            $presentDays = $totalDays - $totalLeaves;

            // Calculate salary components
            $basicSalary = $bdm->salary ?? 25000; // Default basic salary
            $hra = $basicSalary * 0.40; // 40% HRA
            $otherAllowances = $basicSalary * 0.10; // 10% Other allowances
            $grossSalary = $basicSalary + $hra + $otherAllowances;
            $perDaySalary = $grossSalary / 30;

            // Calculate leave deduction (only unpaid leaves)
            $leaveDeduction = $unpaidLeaves * $perDaySalary;
            
            // Other deductions (can be customized)
            $otherDeductions = 0;
            
            // Net salary
            $netSalary = $grossSalary - $leaveDeduction - $otherDeductions;

            // Create salary record
            BDMSalary::create([
                'bdm_id' => $bdm->id,
                'month_year' => $monthYear,
                'basic_salary' => $basicSalary,
                'hra' => $hra,
                'other_allowances' => $otherAllowances,
                'gross_salary' => $grossSalary,
                'per_day_salary' => $perDaySalary,
                'total_present_days' => $presentDays,
                'casual_leave_taken' => $casualLeaves,
                'sick_leave_taken' => $sickLeaves,
                'unpaid_leave_taken' => $unpaidLeaves,
                'leave_deduction' => $leaveDeduction,
                'deductions' => $otherDeductions,
                'net_salary' => $netSalary,
                'is_editable' => true,
                'is_regenerated' => false,
            ]);

            $generated++;
        }

        return back()->with('success', "Successfully generated {$generated} salary slips for {$monthYear}.");
    }

    /**
     * Process month salaries (mark as finalized)
     */
    public function processMonthSalaries(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
        ]);

        $monthYear = sprintf('%04d-%02d', $validated['year'], $validated['month']);

        $updated = BDMSalary::where('month_year', $monthYear)
            ->update(['is_editable' => false]);

        return back()->with('success', "Processed {$updated} salary records for {$monthYear}. They are now finalized.");
    }

    /**
     * Export salary sheet
     */
    public function exportSalarySheet(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
            'format' => 'required|in:excel,pdf,csv',
        ]);

        $monthYear = sprintf('%04d-%02d', $validated['year'], $validated['month']);
        $salaries = BDMSalary::with('bdm')->where('month_year', $monthYear)->get();

        // TODO: Implement actual export logic based on format
        // For now, return success message
        return back()->with('success', "Export feature for {$validated['format']} format will be implemented soon.");
    }

    /**
     * Email payslips to BDMs
     */
    public function emailPayslips(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020',
        ]);

        $monthYear = sprintf('%04d-%02d', $validated['year'], $validated['month']);
        $salaries = BDMSalary::with('bdm')->where('month_year', $monthYear)->get();

        $sent = 0;
        foreach ($salaries as $salary) {
            if ($salary->bdm && $salary->bdm->email) {
                // TODO: Send email to BDM with salary slip PDF
                // Mail::to($salary->bdm->email)->send(new SalarySlipMail($salary));
                $sent++;
            }
        }

        return back()->with('success', "Salary slips will be sent to {$sent} BDMs for {$monthYear}.");
    }
}
