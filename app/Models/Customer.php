<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'company_name',
        'number',
        'alternate_number',
        'email',
        'project_type',
        'project_valuation',
        'project_start_date',
        'payment_terms',
        'lead_source',
        'address',
        'gst_number',
        'state_code',
        'state_name',
        'remarks',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'project_valuation' => 'decimal:2',
        'project_start_date' => 'date',
    ];

    /**
     * Get the invoices for the customer.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
