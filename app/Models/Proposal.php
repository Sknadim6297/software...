<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'customer_id',
        'lead_type',
        'customer_name',
        'customer_email',
        'customer_phone',
        'project_type',
        'project_description',
        'proposal_content',
        'proposed_amount',
        'currency',
        'estimated_days',
        'deliverables',
        'payment_terms',
        'status',
        'rejection_reason',
        'sent_at',
        'viewed_at',
        'responded_at',
        'admin_notes',
        'metadata',
    ];

    protected $casts = [
        'proposed_amount' => 'decimal:2',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    /**
     * Get the lead associated with the proposal
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    /**
     * Get the customer associated with the proposal
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the contract generated from this proposal
     */
    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    /**
     * Check if proposal is accepted
     */
    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if proposal is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColor()
    {
        return match($this->status) {
            'draft' => 'secondary',
            'sent' => 'info',
            'viewed' => 'primary',
            'under_review' => 'warning',
            'accepted' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }
}
