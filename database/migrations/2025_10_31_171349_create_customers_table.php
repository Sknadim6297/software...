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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('company_name')->nullable();
            $table->string('number', 20);
            $table->string('alternate_number', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('gst_number', 20)->nullable();
            $table->string('state_code', 10)->nullable();
            $table->string('state_name', 100)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
