<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bdm_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bdm_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['warning', 'target_failure', 'termination', 'leave_status', 'general']);
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bdm_notifications');
    }
};
