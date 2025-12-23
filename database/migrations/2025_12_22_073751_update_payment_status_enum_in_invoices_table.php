<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing values to match new enum
        DB::table('invoices')->where('payment_status', 'pending')->update(['payment_status' => 'unpaid']);
        DB::table('invoices')->where('payment_status', 'partial')->update(['payment_status' => 'partially_paid']);
        DB::table('invoices')->where('payment_status', 'overdue')->update(['payment_status' => 'unpaid']);
        
        // Drop and recreate the payment_status column with new enum values
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
        
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('payment_status', ['paid', 'unpaid', 'partially_paid'])->default('unpaid')->after('grand_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('payment_status');
        });
        
        Schema::table('invoices', function (Blueprint $table) {
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'overdue'])->default('pending')->after('grand_total');
        });
    }
};
