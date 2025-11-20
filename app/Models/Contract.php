<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'contract_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'contract_content',
        'project_type',
        'final_amount',
        'currency',
        'start_date',
        'expected_completion_date',
        'deliverables',
        'milestones',
        'payment_schedule',
        'status',
        'signed_at',
        'sent_to_customer_at',
        'sent_to_admin_at',
        'terms_and_conditions',
    ];

    protected $casts = [
        'final_amount' => 'decimal:2',
        'start_date' => 'date',
        'expected_completion_date' => 'date',
        'signed_at' => 'datetime',
        'sent_to_customer_at' => 'datetime',
        'sent_to_admin_at' => 'datetime',
    ];

    /**
     * Get the proposal that owns this contract
     */
    public function proposal()
    {
        return $this->belongsTo(Proposal::class);
    }

    /**
     * Get the invoices for this contract
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Generate unique contract number
     */
    public static function generateContractNumber()
    {
        $prefix = 'CNT';
        $year = date('Y');
        $month = date('m');
        
        $lastContract = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastContract ? (int)substr($lastContract->contract_number, -4) + 1 : 1;
        
        return sprintf('%s-%s%s-%04d', $prefix, $year, $month, $number);
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeColor()
    {
        return match($this->status) {
            'pending_signature' => 'warning',
            'active' => 'success',
            'completed' => 'info',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }
}
