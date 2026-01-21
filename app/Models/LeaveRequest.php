<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class LeaveRequest extends Model
{
    protected $table = 'leave_requests';

    protected $fillable = [
        'user_id',
        'from_date',
        'to_date',
        'leave_type',
        'status',
        'reason',
        'admin_notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Get leave days count (excluding weekends/holidays)
    public function getLeaveDaysCount()
    {
        if ($this->leave_type === 'half_day') {
            return 0.5;
        }

        $days = 0;
        $current = $this->from_date->copy();

        while ($current <= $this->to_date) {
            // Skip weekends (Saturday=6, Sunday=0)
            if (!in_array($current->dayOfWeek, [0, 6])) {
                // Skip holidays
                if (!Holiday::where('holiday_date', $current)->exists()) {
                    $days++;
                }
            }
            $current->addDay();
        }

        return $days;
    }

    // Check if leave dates overlap
    public static function hasOverlap($userId, $fromDate, $toDate, $excludeId = null)
    {
        $query = self::where('user_id', $userId)
            ->whereIn('status', ['approved', 'pending'])
            ->where(function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('from_date', [$fromDate, $toDate])
                    ->orWhereBetween('to_date', [$fromDate, $toDate])
                    ->orWhere(function ($q2) use ($fromDate, $toDate) {
                        $q2->where('from_date', '<=', $fromDate)
                            ->where('to_date', '>=', $toDate);
                    });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
