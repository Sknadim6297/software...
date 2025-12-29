<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'customer_name',
        'customer_mobile',
        'customer_email',
        'project_name',
        'project_type',
        'project_start_date',
        'start_date', // Keep for backwards compatibility
        'project_valuation',
        'upfront_payment',
        'first_installment',
        'second_installment',
        'third_installment',
        'upfront_paid',
        'upfront_paid_date',
        'first_installment_paid',
        'first_installment_paid_date',
        'second_installment_paid',
        'second_installment_paid_date',
        'third_installment_paid',
        'third_installment_paid_date',
        'project_coordinator',
        'project_coordinator_id',
        'bdm_id',
        'status',
        'project_status',
        'current_installment',
        'domain_name',
        'domain_purchase_date',
        'domain_amount',
        'domain_renewal_cycle',
        'domain_renewal_date',
        'hosting_provider',
        'hosting_purchase_date',
        'hosting_amount',
        'hosting_renewal_cycle',
        'hosting_renewal_date',
        'maintenance_enabled',
        'maintenance_type',
        'maintenance_months',
        'maintenance_charge',
        'maintenance_billing_cycle',
        'maintenance_start_date',
        'maintenance_contract_id',
        'notes',
        'payment_invoices'
    ];

    protected $casts = [
        'start_date' => 'date',
        'project_start_date' => 'date',
        'upfront_paid' => 'boolean',
        'first_installment_paid' => 'boolean',
        'second_installment_paid' => 'boolean',
        'third_installment_paid' => 'boolean',
        'upfront_paid_date' => 'date',
        'first_installment_paid_date' => 'date',
        'second_installment_paid_date' => 'date',
        'third_installment_paid_date' => 'date',
        'domain_purchase_date' => 'date',
        'domain_renewal_date' => 'date',
        'hosting_purchase_date' => 'date',
        'hosting_renewal_date' => 'date',
        'maintenance_start_date' => 'date',
        'maintenance_enabled' => 'boolean',
        'payment_invoices' => 'array'
    ];

    /**
     * Get the customer that owns the project.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the project coordinator.
     */
    public function coordinator()
    {
        return $this->belongsTo(User::class, 'project_coordinator_id');
    }

    /**
     * Get the BDM who created the project.
     */
    public function bdm()
    {
        return $this->belongsTo(BDM::class);
    }

    /**
     * Get the maintenance contract for the project.
     */
    public function maintenanceContract()
    {
        return $this->belongsTo(Contract::class, 'maintenance_contract_id');
    }

    /**
     * Get all invoices for the project.
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Accessor Methods
     */
    public function getTotalPaidAttribute()
    {
        $total = 0;
        if ($this->upfront_paid) $total += $this->upfront_payment;
        if ($this->first_installment_paid) $total += $this->first_installment;
        if ($this->second_installment_paid) $total += $this->second_installment;
        if ($this->third_installment_paid) $total += $this->third_installment;
        return $total;
    }

    public function getRemainingAmountAttribute()
    {
        return $this->project_valuation - $this->total_paid;
    }

    public function getPaymentProgressAttribute()
    {
        if ($this->project_valuation == 0) return 0;
        return round(($this->total_paid / $this->project_valuation) * 100, 2);
    }

    public function getNextPendingInstallmentAttribute()
    {
        if (!$this->upfront_paid && $this->upfront_payment > 0) {
            return ['type' => 'upfront', 'amount' => $this->upfront_payment, 'label' => 'Upfront Payment'];
        }
        if (!$this->first_installment_paid && $this->first_installment > 0) {
            return ['type' => 'first', 'amount' => $this->first_installment, 'label' => 'First Installment'];
        }
        if (!$this->second_installment_paid && $this->second_installment > 0) {
            return ['type' => 'second', 'amount' => $this->second_installment, 'label' => 'Second Installment'];
        }
        if (!$this->third_installment_paid && $this->third_installment > 0) {
            return ['type' => 'third', 'amount' => $this->third_installment, 'label' => 'Third Installment'];
        }
        return null;
    }

    public function getIsFullyPaidAttribute()
    {
        return $this->remaining_amount == 0;
    }

    /**
     * Get the next installment to be paid.
     */
    public function getNextInstallment()
    {
        if (!$this->upfront_paid && $this->upfront_payment > 0) {
            return ['type' => 'upfront', 'amount' => $this->upfront_payment, 'label' => 'Upfront Payment'];
        }
        if (!$this->first_installment_paid && $this->first_installment > 0) {
            return ['type' => 'first', 'amount' => $this->first_installment, 'label' => 'First Installment'];
        }
        if (!$this->second_installment_paid && $this->second_installment > 0) {
            return ['type' => 'second', 'amount' => $this->second_installment, 'label' => 'Second Installment'];
        }
        if (!$this->third_installment_paid && $this->third_installment > 0) {
            return ['type' => 'third', 'amount' => $this->third_installment, 'label' => 'Third Installment'];
        }
        return null;
    }

    /**
     * Check if all installments are paid.
     */
    public function areAllInstallmentsPaid()
    {
        return $this->is_fully_paid;
    }

    /**
     * Get total amount paid so far.
     */
    public function getTotalPaid()
    {
        return $this->total_paid;
    }

    /**
     * Scopes
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'In Progress')->orWhere('project_status', 'In Progress');
    }

    /**
     * Scope a query to only include completed projects.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed')->orWhere('project_status', 'Completed');
    }

    /**
     * Scope by BDM
     */
    public function scopeByBdm($query, $bdmId)
    {
        return $query->where('bdm_id', $bdmId);
    }
}
