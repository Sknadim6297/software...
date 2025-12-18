<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bdm_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bdm_id')->constrained()->onDelete('cascade');
            $table->string('month_year'); // Format: YYYY-MM
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('hra', 10, 2)->default(0);
            $table->decimal('other_allowances', 10, 2)->default(0);
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2);
            $table->string('salary_slip_path')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->unique(['bdm_id', 'month_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bdm_salaries');
    }
};
