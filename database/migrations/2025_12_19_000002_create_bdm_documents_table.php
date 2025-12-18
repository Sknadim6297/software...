<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bdm_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bdm_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', [
                'aadhaar_card',
                'pan_card',
                '10th_admit_card',
                '12th_marksheet',
                'graduation_certificate',
                'last_company_offer_letter',
                'salary_slip',
                'reference_contact'
            ]);
            $table->string('file_path');
            $table->string('original_filename');
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['bdm_id', 'document_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bdm_documents');
    }
};
