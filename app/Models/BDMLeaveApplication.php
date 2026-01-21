<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class BDMLeaveApplication extends Model
{
    protected $table = 'bdm_leave_applications';
    
    protected $fillable = [
        'bdm_id',
        'leave_type',
        'leave_date',
        'from_date',
        'to_date',
        'number_of_days',
        'reason',
        'status',
        'admin_remarks',
        'applied_at',
        'admin_action_at',
        'is_editable',
    ];

    protected $casts = [
        'leave_date' => 'date',
        'from_date' => 'date',
        'to_date' => 'date',
        'applied_at' => 'datetime',
        'admin_action_at' => 'datetime',
        'is_editable' => 'boolean',
    ];

    public function bdm(): BelongsTo
    {
        return $this->belongsTo(BDM::class, 'bdm_id');
    }

    // Status checks
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

    // Status actions
    public function approve(string $remarks = null): void
    {
        $this->update([
            'status' => 'approved',
            'admin_remarks' => $remarks,
            'admin_action_at' => now(),
            'is_editable' => false,
        ]);
    }

    public function reject(string $remarks): void
    {
        $this->update([
            'status' => 'rejected',
            'admin_remarks' => $remarks,
            'admin_action_at' => now(),
            'is_editable' => false,
        ]);
    }

    // Calculate number of days between from_date and to_date
    public function calculateNumberOfDays(): int
    {
        if ($this->from_date && $this->to_date) {
            return $this->from_date->diffInDays($this->to_date) + 1; // +1 to include both start and end dates
        }
        return $this->number_of_days ?? 1;
    }

    // Format leave date range for display
    public function getDateRangeAttribute(): string
    {
        if ($this->from_date && $this->to_date) {
            if ($this->from_date->isSameDay($this->to_date)) {
                return $this->from_date->format('M d, Y');
            }
            return $this->from_date->format('M d') . ' - ' . $this->to_date->format('M d, Y');
        }
        return $this->leave_date?->format('M d, Y') ?? 'N/A';
    }

    // Get leave type label
    public function getLeaveTypeLabel(): string
    {
        return match($this->leave_type) {
            'casual' => 'Casual Leave (CL)',
            'sick' => 'Sick Leave (SL)',
            'unpaid' => 'Unpaid Leave',
            default => ucfirst($this->leave_type),
        };
    }

    // Get status badge color
    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'approved' => 'success',
            'rejected' => 'danger',
            'pending' => 'warning',
            default => 'secondary',
        };
    }
}
