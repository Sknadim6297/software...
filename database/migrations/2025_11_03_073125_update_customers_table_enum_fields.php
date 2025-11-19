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
        Schema::table('customers', function (Blueprint $table) {
            // Update project_type enum to include all new values
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
            
            // Update payment_terms enum to include all new values
            $table->enum('payment_terms', [
                'advance_100',
                'advance_50_delivery_50',
                'advance_30_milestone_40_delivery_30',
                'net_30',
                'net_15',
                'on_delivery',
                'custom'
            ])->nullable()->change();
            
            // Update lead_source enum to include all new values
            $table->enum('lead_source', [
                'website',
                'facebook',
                'instagram', 
                'linkedin',
                'google',
                'justdial',
                'referral',
                'cold_call',
                'email',
                'other'
            ])->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Revert to original enum values if needed
            $table->enum('project_type', ['web', 'mobile', 'other'])->nullable()->change();
            $table->enum('payment_terms', ['advance', 'net_30', 'custom'])->nullable()->change();
            $table->enum('lead_source', ['website', 'facebook', 'google', 'referral'])->nullable()->change();
        });
    }
};
