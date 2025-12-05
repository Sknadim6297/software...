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
        Schema::table('leads', function (Blueprint $table) {
            $table->string('has_gst')->nullable()->comment('yes/no - Does customer have GST');
            $table->string('gst_number', 15)->nullable()->comment('Customer GST number if they have one');
            $table->string('wants_gst')->nullable()->comment('yes/no - Does customer want to pay GST');
            $table->string('invoice_gst_number', 15)->nullable()->comment('GST number for invoicing if different');
            $table->string('gst_email')->nullable()->comment('Email for GST invoice');
            $table->string('project_type_other')->nullable()->comment('Custom project type when Other is selected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['has_gst', 'gst_number', 'wants_gst', 'invoice_gst_number', 'gst_email', 'project_type_other']);
        });
    }
};
