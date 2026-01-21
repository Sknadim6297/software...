<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BDMLeaveApplication;
use App\Models\BDM;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminLeaveController extends Controller
{
    /**
     * View all BDM leave applications
     */
    public function index(Request $request)
    {
        $query = BDMLeaveApplication::with('bdm.user');

        if ($request->status && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->bdm_id) {
            $query->where('bdm_id', $request->bdm_id);
        }

        if ($request->leave_type) {
            $query->where('leave_type', $request->leave_type);
        }

        $leaves = $query->orderBy('leave_date', 'desc')->paginate(20);
        $bdms = BDM::with('user')->get();

        return view('admin.leaves.index', [
            'leaves' => $leaves,
            'bdms' => $bdms,
        ]);
    }

    /**
     * Show leave details
     */
    public function show(BDMLeaveApplication $leave)
    {
        $leave->load('bdm.user');
        return view('admin.leaves.show', ['leave' => $leave]);
    }

    /**
     * Approve BDM leave request
     */
    public function approve(Request $request, BDMLeaveApplication $leave)
    {
        $request->validate([
            'admin_remarks' => 'nullable|string|max:500',
        ]);

        if ($leave->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending requests can be approved']);
        }

        $leave->update([
            'status' => 'approved',
            'admin_remarks' => $request->admin_remarks,
            'admin_action_at' => now(),
        ]);

        // Update leave balance
        $bdm = $leave->bdm;
        $leaveBalance = $bdm->leaveBalance;
        
        if ($leaveBalance && in_array($leave->leave_type, ['casual', 'sick'])) {
            if ($leave->leave_type === 'casual') {
                $leaveBalance->decrement('casual_leave_balance');
                $leaveBalance->increment('casual_leave_used_this_month');
            } else {
                $leaveBalance->decrement('sick_leave_balance');
                $leaveBalance->increment('sick_leave_used_this_month');
            }
        }

        // Mark attendance as leave for that date
        Attendance::updateOrCreate(
            [
                'user_id' => $bdm->user_id,
                'attendance_date' => $leave->leave_date,
            ],
            [
                'status' => 'leave',
            ]
        );

        return back()->with('success', 'Leave request approved successfully');
    }

    /**
     * Reject BDM leave request
     */
    public function reject(Request $request, BDMLeaveApplication $leave)
    {
        $request->validate([
            'admin_remarks' => 'required|string|max:500',
        ]);

        if ($leave->status !== 'pending') {
            return back()->withErrors(['error' => 'Only pending requests can be rejected']);
        }

        $leave->update([
            'status' => 'rejected',
            'admin_remarks' => $request->admin_remarks,
            'admin_action_at' => now(),
        ]);

        return back()->with('success', 'Leave request rejected successfully');
    }

    /**
     * View leave history for BDM
     */
    public function employeeHistory(BDM $bdm, Request $request)
    {
        $year = $request->get('year', now()->year);

        $leaves = BDMLeaveApplication::where('bdm_id', $bdm->id)
            ->whereYear('leave_date', $year)
            ->orderBy('leave_date', 'desc')
            ->paginate(20);

        // Summary for the year
        $summary = BDMLeaveApplication::where('bdm_id', $bdm->id)
            ->where('status', 'approved')
            ->whereYear('leave_date', $year)
            ->selectRaw('
                COUNT(*) as total_approved,
                SUM(CASE WHEN leave_type = "casual" THEN 1 ELSE 0 END) as casual_leaves,
                SUM(CASE WHEN leave_type = "sick" THEN 1 ELSE 0 END) as sick_leaves,
                SUM(CASE WHEN leave_type = "unpaid" THEN 1 ELSE 0 END) as unpaid_leaves
            ')
            ->first();

        return view('admin.leaves.employee-history', [
            'bdm' => $bdm,
            'leaves' => $leaves,
            'summary' => $summary,
            'year' => $year,
        ]);
    }

    /**
     * Leave report
     */
    public function report(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $report = BDMLeaveApplication::where('status', 'approved')
            ->whereYear('leave_date', $year)
            ->whereMonth('leave_date', $month)
            ->with('bdm.user')
            ->get()
            ->groupBy('bdm_id');

        return view('admin.leaves.report', [
            'report' => $report,
            'year' => $year,
            'month' => $month,
        ]);
    }

    /**
     * Monthly summary
     */
    public function monthlySummary(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $summary = BDMLeaveApplication::where('status', 'approved')
            ->whereYear('leave_date', $year)
            ->whereMonth('leave_date', $month)
            ->selectRaw('
                leave_type,
                COUNT(*) as total,
                bdm_id
            ')
            ->groupBy('leave_type', 'bdm_id')
            ->with('bdm.user')
            ->get();

        return view('admin.leaves.summary', [
            'summary' => $summary,
            'year' => $year,
            'month' => $month,
        ]);
    }

    /**
     * View leave balances for all BDMs
     */
    public function balances()
    {
        $bdms = BDM::with(['user', 'leaveBalance'])->get();
        
        return view('admin.leaves.balances', [
            'bdms' => $bdms,
        ]);
    }
}
