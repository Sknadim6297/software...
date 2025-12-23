<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'proposal_id',
        'contract_id',
        'invoice_number',
        'invoice_type', // 'proforma', 'tax_invoice', 'money_receipt'
        'invoice_date',
        'due_date',
        'invoice_ref_no',
        'invoice_ref_date',
        'remarks',
        'subtotal',
        'discount_amount',
        'tax_total',
        'tcs_amount',
        'round_off',
        'grand_total',
        'payment_status',
        'notes',
        'customer_gstin',
        'customer_state_code',
        'customer_state_name',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'invoice_ref_date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'tcs_amount' => 'decimal:2',
        'round_off' => 'decimal:2',
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
     * Get the proposal that owns this invoice
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    /**
     * Get the contract that owns this invoice
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
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
    public static function generateInvoiceNumber($type = 'tax_invoice')
    {
        $year = date('Y');
        $prefix = match($type) {
            'proforma' => 'KX-PER-',
            'money_receipt' => 'KX-MR-',
            default => 'KX-',
        };
        
        $lastInvoice = self::where('invoice_type', $type)
            ->where('invoice_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            preg_match('/\d+/', $lastInvoice->invoice_number, $matches);
            $number = isset($matches[0]) ? intval($matches[0]) + 1 : 101;
        } else {
            $number = 101;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Convert number to words (Indian format)
     */
    public static function numberToWords($number)
    {
        $words = array(
            0 => '', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
            14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
            18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
            80 => 'Eighty', 90 => 'Ninety'
        );
        
        $rupees = floor($number);
        $paise = round(($number - $rupees) * 100);
        
        if ($rupees == 0) {
            $rupeesInWords = 'Zero';
        } else {
            $crore = floor($rupees / 10000000);
            $rupees %= 10000000;
            $lakh = floor($rupees / 100000);
            $rupees %= 100000;
            $thousand = floor($rupees / 1000);
            $rupees %= 1000;
            $hundred = floor($rupees / 100);
            $rupees %= 100;
            
            $rupeesInWords = '';
            
            if ($crore) {
                $rupeesInWords .= self::convertTwoDigits($crore, $words) . ' Crore ';
            }
            if ($lakh) {
                $rupeesInWords .= self::convertTwoDigits($lakh, $words) . ' Lakh ';
            }
            if ($thousand) {
                $rupeesInWords .= self::convertTwoDigits($thousand, $words) . ' Thousand ';
            }
            if ($hundred) {
                $rupeesInWords .= $words[$hundred] . ' Hundred ';
            }
            if ($rupees) {
                $rupeesInWords .= self::convertTwoDigits($rupees, $words);
            }
        }
        
        $result = 'Rupees ' . trim($rupeesInWords);
        
        if ($paise > 0) {
            $result .= ' and ' . self::convertTwoDigits($paise, $words) . ' Paise';
        }
        
        return $result . ' Only';
    }
    
    private static function convertTwoDigits($number, $words)
    {
        if ($number < 20) {
            return $words[$number];
        }
        
        $tens = floor($number / 10) * 10;
        $units = $number % 10;
        
        return $words[$tens] . ($units ? ' ' . $words[$units] : '');
    }
}
