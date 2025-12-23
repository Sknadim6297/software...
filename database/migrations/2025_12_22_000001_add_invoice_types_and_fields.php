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
        Schema::table('invoices', function (Blueprint $table) {
            // Update invoice_type enum to include new types
            $table->dropColumn('invoice_type');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('invoice_type', ['proforma', 'tax_invoice', 'money_receipt'])->default('tax_invoice')->after('invoice_number');
            
            // Add reference fields
            $table->string('invoice_ref_no')->nullable()->after('invoice_date');
            $table->date('invoice_ref_date')->nullable()->after('invoice_ref_no');
            $table->text('remarks')->nullable()->after('invoice_ref_date');
            
            // Add TCS and Round Off fields
            $table->decimal('tcs_amount', 12, 2)->default(0)->after('tax_total');
            $table->decimal('round_off', 12, 2)->default(0)->after('tcs_amount');
            
            // Add customer GSTIN and state code
            $table->string('customer_gstin')->nullable()->after('customer_id');
            $table->string('customer_state_code')->nullable()->after('customer_gstin');
            $table->string('customer_state_name')->nullable()->after('customer_state_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_ref_no',
                'invoice_ref_date',
                'remarks',
                'tcs_amount',
                'round_off',
                'customer_gstin',
                'customer_state_code',
                'customer_state_name'
            ]);
            
            $table->dropColumn('invoice_type');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('invoice_type', ['regular', 'proforma'])->default('regular');
        });
    }
};
