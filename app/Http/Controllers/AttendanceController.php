<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceRule;
use App\Models\LeaveRequest;
use App\Models\Salary;
use App\Models\Holiday;
use App\Mail\LateWarningMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class AttendanceController extends Controller
{
    /**
     * Today's Attendance Dashboard
     */
    public function today()
    {
        $user = Auth::user();
        
        // Check if user is a BDM
        $bdm = $user->bdm ?? null;
        if (!$bdm) {
            return redirect()->route('dashboard')->with('error', 'Only BDM employees can access attendance.');
        }
        
        $today = Carbon::today();
        
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->where('attendance_date', $today)
            ->first();

        $rules = AttendanceRule::first();
        $canCheckIn = Attendance::canCheckIn();
        $canCheckOut = Attendance::canCheckOut();
        $isLate = Attendance::isLate();

        // Get monthly summary
        $monthlySummary = Attendance::getMonthlySummary($user->id, $today->year, $today->month);

        // Check for pending leaves
        $pendingLeave = LeaveRequest::where('user_id', $user->id)
            ->where('from_date', '<=', $today)
            ->where('to_date', '>=', $today)
            ->where('status', 'approved')
            ->first();

        // Calculate if user can still do late check-in (within 30 mins of deadline)
        $checkInDeadline = $rules ? Carbon::now()->setTimeFromTimeString($rules->check_in_deadline) : null;
        $canLatCheckIn = $checkInDeadline && $isLate && Carbon::now()->diffInMinutes($checkInDeadline) < 30;

        return view('attendance.today', [
            'todayAttendance' => $todayAttendance,
            'canCheckIn' => $canCheckIn,
            'canCheckOut' => $canCheckOut,
            'isLate' => $isLate,
            'canLatCheckIn' => $canLatCheckIn,
            'rules' => $rules,
            'monthlySummary' => $monthlySummary,
            'pendingLeave' => $pendingLeave,
        ]);
    }

    /**
     * Check-In
     */
    public function checkIn(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Check if already checked in
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('attendance_date', $today)
            ->first();

        if ($existingAttendance && $existingAttendance->check_in_time) {
            return response()->json([
                'success' => false,
                'message' => 'Already checked in today',
            ], 400);
        }

        $rules = AttendanceRule::first();
        $deadline = Carbon::now()->setTimeFromTimeString($rules->check_in_deadline);
        $isLate = Carbon::now()->greaterThan($deadline);

        // Check if check-in is allowed
        if (Carbon::now()->greaterThan($deadline->copy()->addMinutes(30))) {
            return response()->json([
                'success' => false,
                'message' => 'Check-in time window has expired',
            ], 400);
        }

        if (!$existingAttendance) {
            $existingAttendance = new Attendance();
            $existingAttendance->user_id = $user->id;
            $existingAttendance->attendance_date = $today;
        }

        $existingAttendance->check_in_time = Carbon::now()->toTimeString();
        $existingAttendance->is_late = $isLate;
        $existingAttendance->status = 'present';
        $existingAttendance->save();

        // Get late count for current month
        $monthlyLateCount = Attendance::where('user_id', $user->id)
            ->whereYear('attendance_date', $today->year)
            ->whereMonth('attendance_date', $today->month)
            ->where('is_late', true)
            ->count();

        // Check if should send warning
        if ($monthlyLateCount >= 3 && $monthlyLateCount < 4) {
            Mail::to($user->email)->send(new LateWarningMail($user, $monthlyLateCount));
        }

        // Auto-assign half-day on 4th late
        if ($monthlyLateCount >= 4 && $rules->auto_assign_half_day) {
            $existingAttendance->status = 'half_day';
            $existingAttendance->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Checked in successfully' . ($isLate ? ' (LATE)' : ''),
            'attendance' => $existingAttendance,
        ]);
    }

    /**
     * Check-Out
     */
    public function checkOut(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $attendance = Attendance::where('user_id', $user->id)
            ->where('attendance_date', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'No check-in found for today',
            ], 400);
        }

        if ($attendance->check_out_time) {
            return response()->json([
                'success' => false,
                'message' => 'Already checked out today',
            ], 400);
        }

        $rules = AttendanceRule::first();
        $checkoutDeadline = Carbon::now()->setTimeFromTimeString($rules->check_out_time);

        if (Carbon::now()->greaterThan($checkoutDeadline)) {
            return response()->json([
                'success' => false,
                'message' => 'Check-out time has ended for today',
            ], 400);
        }

        $attendance->check_out_time = Carbon::now()->toTimeString();
        $attendance->save();

        return response()->json([
            'success' => true,
            'message' => 'Checked out successfully',
            'attendance' => $attendance,
        ]);
    }

    /**
     * Select Date Attendance
     */
    public function selectDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $user = Auth::user();
        $date = Carbon::parse($request->date);

        $attendance = Attendance::where('user_id', $user->id)
            ->where('attendance_date', $date->toDateString())
            ->first();

        if (!$attendance) {
            return view('attendance.select-date', [
                'attendance' => null,
                'date' => $date,
                'message' => 'No attendance record found for this date',
            ]);
        }

        return view('attendance.select-date', [
            'attendance' => $attendance,
            'date' => $date,
        ]);
    }

    /**
     * Month History
     */
    public function monthHistory(Request $request)
    {
        $user = Auth::user();
        $date = $request->get('date', now());
        $date = Carbon::parse($date);

        $attendances = Attendance::where('user_id', $user->id)
            ->whereYear('attendance_date', $date->year)
            ->whereMonth('attendance_date', $date->month)
            ->orderBy('attendance_date', 'asc')
            ->get();

        // Get summary
        $summary = Attendance::getMonthlySummary($user->id, $date->year, $date->month);

        return view('attendance.month-history', [
            'attendances' => $attendances,
            'summary' => $summary,
            'date' => $date,
        ]);
    }

    /**
     * Monthly Summary
     */
    public function monthlySummary(Request $request)
    {
        $user = Auth::user();
        $date = $request->get('date', now());
        $date = Carbon::parse($date);

        $summary = Attendance::getMonthlySummary($user->id, $date->year, $date->month);

        // Get approved leaves
        $leaves = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereYear('from_date', $date->year)
            ->whereMonth('from_date', $date->month)
            ->get();

        return view('attendance.monthly-summary', [
            'summary' => $summary,
            'leaves' => $leaves,
            'date' => $date,
        ]);
    }
}
