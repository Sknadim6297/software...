<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendance_rules', function (Blueprint $table) {
            $table->id();
            $table->time('check_in_deadline')->default('10:45:00'); // Latest time to check in
            $table->time('check_out_time')->default('20:30:00'); // Check out time
            $table->integer('late_marks_for_warning')->default(3); // 3 late marks = warning
            $table->integer('late_marks_for_half_day')->default(4); // 4th late = half day
            $table->boolean('block_mobile_login_on_late')->default(true);
            $table->boolean('auto_assign_half_day')->default(true);
            $table->boolean('enable_leave_balance')->default(true);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_rules');
    }
};
