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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->text('product_description');
            $table->string('sac_hsn_code', 20)->default('9983');
            $table->integer('quantity')->default(1);
            $table->decimal('rate', 12, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('cgst_percentage', 5, 2)->default(0);
            $table->decimal('sgst_percentage', 5, 2)->default(0);
            $table->decimal('igst_percentage', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
