<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRule extends Model
{
    protected $table = 'attendance_rules';

    protected $fillable = [
        'check_in_deadline',
        'check_out_time',
        'late_marks_for_warning',
        'late_marks_for_half_day',
        'block_mobile_login_on_late',
        'auto_assign_half_day',
        'enable_leave_balance',
    ];

    protected $casts = [
        'block_mobile_login_on_late' => 'boolean',
        'auto_assign_half_day' => 'boolean',
        'enable_leave_balance' => 'boolean',
    ];
}
