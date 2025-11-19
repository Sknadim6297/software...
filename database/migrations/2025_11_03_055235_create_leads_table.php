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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['incoming', 'outgoing'])->default('incoming');
            $table->date('date');
            $table->time('time');
            $table->enum('platform', [
                'facebook', 
                'justdial', 
                'google', 
                'instagram',
                'linkedin', 
                'website',
                'referral',
                'cold_call',
                'other'
            ])->default('other');
            $table->string('customer_name');
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('project_type')->nullable();
            $table->decimal('project_valuation', 15, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', [
                'pending', 
                'callback_scheduled', 
                'did_not_receive', 
                'not_required', 
                'meeting_scheduled', 
                'not_interested',
                'interested',
                'converted'
            ])->default('pending');
            $table->dateTime('callback_time')->nullable();
            $table->string('meeting_address')->nullable();
            $table->dateTime('meeting_time')->nullable();
            $table->string('meeting_person_name')->nullable();
            $table->string('meeting_phone_number')->nullable();
            $table->text('meeting_summary')->nullable();
            $table->boolean('callback_completed')->default(false);
            $table->text('call_notes')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable(); // For BDM assignment
            $table->timestamps();

            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->index(['type', 'status']);
            $table->index(['date', 'time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
