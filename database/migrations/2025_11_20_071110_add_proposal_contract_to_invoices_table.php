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
            $table->foreignId('proposal_id')->nullable()->after('customer_id')->constrained()->onDelete('set null');
            $table->foreignId('contract_id')->nullable()->after('proposal_id')->constrained()->onDelete('set null');
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'overdue'])->default('pending')->after('grand_total');
            $table->date('due_date')->nullable()->after('invoice_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['proposal_id']);
            $table->dropForeign(['contract_id']);
            $table->dropColumn(['proposal_id', 'contract_id', 'payment_status', 'due_date']);
        });
    }
};
