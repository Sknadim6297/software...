<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'project_name',
        'project_type',
        'start_date',
        'project_valuation',
        'upfront_payment',
        'first_installment',
        'second_installment',
        'third_installment',
        'project_coordinator_id',
        'project_status',
        'upfront_paid',
        'first_installment_paid',
        'second_installment_paid',
        'third_installment_paid',
        'current_installment',
    ];

    protected $casts = [
        'start_date' => 'date',
        'upfront_paid' => 'boolean',
        'first_installment_paid' => 'boolean',
        'second_installment_paid' => 'boolean',
        'third_installment_paid' => 'boolean',
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
     * Get the installments for the project.
     */
    public function installments()
    {
        return $this->hasMany(ProjectInstallment::class);
    }

    /**
     * Get the maintenance contract for the project.
     */
    public function maintenanceContract()
    {
        return $this->hasOne(MaintenanceContract::class);
    }

    /**
     * Get the next installment to be paid.
     */
    public function getNextInstallment()
    {
        if (!$this->upfront_paid && $this->upfront_payment > 0) {
            return ['type' => 'Upfront', 'amount' => $this->upfront_payment];
        }
        if (!$this->first_installment_paid && $this->first_installment > 0) {
            return ['type' => 'First', 'amount' => $this->first_installment];
        }
        if (!$this->second_installment_paid && $this->second_installment > 0) {
            return ['type' => 'Second', 'amount' => $this->second_installment];
        }
        if (!$this->third_installment_paid && $this->third_installment > 0) {
            return ['type' => 'Third', 'amount' => $this->third_installment];
        }
        return null;
    }

    /**
     * Check if all installments are paid.
     */
    public function areAllInstallmentsPaid()
    {
        $allPaid = true;
        
        if ($this->upfront_payment > 0 && !$this->upfront_paid) $allPaid = false;
        if ($this->first_installment > 0 && !$this->first_installment_paid) $allPaid = false;
        if ($this->second_installment > 0 && !$this->second_installment_paid) $allPaid = false;
        if ($this->third_installment > 0 && !$this->third_installment_paid) $allPaid = false;
        
        return $allPaid;
    }

    /**
     * Get total amount paid so far.
     */
    public function getTotalPaid()
    {
        $total = 0;
        if ($this->upfront_paid) $total += $this->upfront_payment;
        if ($this->first_installment_paid) $total += $this->first_installment;
        if ($this->second_installment_paid) $total += $this->second_installment;
        if ($this->third_installment_paid) $total += $this->third_installment;
        return $total;
    }

    /**
     * Scope a query to only include in-progress projects.
     */
    public function scopeInProgress($query)
    {
        return $query->where('project_status', 'In Progress');
    }

    /**
     * Scope a query to only include completed projects.
     */
    public function scopeCompleted($query)
    {
        return $query->where('project_status', 'Completed');
    }
}
