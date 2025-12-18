<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bdm_leave_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bdm_id')->constrained()->onDelete('cascade');
            $table->enum('leave_type', ['casual', 'sick', 'unpaid']);
            $table->date('leave_date');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_remarks')->nullable();
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamp('admin_action_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bdm_leave_applications');
    }
};
