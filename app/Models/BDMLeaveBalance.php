<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class BDMLeaveBalance extends Model
{
    protected $table = 'bdm_leave_balances';
    
    protected $fillable = [
        'bdm_id',
        'casual_leave',
        'sick_leave',
        'unpaid_leave',
        'casual_leave_balance',
        'sick_leave_balance',
        'casual_leave_allocated',
        'sick_leave_allocated',
        'casual_leave_used_this_month',
        'sick_leave_used_this_month',
        'current_month',
        'year_month',
    ];

    protected $casts = [
        'casual_leave' => 'integer',
        'sick_leave' => 'integer',
        'unpaid_leave' => 'integer',
        'casual_leave_balance' => 'integer',
        'sick_leave_balance' => 'integer',
        'casual_leave_allocated' => 'integer',
        'sick_leave_allocated' => 'integer',
        'casual_leave_used_this_month' => 'integer',
        'sick_leave_used_this_month' => 'integer',
    ];

    public function bdm(): BelongsTo
    {
        return $this->belongsTo(BDM::class, 'bdm_id');
    }

    /**
     * Get casual leave remaining for the year
     */
    public function getCasualLeaveRemaining(): int
    {
        return $this->casual_leave_balance ?? 0;
    }

    /**
     * Get sick leave remaining for the year
     */
    public function getSickLeaveRemaining(): int
    {
        return $this->sick_leave_balance ?? 0;
    }

    /**
     * Get total leaves taken (approved only)
     */
    public function getTotalLeavesTaken(): int
    {
        return ($this->casual_leave_allocated ?? 0) - ($this->casual_leave_balance ?? 0) +
               ($this->sick_leave_allocated ?? 0) - ($this->sick_leave_balance ?? 0);
    }

    /**
     * Reset monthly usage when month changes
     */
    public function resetMonthlyUsage(): void
    {
        $currentMonth = Carbon::now()->format('Y-m');
        
        if ($this->current_month !== $currentMonth) {
            $this->update([
                'casual_leave_used_this_month' => 0,
                'sick_leave_used_this_month' => 0,
                'current_month' => $currentMonth,
            ]);
        }
    }

    /**
     * Update leave balance after approval
     */
    public function updateAfterApprovedLeave(string $leaveType, int $days = 1): void
    {
        if ($leaveType === 'casual') {
            $this->decrement('casual_leave_balance', $days);
        } elseif ($leaveType === 'sick') {
            $this->decrement('sick_leave_balance', $days);
        }
    }

    /**
     * Update leave balance when leave is rejected
     */
    public function revertRejectedLeave(string $leaveType, int $days = 1): void
    {
        if ($leaveType === 'casual') {
            $this->increment('casual_leave_balance', $days);
        } elseif ($leaveType === 'sick') {
            $this->increment('sick_leave_balance', $days);
        }
    }

    /**
     * Set leave allocation for the year
     */
    public function setAllocation(int $casualLeaves, int $sickLeaves): void
    {
        $this->update([
            'casual_leave_allocated' => $casualLeaves,
            'casual_leave_balance' => $casualLeaves,
            'sick_leave_allocated' => $sickLeaves,
            'sick_leave_balance' => $sickLeaves,
        ]);
    }
}
