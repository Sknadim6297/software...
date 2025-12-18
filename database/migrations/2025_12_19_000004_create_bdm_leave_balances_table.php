<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bdm_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bdm_id')->unique()->constrained()->onDelete('cascade');
            $table->integer('casual_leave_balance')->default(0);
            $table->integer('sick_leave_balance')->default(0);
            $table->integer('casual_leave_used_this_month')->default(0);
            $table->integer('sick_leave_used_this_month')->default(0);
            $table->string('current_month')->nullable(); // YYYY-MM format
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bdm_leave_balances');
    }
};
