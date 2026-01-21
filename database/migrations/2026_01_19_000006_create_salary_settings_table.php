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
        Schema::create('salary_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('base_salary', 15, 2)->default(0);
            $table->decimal('late_penalty_per_mark', 10, 2)->default(0);
            $table->decimal('half_day_deduction_percentage', 5, 2)->default(50); // % of daily rate
            $table->decimal('absent_deduction_percentage', 5, 2)->default(100); // % of daily rate
            $table->boolean('enable_deductions')->default(true);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_settings');
    }
};
