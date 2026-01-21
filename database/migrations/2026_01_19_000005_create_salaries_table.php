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
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('base_salary', 15, 2);
            $table->integer('year');
            $table->integer('month'); // 1-12
            $table->integer('working_days')->default(0);
            $table->integer('present_days')->default(0);
            $table->integer('absent_days')->default(0);
            $table->integer('half_days')->default(0);
            $table->integer('late_count')->default(0);
            $table->integer('approved_leaves')->default(0);
            $table->decimal('daily_rate', 12, 2)->default(0);
            $table->decimal('late_deduction', 12, 2)->default(0); // Per late mark
            $table->decimal('half_day_deduction', 12, 2)->default(0);
            $table->decimal('absent_deduction', 12, 2)->default(0);
            $table->decimal('other_deductions', 12, 2)->default(0);
            $table->decimal('gross_salary', 15, 2)->default(0);
            $table->decimal('total_deductions', 15, 2)->default(0);
            $table->decimal('net_salary', 15, 2)->default(0);
            $table->text('deduction_details')->nullable(); // JSON for breakdown
            $table->string('payslip_file')->nullable();
            $table->boolean('is_processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            
            $table->unique(['user_id', 'year', 'month']);
            $table->index(['user_id', 'year', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
