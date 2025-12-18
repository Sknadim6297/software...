<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->foreignId('bdm_id')->nullable()->after('proposal_id')->constrained('bdms')->nullOnDelete();
            $table->timestamp('finalized_at')->nullable()->after('signed_at');
            $table->decimal('total_amount', 10, 2)->nullable()->after('final_amount')->comment('Used for target calculation');
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['bdm_id']);
            $table->dropColumn(['bdm_id', 'finalized_at', 'total_amount']);
        });
    }
};
