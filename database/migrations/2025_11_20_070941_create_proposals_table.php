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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->string('lead_type')->comment('incoming or outgoing'); // incoming or outgoing lead
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('project_type')->comment('website, software, app, seo, etc');
            $table->text('project_description')->nullable();
            $table->longText('proposal_content'); // The actual proposal with template
            $table->decimal('proposed_amount', 12, 2);
            $table->string('currency', 10)->default('INR');
            $table->integer('estimated_days')->nullable();
            $table->text('deliverables')->nullable(); // JSON or text list of what will be provided
            $table->text('payment_terms')->nullable();
            $table->enum('status', [
                'draft',
                'sent',
                'viewed',
                'under_review',
                'accepted',
                'rejected'
            ])->default('draft');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            
            // Foreign key for leads (optional, since we're storing customer data directly)
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
