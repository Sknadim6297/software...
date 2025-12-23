<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ServiceRenewal extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'service_type',
        'start_date',
        'renewal_date',
        'renewal_type',
        'amount',
        'service_status',
        'transaction_id',
        'stop_reason',
        'auto_renewal',
        'renewal_mail_sent',
        'last_renewal_mail_sent_at',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'renewal_date' => 'date',
        'auto_renewal' => 'boolean',
        'renewal_mail_sent' => 'boolean',
        'last_renewal_mail_sent_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the customer that owns the service renewal.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the user who verified the renewal.
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Check if renewal is due soon (within 7 days).
     */
    public function isDueSoon()
    {
        return $this->renewal_date->diffInDays(Carbon::now()) <= 7 && $this->renewal_date >= Carbon::now();
    }

    /**
     * Check if renewal is overdue.
     */
    public function isOverdue()
    {
        return $this->renewal_date < Carbon::now();
    }

    /**
     * Calculate next renewal date based on renewal type.
     */
    public function calculateNextRenewalDate()
    {
        $currentRenewalDate = Carbon::parse($this->renewal_date);
        
        switch ($this->renewal_type) {
            case 'Monthly':
                return $currentRenewalDate->addMonth();
            case 'Quarterly':
                return $currentRenewalDate->addMonths(3);
            case 'Yearly':
                return $currentRenewalDate->addYear();
            default:
                return $currentRenewalDate->addMonth();
        }
    }

    /**
     * Scope a query to only include active services.
     */
    public function scopeActive($query)
    {
        return $query->where('service_status', 'Active');
    }

    /**
     * Scope a query to only include services due for renewal.
     */
    public function scopeDueForRenewal($query)
    {
        return $query->where('renewal_date', '<=', Carbon::now()->addDays(7))
                     ->where('service_status', 'Active')
                     ->where('auto_renewal', true);
    }
}
