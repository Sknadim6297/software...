<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MaintenanceContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'customer_id',
        'contract_type',
        'free_months',
        'charges',
        'charge_frequency',
        'domain_purchase_date',
        'domain_amount',
        'domain_renewal_date',
        'hosting_purchase_date',
        'hosting_amount',
        'hosting_renewal_date',
        'contract_start_date',
        'contract_end_date',
        'status',
        'invoice_id',
    ];

    protected $casts = [
        'domain_purchase_date' => 'date',
        'domain_renewal_date' => 'date',
        'hosting_purchase_date' => 'date',
        'hosting_renewal_date' => 'date',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
    ];

    /**
     * Get the project that owns the maintenance contract.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the customer that owns the maintenance contract.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the invoice for the maintenance contract.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Check if contract is expired.
     */
    public function isExpired()
    {
        if ($this->contract_end_date) {
            return $this->contract_end_date < Carbon::now();
        }
        return false;
    }

    /**
     * Check if contract is active.
     */
    public function isActive()
    {
        return $this->status === 'Active' && !$this->isExpired();
    }

    /**
     * Scope a query to only include active contracts.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }
}
