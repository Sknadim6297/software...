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
        // First, let's change the columns from ENUM to VARCHAR to allow more flexibility
        DB::statement("ALTER TABLE customers MODIFY COLUMN project_type VARCHAR(100) NULL");
        DB::statement("ALTER TABLE customers MODIFY COLUMN payment_terms VARCHAR(255) NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original ENUM values if needed
        Schema::table('customers', function (Blueprint $table) {
            $table->enum('project_type', [
                'web_development',
                'mobile_app', 
                'ecommerce',
                'software_development',
                'ui_ux_design',
                'digital_marketing',
                'consultation',
                'other'
            ])->nullable()->change();
            
            $table->enum('payment_terms', [
                'advance_100',
                'advance_50_delivery_50',
                'advance_30_milestone_40_delivery_30',
                'net_30',
                'net_15',
                'on_delivery',
                'custom'
            ])->nullable()->change();
        });
    }
};
