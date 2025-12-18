<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BDMDocument extends Model
{
    protected $table = 'bdm_documents';
    
    protected $fillable = [
        'bdm_id',
        'document_type',
        'file_path',
        'original_filename',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function bdm(): BelongsTo
    {
        return $this->belongsTo(BDM::class, 'bdm_id');
    }

    public static function getDocumentTypes(): array
    {
        return [
            'aadhaar_card' => 'Aadhaar Card',
            'pan_card' => 'PAN Card',
            '10th_admit_card' => '10th Admit Card',
            '12th_marksheet' => '12th Marksheet',
            'graduation_certificate' => 'Graduation Final Year Certificate',
            'last_company_offer_letter' => 'Last Company Appointment / Offer Letter',
            'salary_slip' => 'Salary Slip',
            'reference_contact' => 'Last Company Reference Contact Number',
        ];
    }

    public function getDocumentTypeLabelAttribute(): string
    {
        return self::getDocumentTypes()[$this->document_type] ?? $this->document_type;
    }
}
