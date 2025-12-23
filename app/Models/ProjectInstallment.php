<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectInstallment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'installment_type',
        'amount',
        'transaction_id',
        'paid',
        'paid_at',
        'invoice_id',
    ];

    protected $casts = [
        'paid' => 'boolean',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the project that owns the installment.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the invoice for the installment.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
