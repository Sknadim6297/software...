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
        Schema::table('bdm_leave_balances', function (Blueprint $table) {
            // Add new columns
            $table->integer('casual_leave')->default(0)->after('bdm_id');
            $table->integer('sick_leave')->default(0)->after('casual_leave');
            $table->integer('unpaid_leave')->default(0)->after('sick_leave');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bdm_leave_balances', function (Blueprint $table) {
            $table->dropColumn(['casual_leave', 'sick_leave', 'unpaid_leave']);
        });
    }
};
