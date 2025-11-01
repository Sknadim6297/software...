<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_number',
        'invoice_type', // 'regular', 'proforma'
        'invoice_date',
        'subtotal',
        'discount_amount',
        'tax_total',
        'grand_total',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    /**
     * Get the customer that owns the invoice.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the items for the invoice.
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Generate invoice number based on type
     */
    public static function generateInvoiceNumber($type = 'regular')
    {
        $year = date('Y');
        $prefix = $type === 'proforma' ? 'KX-PER-' : 'KX-';
        
        $lastInvoice = self::where('invoice_type', $type)
            ->where('invoice_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            preg_match('/\d+/', $lastInvoice->invoice_number, $matches);
            $number = isset($matches[0]) ? intval($matches[0]) + 1 : 109;
        } else {
            $number = 109;
        }

        return $type === 'proforma' ? "{$prefix}{$number}/{$year}" : "{$prefix}{$number}";
    }
}
