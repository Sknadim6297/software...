<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bdm_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bdm_id')->constrained()->onDelete('cascade');
            $table->enum('target_type', ['monthly', 'quarterly', 'annual']);
            $table->string('period'); // YYYY-MM for monthly, YYYY-Q1/Q2/Q3/Q4 for quarterly, YYYY for annual
            $table->integer('project_target')->default(0);
            $table->decimal('revenue_target', 15, 2)->default(0);
            $table->integer('carried_forward_projects')->default(0);
            $table->decimal('carried_forward_revenue', 15, 2)->default(0);
            $table->integer('total_project_target')->default(0);
            $table->decimal('total_revenue_target', 15, 2)->default(0);
            $table->integer('projects_achieved')->default(0);
            $table->decimal('revenue_achieved', 15, 2)->default(0);
            $table->decimal('achievement_percentage', 5, 2)->default(0);
            $table->boolean('target_met')->default(false);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            
            $table->unique(['bdm_id', 'target_type', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bdm_targets');
    }
};
