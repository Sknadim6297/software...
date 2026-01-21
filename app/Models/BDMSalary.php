<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BDMSalary extends Model
{
    protected $table = 'bdm_salaries';
    
    protected $fillable = [
        'bdm_id',
        'month_year',
        'basic_salary',
        'hra',
        'other_allowances',
        'gross_salary',
        'deductions',
        'net_salary',
        'salary_slip_path',
        'remarks',
        'total_present_days',
        'casual_leave_taken',
        'sick_leave_taken',
        'unpaid_leave_taken',
        'per_day_salary',
        'leave_deduction',
        'attendance_notes',
        'is_regenerated',
        'generated_by',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'hra' => 'decimal:2',
        'other_allowances' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'per_day_salary' => 'decimal:2',
        'leave_deduction' => 'decimal:2',
        'total_present_days' => 'integer',
        'casual_leave_taken' => 'integer',
        'sick_leave_taken' => 'integer',
        'unpaid_leave_taken' => 'integer',
        'is_regenerated' => 'boolean',
    ];

    public function bdm(): BelongsTo
    {
        return $this->belongsTo(BDM::class, 'bdm_id');
    }

    /**
     * Get formatted month name
     */
    public function getFormattedMonthAttribute(): string
    {
        return \Carbon\Carbon::parse($this->month_year . '-01')->format('F Y');
    }

    /**
     * Get total leaves taken (approved only)
     */
    public function getTotalLeavesAttribute(): int
    {
        return ($this->casual_leave_taken ?? 0) + ($this->sick_leave_taken ?? 0) + ($this->unpaid_leave_taken ?? 0);
    }

    /**
     * Calculate leave deduction based on per day salary
     */
    public function calculateLeaveDeduction(): decimal
    {
        $totalLeaveDays = $this->total_leaves;
        return $totalLeaveDays * ($this->per_day_salary ?? 0);
    }

    /**
     * Regenerate salary slip (called when corrections are made)
     */
    public function regenerate(string $generatedBy = null): void
    {
        $this->update([
            'is_regenerated' => true,
            'generated_by' => $generatedBy ?? auth()->user()->email,
            'updated_at' => now(),
        ]);
    }

    /**
     * Get salary summary for the month
     */
    public function getSalarySummary(): array
    {
        return [
            'basic_salary' => $this->basic_salary,
            'hra' => $this->hra,
            'allowances' => $this->other_allowances,
            'gross' => $this->gross_salary,
            'deductions' => $this->deductions,
            'net' => $this->net_salary,
            'present_days' => $this->total_present_days,
            'leaves_taken' => $this->total_leaves,
            'leave_deduction' => $this->leave_deduction,
        ];
    }
}
