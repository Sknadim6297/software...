<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->boolean('meeting_completed')->default(false)->after('meeting_summary');
            $table->text('meeting_completed_summary')->nullable()->after('meeting_completed');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['meeting_completed', 'meeting_completed_summary']);
        });
    }
};
