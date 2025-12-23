<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BDM;
use App\Models\BDMLeaveApplication;
use App\Models\BDMLeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BDMLeaveStatusNotification;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $query = BDMLeaveApplication::with('bdm');

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by leave type
        if ($request->has('leave_type') && $request->leave_type != 'all') {
            $query->where('leave_type', $request->leave_type);
        }

        $leaves = $query->latest()->paginate(20);
        
        return view('admin.leaves.index', compact('leaves'));
    }

    public function show(BDMLeaveApplication $leave)
    {
        $leave->load('bdm');
        return view('admin.leaves.show', compact('leave'));
    }

    public function approve(Request $request, BDMLeaveApplication $leave)
    {
        if ($leave->status !== 'pending') {
            return back()->with('error', 'Only pending leaves can be approved!');
        }

        $leave->update([
            'status' => 'approved',
            'admin_remarks' => $request->remarks,
            'admin_action_at' => now(),
        ]);

        // Deduct leave balance
        $leaveBalance = $leave->bdm->leaveBalance;
        
        if ($leave->leave_type === 'casual') {
            $leaveBalance->decrement('casual_leave', 1);
        } elseif ($leave->leave_type === 'sick') {
            $leaveBalance->decrement('sick_leave', 1);
        } elseif ($leave->leave_type === 'unpaid') {
            $leaveBalance->increment('unpaid_leave', 1);
        }

        // Send email notification
        try {
            Mail::to($leave->bdm->email)->send(new BDMLeaveStatusNotification($leave));
        } catch (\Exception $e) {
            // Log error but don't fail the request
        }

        return back()->with('success', 'Leave approved successfully!');
    }

    public function reject(Request $request, BDMLeaveApplication $leave)
    {
        $request->validate([
            'remarks' => 'required|string',
        ]);

        if ($leave->status !== 'pending') {
            return back()->with('error', 'Only pending leaves can be rejected!');
        }

        $leave->update([
            'status' => 'rejected',
            'admin_remarks' => $request->remarks,
            'admin_action_at' => now(),
        ]);

        // Send email notification
        try {
            Mail::to($leave->bdm->email)->send(new BDMLeaveStatusNotification($leave));
        } catch (\Exception $e) {
            // Log error but don't fail the request
        }

        return back()->with('success', 'Leave rejected successfully!');
    }

    public function balances()
    {
        $balances = BDMLeaveBalance::with('bdm')->get();
        return view('admin.leaves.balances', compact('balances'));
    }

    public function updateBalance(Request $request, BDMLeaveBalance $balance)
    {
        $request->validate([
            'casual_leave' => 'required|integer|min:0',
            'sick_leave' => 'required|integer|min:0',
        ]);

        $balance->update([
            'casual_leave' => $request->casual_leave,
            'sick_leave' => $request->sick_leave,
        ]);

        return back()->with('success', 'Leave balance updated successfully!');
    }

    public function destroy(BDMLeaveApplication $leave)
    {
        if ($leave->status === 'approved') {
            return back()->with('error', 'Cannot delete approved leaves!');
        }

        $leave->delete();
        return redirect()->route('admin.leaves.index')
            ->with('success', 'Leave application deleted successfully!');
    }
}
