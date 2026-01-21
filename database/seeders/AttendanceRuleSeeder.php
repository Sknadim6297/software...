<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceRule;
use Illuminate\Support\Facades\DB;

class AttendanceRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing rules
        DB::table('attendance_rules')->truncate();

        // Create default attendance rule
        AttendanceRule::create([
            'check_in_deadline' => '10:45:00',
            'check_out_time' => '20:30:00',
            'late_marks_for_warning' => 3,
            'late_marks_for_half_day' => 4,
            'block_mobile_login_on_late' => true,
            'auto_assign_half_day' => true,
            'enable_leave_balance' => true,
        ]);
    }
}
