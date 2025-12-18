<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BDMLeaveApplication extends Model
{
    protected $table = 'bdm_leave_applications';
    
    protected $fillable = [
        'bdm_id',
        'leave_type',
        'leave_date',
        'reason',
        'status',
        'admin_remarks',
        'applied_at',
        'admin_action_at',
    ];

    protected $casts = [
        'leave_date' => 'date',
        'applied_at' => 'datetime',
        'admin_action_at' => 'datetime',
    ];

    public function bdm(): BelongsTo
    {
        return $this->belongsTo(BDM::class, 'bdm_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function approve(string $remarks = null): void
    {
        $this->update([
            'status' => 'approved',
            'admin_remarks' => $remarks,
            'admin_action_at' => now(),
        ]);
    }

    public function reject(string $remarks): void
    {
        $this->update([
            'status' => 'rejected',
            'admin_remarks' => $remarks,
            'admin_action_at' => now(),
        ]);
    }
}
