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
        'casual_leave_balance',
        'sick_leave_balance',
        'casual_leave_used_this_month',
        'sick_leave_used_this_month',
        'current_month',
    ];

    public function bdm(): BelongsTo
    {
        return $this->belongsTo(BDM::class, 'bdm_id');
    }

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

    public function canTakeCasualLeave(): bool
    {
        $this->resetMonthlyUsage();
        return $this->casual_leave_balance > 0 && $this->casual_leave_used_this_month < 1;
    }

    public function canTakeSickLeave(): bool
    {
        $this->resetMonthlyUsage();
        return $this->sick_leave_balance > 0 && $this->sick_leave_used_this_month < 1;
    }

    public function deductCasualLeave(): void
    {
        $this->decrement('casual_leave_balance');
        $this->increment('casual_leave_used_this_month');
    }

    public function deductSickLeave(): void
    {
        $this->decrement('sick_leave_balance');
        $this->increment('sick_leave_used_this_month');
    }
}
