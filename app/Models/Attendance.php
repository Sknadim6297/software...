<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $table = 'attendances';

    protected $fillable = [
        'user_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'status',
        'is_late',
        'is_early_checkout',
        'notes',
        'late_count',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
        'is_late' => 'boolean',
        'is_early_checkout' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Check if user can check in
    public static function canCheckIn(): bool
    {
        $rules = AttendanceRule::first();
        $deadline = Carbon::now()->setTimeFromTimeString($rules->check_in_deadline);
        return Carbon::now()->lessThanOrEqualTo($deadline);
    }

    // Check if user is late
    public static function isLate(): bool
    {
        $rules = AttendanceRule::first();
        $deadline = Carbon::now()->setTimeFromTimeString($rules->check_in_deadline);
        return Carbon::now()->greaterThan($deadline);
    }

    // Check if user can check out
    public static function canCheckOut(): bool
    {
        $rules = AttendanceRule::first();
        $checkoutTime = Carbon::now()->setTimeFromTimeString($rules->check_out_time);
        return Carbon::now()->lessThanOrEqualTo($checkoutTime);
    }

    // Get today's attendance for user
    public static function getTodayAttendance($userId)
    {
        return self::where('user_id', $userId)
            ->where('attendance_date', Carbon::today())
            ->first();
    }

    // Get monthly summary
    public static function getMonthlySummary($userId, $year, $month)
    {
        return self::where('user_id', $userId)
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->selectRaw('
                COUNT(CASE WHEN status = "present" THEN 1 END) as present_days,
                COUNT(CASE WHEN status = "absent" THEN 1 END) as absent_days,
                COUNT(CASE WHEN status = "half_day" THEN 1 END) as half_days,
                COUNT(CASE WHEN is_late = true THEN 1 END) as late_count,
                COUNT(CASE WHEN status = "leave" THEN 1 END) as approved_leaves
            ')
            ->first();
    }

    // Get late count for current month
    public function getMonthlyLateCount()
    {
        return self::where('user_id', $this->user_id)
            ->whereYear('attendance_date', Carbon::now()->year)
            ->whereMonth('attendance_date', Carbon::now()->month)
            ->where('is_late', true)
            ->count();
    }
}
