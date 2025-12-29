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
        Schema::table('projects', function (Blueprint $table) {
            // Add missing customer fields if not exists
            if (!Schema::hasColumn('projects', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('customer_id');
            }
            if (!Schema::hasColumn('projects', 'customer_mobile')) {
                $table->string('customer_mobile')->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('projects', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('customer_mobile');
            }

            // Add payment date tracking
            if (!Schema::hasColumn('projects', 'upfront_paid_date')) {
                $table->date('upfront_paid_date')->nullable()->after('upfront_paid');
            }
            if (!Schema::hasColumn('projects', 'first_installment_paid_date')) {
                $table->date('first_installment_paid_date')->nullable()->after('first_installment_paid');
            }
            if (!Schema::hasColumn('projects', 'second_installment_paid_date')) {
                $table->date('second_installment_paid_date')->nullable()->after('second_installment_paid');
            }
            if (!Schema::hasColumn('projects', 'third_installment_paid_date')) {
                $table->date('third_installment_paid_date')->nullable()->after('third_installment_paid');
            }

            // Add project coordinator name
            if (!Schema::hasColumn('projects', 'project_coordinator')) {
                $table->string('project_coordinator')->nullable()->after('third_installment_paid_date');
            }

            // Add BDM ID
            if (!Schema::hasColumn('projects', 'bdm_id')) {
                $table->unsignedBigInteger('bdm_id')->nullable()->after('project_coordinator');
            }

            // Add status field (if using project_status, keep for backwards compat)
            if (!Schema::hasColumn('projects', 'status')) {
                $table->enum('status', ['In Progress', 'Completed'])->default('In Progress')->after('project_status');
            }

            // Domain & Hosting Details
            if (!Schema::hasColumn('projects', 'domain_name')) {
                $table->string('domain_name')->nullable();
                $table->date('domain_purchase_date')->nullable();
                $table->decimal('domain_amount', 10, 2)->nullable();
                $table->string('domain_renewal_cycle')->nullable();
                $table->date('domain_renewal_date')->nullable();
            }

            if (!Schema::hasColumn('projects', 'hosting_provider')) {
                $table->string('hosting_provider')->nullable();
                $table->date('hosting_purchase_date')->nullable();
                $table->decimal('hosting_amount', 10, 2)->nullable();
                $table->string('hosting_renewal_cycle')->nullable();
                $table->date('hosting_renewal_date')->nullable();
            }

            // Maintenance Contract
            if (!Schema::hasColumn('projects', 'maintenance_enabled')) {
                $table->boolean('maintenance_enabled')->default(false);
                $table->enum('maintenance_type', ['Free', 'Chargeable'])->nullable();
                $table->integer('maintenance_months')->nullable();
                $table->decimal('maintenance_charge', 10, 2)->nullable();
                $table->enum('maintenance_billing_cycle', ['Monthly', 'Quarterly', 'Annually'])->nullable();
                $table->date('maintenance_start_date')->nullable();
                $table->unsignedBigInteger('maintenance_contract_id')->nullable();
            }

            // Metadata
            if (!Schema::hasColumn('projects', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (!Schema::hasColumn('projects', 'payment_invoices')) {
                $table->json('payment_invoices')->nullable();
            }

            // Add soft deletes if not exists
            if (!Schema::hasColumn('projects', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // Add foreign keys
        Schema::table('projects', function (Blueprint $table) {
            // Add BDM foreign key
            if (!Schema::hasColumn('projects', 'bdm_id')) {
                $table->foreign('bdm_id')->references('id')->on('bdms')->onDelete('set null');
            }

            // Add maintenance contract foreign key
            if (!Schema::hasColumn('projects', 'maintenance_contract_id')) {
                $table->foreign('maintenance_contract_id')->references('id')->on('contracts')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Drop columns
            $table->dropColumn([
                'customer_name',
                'customer_mobile',
                'customer_email',
                'upfront_paid_date',
                'first_installment_paid_date',
                'second_installment_paid_date',
                'third_installment_paid_date',
                'project_coordinator',
                'bdm_id',
                'status',
                'domain_name',
                'domain_purchase_date',
                'domain_amount',
                'domain_renewal_cycle',
                'domain_renewal_date',
                'hosting_provider',
                'hosting_purchase_date',
                'hosting_amount',
                'hosting_renewal_cycle',
                'hosting_renewal_date',
                'maintenance_enabled',
                'maintenance_type',
                'maintenance_months',
                'maintenance_charge',
                'maintenance_billing_cycle',
                'maintenance_start_date',
                'maintenance_contract_id',
                'notes',
                'payment_invoices',
                'deleted_at'
            ]);
        });
    }
};
