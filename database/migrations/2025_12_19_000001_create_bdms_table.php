<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bdms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('profile_image')->nullable();
            $table->string('name');
            $table->string('father_name');
            $table->date('date_of_birth');
            $table->string('highest_education');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('employee_code')->unique();
            $table->date('joining_date');
            $table->decimal('current_ctc', 10, 2)->default(0);
            $table->enum('status', ['active', 'warned', 'terminated'])->default('active');
            $table->integer('warning_count')->default(0);
            $table->timestamp('last_warning_date')->nullable();
            $table->timestamp('termination_date')->nullable();
            $table->text('termination_reason')->nullable();
            $table->boolean('can_login')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bdms');
    }
};
