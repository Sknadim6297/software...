<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceRule;
use App\Models\Holiday;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Attendance dashboard
     */
    public function dashboard()
    {
        $today = Carbon::today();
        
        // Today's attendance statistics
        $totalEmployees = User::whereHas('bdm')->count();
        $presentToday = Attendance::where('attendance_date', $today)
            ->where('status', 'present')
            ->distinct('user_id')
            ->count();
        
        $absentToday = Attendance::where('attendance_date', $today)
            ->where('status', 'absent')
            ->distinct('user_id')
            ->count();
        
        $halfDayToday = Attendance::where('attendance_date', $today)
            ->where('status', 'half_day')
            ->distinct('user_id')
            ->count();
        
        $onLeaveToday = Attendance::where('attendance_date', $today)
            ->where('status', 'leave')
            ->distinct('user_id')
            ->count();

        // Pending checkouts
        $pendingCheckout = Attendance::where('attendance_date', $today)
            ->whereNotNull('check_in_time')
            ->whereNull('check_out_time')
            ->with('user')
            ->get();

        // Late employees
        $lateEmployees = Attendance::where('attendance_date', $today)
            ->where('is_late', true)
            ->with('user')
            ->get();

        // Monthly late tracking
        $monthlyLateTracking = Attendance::selectRaw('user_id, COUNT(CASE WHEN is_late = true THEN 1 END) as late_count')
            ->whereYear('attendance_date', $today->year)
            ->whereMonth('attendance_date', $today->month)
            ->groupBy('user_id')
            ->with('user')
            ->orderByDesc('late_count')
            ->limit(20)
            ->get();

        return view('admin.attendance.dashboard', [
            'totalEmployees' => $totalEmployees,
            'presentToday' => $presentToday,
            'absentToday' => $absentToday,
            'halfDayToday' => $halfDayToday,
            'onLeaveToday' => $onLeaveToday,
            'pendingCheckout' => $pendingCheckout,
            'lateEmployees' => $lateEmployees,
            'monthlyLateTracking' => $monthlyLateTracking,
        ]);
    }

    /**
     * View all attendance records
     */
    public function index(Request $request)
    {
        $query = Attendance::with('user');

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->date) {
            $query->where('attendance_date', Carbon::parse($request->date));
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->is_late) {
            $query->where('is_late', true);
        }

        $attendances = $query->orderBy('attendance_date', 'desc')->paginate(50);
        $users = User::whereHas('bdm')->orderBy('name')->get();

        return view('admin.attendance.index', [
            'attendances' => $attendances,
            'users' => $users,
        ]);
    }

    /**
     * Edit attendance
     */
    public function edit(Attendance $attendance)
    {
        return view('admin.attendance.edit', ['attendance' => $attendance]);
    }

    /**
     * Update attendance
     */
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,half_day,leave',
            'notes' => 'nullable|string',
        ]);

        $attendance->update($request->only(['check_in_time', 'check_out_time', 'status', 'notes']));

        return back()->with('success', 'Attendance updated successfully');
    }

    /**
     * Add manual attendance
     */
    public function addManual(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'attendance_date' => 'required|date',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,half_day,leave',
        ]);

        try {
            $existing = Attendance::where('user_id', $validated['user_id'])
                ->where('attendance_date', $validated['attendance_date'])
                ->first();

            if ($existing) {
                $existing->update($request->only(['check_in_time', 'check_out_time', 'status']));
                $message = 'Attendance record updated successfully';
            } else {
                Attendance::create($validated);
                $message = 'Attendance record added successfully';
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to add attendance: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Unlock previous day checkout
     */
    public function unlockCheckout(Attendance $attendance)
    {
        $attendance->update(['check_out_time' => null]);

        return back()->with('success', 'Checkout unlocked. Employee can checkout now.');
    }

    /**
     * Remove penalty
     */
    public function removePenalty(Attendance $attendance)
    {
        $attendance->update(['is_late' => false, 'is_early_checkout' => false]);

        return back()->with('success', 'Penalty removed');
    }

    /**
     * View employee attendance history
     */
    public function employeeHistory(User $user, Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('attendance_date', $month)
            ->whereYear('attendance_date', $year)
            ->orderBy('attendance_date', 'asc')
            ->get();

        $summary = Attendance::getMonthlySummary($user->id, $year, $month);

        return view('admin.attendance.employee-history', [
            'user' => $user,
            'attendances' => $attendances,
            'summary' => $summary,
            'month' => $month,
            'year' => $year,
        ]);
    }

    /**
     * Attendance rules settings
     */
    public function settings()
    {
        $rules = AttendanceRule::first() ?? new AttendanceRule();

        return view('admin.attendance.settings', ['rules' => $rules]);
    }

    /**
     * Update attendance rules
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'check_in_deadline' => 'required|date_format:H:i',
            'check_out_time' => 'required|date_format:H:i',
            'late_marks_for_warning' => 'required|integer|min:1',
            'late_marks_for_half_day' => 'required|integer|min:1',
        ]);

        $rules = AttendanceRule::first();
        if ($rules) {
            $rules->update($request->only([
                'check_in_deadline',
                'check_out_time',
                'late_marks_for_warning',
                'late_marks_for_half_day',
                'block_mobile_login_on_late',
                'auto_assign_half_day',
                'enable_leave_balance',
            ]));
        } else {
            AttendanceRule::create($request->only([
                'check_in_deadline',
                'check_out_time',
                'late_marks_for_warning',
                'late_marks_for_half_day',
                'block_mobile_login_on_late',
                'auto_assign_half_day',
                'enable_leave_balance',
            ]));
        }

        return back()->with('success', 'Attendance rules updated successfully');
    }

    /**
     * Manage holidays
     */
    public function holidays()
    {
        $holidays = Holiday::orderBy('holiday_date', 'asc')->get();

        return view('admin.attendance.holidays', ['holidays' => $holidays]);
    }

    /**
     * Add holiday
     */
    public function addHoliday(Request $request)
    {
        $request->validate([
            'holiday_date' => 'required|date|unique:holidays',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        Holiday::create($request->only(['holiday_date', 'name', 'description']));

        return back()->with('success', 'Holiday added successfully');
    }

    /**
     * Delete holiday
     */
    public function deleteHoliday(Holiday $holiday)
    {
        $holiday->delete();

        return back()->with('success', 'Holiday deleted successfully');
    }
}
