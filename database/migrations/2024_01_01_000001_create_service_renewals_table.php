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
        Schema::create('service_renewals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->enum('service_type', [
                'Domain',
                'Server',
                'Digital Marketing',
                'Website Maintenance',
                'Application Maintenance',
                'Software Maintenance'
            ]);
            $table->date('start_date');
            $table->date('renewal_date');
            $table->enum('renewal_type', ['Monthly', 'Yearly', 'Quarterly']);
            $table->decimal('amount', 10, 2);
            $table->enum('service_status', ['Active', 'Deactive'])->default('Active');
            $table->string('transaction_id')->nullable();
            $table->text('stop_reason')->nullable();
            $table->boolean('auto_renewal')->default(true);
            $table->boolean('renewal_mail_sent')->default(false);
            $table->timestamp('last_renewal_mail_sent_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_renewals');
    }
};
