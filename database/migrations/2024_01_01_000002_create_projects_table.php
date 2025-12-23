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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('project_name');
            $table->enum('project_type', ['Website', 'Software', 'Application']);
            $table->date('start_date');
            $table->decimal('project_valuation', 12, 2);
            $table->decimal('upfront_payment', 12, 2)->default(0);
            $table->decimal('first_installment', 12, 2)->default(0);
            $table->decimal('second_installment', 12, 2)->default(0);
            $table->decimal('third_installment', 12, 2)->default(0);
            $table->foreignId('project_coordinator_id')->constrained('users')->onDelete('cascade');
            $table->enum('project_status', ['In Progress', 'Completed'])->default('In Progress');
            $table->boolean('upfront_paid')->default(false);
            $table->boolean('first_installment_paid')->default(false);
            $table->boolean('second_installment_paid')->default(false);
            $table->boolean('third_installment_paid')->default(false);
            $table->integer('current_installment')->default(0); // 0=upfront, 1=first, 2=second, 3=third
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
