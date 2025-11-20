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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained()->onDelete('cascade');
            $table->string('contract_number')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->longText('contract_content'); // Auto-generated from proposal
            $table->string('project_type');
            $table->decimal('final_amount', 12, 2);
            $table->string('currency', 10)->default('INR');
            $table->date('start_date')->nullable();
            $table->date('expected_completion_date')->nullable();
            $table->text('deliverables')->nullable();
            $table->text('milestones')->nullable(); // JSON format for tracking
            $table->text('payment_schedule')->nullable(); // When payments are due
            $table->enum('status', [
                'pending_signature',
                'active',
                'completed',
                'cancelled'
            ])->default('pending_signature');
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('sent_to_customer_at')->nullable();
            $table->timestamp('sent_to_admin_at')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
