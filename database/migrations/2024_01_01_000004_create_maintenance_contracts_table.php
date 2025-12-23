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
        Schema::create('maintenance_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->enum('contract_type', ['Free', 'Chargeable']);
            $table->integer('free_months')->nullable(); // For free contracts
            $table->decimal('charges', 10, 2)->nullable(); // For chargeable contracts
            $table->enum('charge_frequency', ['Monthly', 'Quarterly', 'Annually'])->nullable();
            $table->date('domain_purchase_date')->nullable();
            $table->decimal('domain_amount', 10, 2)->nullable();
            $table->date('domain_renewal_date')->nullable();
            $table->date('hosting_purchase_date')->nullable();
            $table->decimal('hosting_amount', 10, 2)->nullable();
            $table->date('hosting_renewal_date')->nullable();
            $table->date('contract_start_date');
            $table->date('contract_end_date')->nullable();
            $table->enum('status', ['Active', 'Expired', 'Cancelled'])->default('Active');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_contracts');
    }
};
