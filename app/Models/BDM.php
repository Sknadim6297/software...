<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class BDM extends Model
{
    protected $table = 'bdms';
    
    protected $fillable = [
        'user_id',
        'profile_image',
        'name',
        'father_name',
        'date_of_birth',
        'highest_education',
        'email',
        'phone',
        'employee_code',
        'designation',
        'joining_date',
        'current_ctc',
        'status',
        'warning_count',
        'last_warning_date',
        'termination_date',
        'termination_reason',
        'can_login',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'current_ctc' => 'decimal:2',
        'last_warning_date' => 'datetime',
        'termination_date' => 'datetime',
        'can_login' => 'boolean',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(BDMDocument::class, 'bdm_id');
    }

    public function salaries(): HasMany
    {
        return $this->hasMany(BDMSalary::class, 'bdm_id');
    }

    public function leaveBalance(): HasOne
    {
        return $this->hasOne(BDMLeaveBalance::class, 'bdm_id');
    }

    public function leaveApplications(): HasMany
    {
        return $this->hasMany(BDMLeaveApplication::class, 'bdm_id');
    }

    public function targets(): HasMany
    {
        return $this->hasMany(BDMTarget::class, 'bdm_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(BDMNotification::class, 'bdm_id');
    }

    // Business Logic Methods
    public function hasSixMonthsCompleted(): bool
    {
        return Carbon::parse($this->joining_date)->addMonths(6)->isPast();
    }

    public function isEligibleForLeaves(): bool
    {
        return $this->hasSixMonthsCompleted();
    }

    public function isTerminated(): bool
    {
        return $this->status === 'terminated';
    }

    public function isWarned(): bool
    {
        return $this->status === 'warned';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function terminate(string $reason): void
    {
        $this->update([
            'status' => 'terminated',
            'termination_date' => now(),
            'termination_reason' => $reason,
            'can_login' => false,
        ]);

        $this->createNotification('termination', 'Account Terminated', $reason);
    }

    public function revive(): void
    {
        $this->update([
            'status' => 'active',
            'warning_count' => 0,
            'last_warning_date' => null,
            'termination_date' => null,
            'termination_reason' => null,
            'can_login' => true,
        ]);

        $this->createNotification('general', 'Account Reactivated', 'Your account has been reactivated by admin.');
    }

    public function issueWarning(): void
    {
        $this->increment('warning_count');
        $this->update([
            'status' => 'warned',
            'last_warning_date' => now(),
        ]);
    }

    public function createNotification(string $type, string $title, string $message): void
    {
        $this->notifications()->create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
        ]);
    }

    public function currentMonthTarget()
    {
        return $this->targets()
            ->where('target_type', 'monthly')
            ->where('period', Carbon::now()->format('Y-m'))
            ->first();
    }

    public function previousMonthTarget()
    {
        return $this->targets()
            ->where('target_type', 'monthly')
            ->where('period', Carbon::now()->subMonth()->format('Y-m'))
            ->first();
    }
}
