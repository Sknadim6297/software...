<?php

namespace App\Http\Controllers;

use App\Models\BDM;
use App\Models\BDMLeaveApplication;
use App\Models\BDMLeaveBalance;
use App\Models\BDMSalary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BDMLeaveSalaryController extends Controller
{
    /**
     * Show Leave & Salary Slip dashboard
     */
    public function index()
    {
        $bdm = Auth::user()->bdm;
        if (!$bdm) {
            return redirect()->route('login')->with('error', 'BDM profile not found.');
        }

        // Get leave balance
        $leaveBalance = $bdm->leaveBalance ?? new BDMLeaveBalance();

        // Get salary slips for the last 12 months
        $salarySlips = $bdm->salaries()
            ->orderBy('month_year', 'desc')
            ->paginate(12);

        // Get pending leave applications
        $pendingLeaves = $bdm->leaveApplications()
            ->where('status', 'pending')
            ->orderBy('from_date', 'desc')
            ->get();

        // Get approved leaves for current month
        $currentMonth = Carbon::now()->format('Y-m');
        $approvedLeavesThisMonth = $bdm->leaveApplications()
            ->where('status', 'approved')
            ->whereYear('from_date', Carbon::now()->year)
            ->whereMonth('from_date', Carbon::now()->month)
            ->get();

        return view('bdm.leave-salary.index', compact(
            'bdm',
            'leaveBalance',
            'salarySlips',
            'pendingLeaves',
            'approvedLeavesThisMonth'
        ));
    }

    /**
     * Show apply leave form
     */
    public function applyLeaveForm()
    {
        $bdm = Auth::user()->bdm;
        if (!$bdm) {
            return redirect()->route('login')->with('error', 'BDM profile not found.');
        }

        $leaveBalance = $bdm->leaveBalance ?? new BDMLeaveBalance();

        return view('bdm.leave-salary.apply-leave', compact('bdm', 'leaveBalance'));
    }

    /**
     * Store leave application
     */
    public function applyLeave(Request $request)
    {
        $validated = $request->validate([
            'leave_type' => 'required|in:casual,sick,unpaid',
            'from_date' => 'required|date|after_or_equal:tomorrow',
            'to_date' => 'required|date|after_or_equal:from_date',
            'reason' => 'required|string|min:10|max:500',
        ], [
            'reason.min' => 'Reason must be at least 10 characters.',
            'from_date.after_or_equal' => 'Leave date must be from tomorrow onwards.',
        ]);

        $bdm = Auth::user()->bdm;

        // Calculate number of days
        $fromDate = Carbon::parse($validated['from_date']);
        $toDate = Carbon::parse($validated['to_date']);
        $numberOfDays = $fromDate->diffInDays($toDate) + 1;

        // Check if leave already exists for these dates
        $existingLeave = $bdm->leaveApplications()
            ->where('status', 'approved')
            ->where(function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('from_date', [$fromDate, $toDate])
                    ->orWhereBetween('to_date', [$fromDate, $toDate])
                    ->orWhere(function ($q) use ($fromDate, $toDate) {
                        $q->where('from_date', '<=', $fromDate)
                            ->where('to_date', '>=', $toDate);
                    });
            })
            ->exists();

        if ($existingLeave) {
            return back()->with('error', 'Leave already approved for selected dates.');
        }

        // Validate leave balance for casual and sick leaves
        $leaveBalance = $bdm->leaveBalance;
        if ($validated['leave_type'] === 'casual' && $leaveBalance->casual_leave_balance < $numberOfDays) {
            return back()->with('error', 'Insufficient casual leave balance.');
        }
        if ($validated['leave_type'] === 'sick' && $leaveBalance->sick_leave_balance < $numberOfDays) {
            return back()->with('error', 'Insufficient sick leave balance.');
        }

        // Create leave application
        $leaveApplication = $bdm->leaveApplications()->create([
            'leave_type' => $validated['leave_type'],
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'number_of_days' => $numberOfDays,
            'reason' => $validated['reason'],
            'status' => 'pending',
            'applied_at' => now(),
            'is_editable' => true,
        ]);

        return redirect()->route('bdm.leave-salary.index')
            ->with('success', 'Leave application submitted successfully. Awaiting admin approval.');
    }

    /**
     * View leave application history
     */
    public function leaveHistory()
    {
        $bdm = Auth::user()->bdm;
        if (!$bdm) {
            return redirect()->route('login')->with('error', 'BDM profile not found.');
        }

        $leaves = $bdm->leaveApplications()
            ->orderBy('from_date', 'desc')
            ->paginate(10);

        return view('bdm.leave-salary.leave-history', compact('bdm', 'leaves'));
    }

    /**
     * Download salary slip PDF
     */
    public function downloadSalarySlip(BDMSalary $salary)
    {
        $bdm = Auth::user()->bdm;

        // Check if salary belongs to current BDM
        if ($salary->bdm_id !== $bdm->id) {
            return back()->with('error', 'Unauthorized access.');
        }

        // If PDF doesn't exist, generate it
        if (!$salary->salary_slip_path || !\Storage::disk('public')->exists($salary->salary_slip_path)) {
            $this->generateSalarySlipPDF($salary);
        }

        $filePath = storage_path('app/public/' . $salary->salary_slip_path);

        if (file_exists($filePath)) {
            return response()->download($filePath, 'Salary_Slip_' . $salary->formatted_month . '.pdf');
        }

        return back()->with('error', 'Salary slip file not found.');
    }

    /**
     * Generate salary slip PDF
     */
    private function generateSalarySlipPDF(BDMSalary $salary)
    {
        // This will be implemented with a PDF generation library (dompdf/barryvdh)
        // For now, return a placeholder
        // Future implementation: Generate PDF and save to storage
    }

    /**
     * Get salary slip details
     */
    public function salarySlipDetails(BDMSalary $salary)
    {
        $bdm = Auth::user()->bdm;

        if ($salary->bdm_id !== $bdm->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($salary->getSalarySummary());
    }
}
