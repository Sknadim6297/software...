<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_description',
        'sac_hsn_code',
        'quantity',
        'rate',
        'discount_percentage',
        'cgst_percentage',
        'sgst_percentage',
        'igst_percentage',
        'tax_amount',
        'total_amount',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'rate' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'cgst_percentage' => 'decimal:2',
        'sgst_percentage' => 'decimal:2',
        'igst_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the invoice that owns the item.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
