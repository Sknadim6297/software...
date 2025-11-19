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
            // Add email field if it doesn't exist
            if (!Schema::hasColumn('customers', 'email')) {
                $table->string('email')->nullable()->after('alternate_number');
            }
            
            // Add project-related fields
            if (!Schema::hasColumn('customers', 'project_type')) {
                $table->enum('project_type', [
                    'web_development',
                    'mobile_app', 
                    'ecommerce',
                    'software_development',
                    'ui_ux_design',
                    'digital_marketing',
                    'consultation',
                    'other'
                ])->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('customers', 'project_valuation')) {
                $table->decimal('project_valuation', 12, 2)->nullable()->after('project_type');
            }
            
            if (!Schema::hasColumn('customers', 'project_start_date')) {
                $table->date('project_start_date')->nullable()->after('project_valuation');
            }
            
            // Add payment terms field
            if (!Schema::hasColumn('customers', 'payment_terms')) {
                $table->enum('payment_terms', [
                    'advance_100',
                    'advance_50_delivery_50',
                    'advance_30_milestone_40_delivery_30',
                    'net_30',
                    'net_15',
                    'on_delivery',
                    'custom'
                ])->nullable()->after('project_start_date');
            }
            
            // Add lead source field
            if (!Schema::hasColumn('customers', 'lead_source')) {
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
                ])->nullable()->after('payment_terms');
            }
            
            // Add remarks field
            if (!Schema::hasColumn('customers', 'remarks')) {
                $table->text('remarks')->nullable()->after('address');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'project_type',
                'project_valuation', 
                'project_start_date',
                'payment_terms',
                'lead_source',
                'remarks'
            ]);
        });
    }
};
