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
            // Check and add new fields for enhanced customer management
            if (!Schema::hasColumn('customers', 'email')) {
                $table->string('email')->nullable()->after('number');
            }
            if (!Schema::hasColumn('customers', 'project_type')) {
                $table->string('project_type')->nullable()->after('alternate_number');
            }
            if (!Schema::hasColumn('customers', 'project_valuation')) {
                $table->decimal('project_valuation', 15, 2)->nullable()->after('project_type');
            }
            if (!Schema::hasColumn('customers', 'project_start_date')) {
                $table->date('project_start_date')->nullable()->after('project_valuation');
            }
            if (!Schema::hasColumn('customers', 'payment_terms')) {
                $table->text('payment_terms')->nullable()->after('project_start_date');
            }
            if (!Schema::hasColumn('customers', 'lead_source')) {
                $table->enum('lead_source', ['justdial', 'facebook', 'google', 'other'])->default('other')->after('payment_terms');
            }
            if (!Schema::hasColumn('customers', 'remarks')) {
                $table->text('remarks')->nullable()->after('lead_source');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $columns = ['email', 'project_type', 'project_valuation', 'project_start_date', 'payment_terms', 'lead_source', 'remarks'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('customers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
