<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    /**
     * Show leave requests list
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login');
        }
        
        $leaves = LeaveRequest::where('user_id', $user->id)
            ->orderBy('from_date', 'desc')
            ->paginate(15);

        return view('leaves.index', ['leaves' => $leaves]);
    }

    /**
     * Show create leave form
     */
    public function create()
    {
        return view('leaves.create');
    }

    /**
     * Store leave request
     */
    public function store(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'leave_type' => 'required|in:full_day,half_day',
            'reason' => 'required|string|min:10|max:500',
        ]);

        $user = Auth::user();
        $fromDate = Carbon::parse($request->from_date);
        $toDate = Carbon::parse($request->to_date);

        // Check for overlapping leaves
        if (LeaveRequest::hasOverlap($user->id, $fromDate, $toDate)) {
            return back()->withErrors(['error' => 'Leave dates overlap with existing leave request']);
        }

        // Check if dates are in the past
        if ($fromDate->isPast() && !$fromDate->isToday()) {
            return back()->withErrors(['error' => 'Cannot apply leave for past dates']);
        }

        $leave = LeaveRequest::create([
            'user_id' => $user->id,
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'leave_type' => $request->leave_type,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully');
    }

    /**
     * Show leave details
     */
    public function show(LeaveRequest $leave)
    {
        $this->authorize('view', $leave);

        return view('leaves.show', ['leave' => $leave]);
    }

    /**
     * Cancel leave request
     */
    public function cancel(LeaveRequest $leave)
    {
        $this->authorize('update', $leave);

        if ($leave->status !== 'pending') {
            return back()->withErrors(['error' => 'Can only cancel pending leave requests']);
        }

        $leave->update(['status' => 'cancelled']);

        return back()->with('success', 'Leave request cancelled successfully');
    }

    /**
     * Get leave balance if enabled
     */
    public function balance()
    {
        $user = Auth::user();
        $currentYear = now()->year;

        $approvedLeaves = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereYear('from_date', $currentYear)
            ->get();

        $totalLeaveDays = 0;
        foreach ($approvedLeaves as $leave) {
            $totalLeaveDays += $leave->getLeaveDaysCount();
        }

        return view('leaves.balance', [
            'totalAllowedDays' => 18, // Example: 18 days per year
            'usedDays' => $totalLeaveDays,
            'remainingDays' => 18 - $totalLeaveDays,
        ]);
    }

    /**
     * Check leave status
     */
    public function status()
    {
        $user = Auth::user();
        $leaves = LeaveRequest::where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('leaves.status', ['leaves' => $leaves]);
    }
}
