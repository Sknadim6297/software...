<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * DEPRECATED: This model is for the old salaries table that has been removed.
 * Use BDMSalary model instead for BDM salary management.
 * This class is kept for backwards compatibility but should not be used.
 */
class Salary extends Model
{
    // Old table - no longer exists, kept for compatibility
    protected $table = 'bdm_salaries';

    protected $fillable = [
        'user_id',
        'base_salary',
        'year',
        'month',
        'working_days',
        'present_days',
        'absent_days',
        'half_days',
        'late_count',
        'approved_leaves',
        'daily_rate',
        'late_deduction',
        'half_day_deduction',
        'absent_deduction',
        'other_deductions',
        'gross_salary',
        'total_deductions',
        'net_salary',
        'deduction_details',
        'payslip_file',
        'is_processed',
        'processed_at',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'late_deduction' => 'decimal:2',
        'half_day_deduction' => 'decimal:2',
        'absent_deduction' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'is_processed' => 'boolean',
        'processed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Calculate salary
    public function calculateSalary()
    {
        $settings = SalarySetting::where('user_id', $this->user_id)->first();
        
        // Calculate daily rate
        $this->daily_rate = $this->base_salary / 30; // Assuming 30 working days

        // Calculate deductions
        $this->late_deduction = $this->late_count * ($settings->late_penalty_per_mark ?? 0);
        $this->half_day_deduction = $this->half_days * ($this->daily_rate * ($settings->half_day_deduction_percentage ?? 50) / 100);
        $this->absent_deduction = $this->absent_days * ($this->daily_rate * ($settings->absent_deduction_percentage ?? 100) / 100);

        // Calculate gross and net
        $this->gross_salary = $this->base_salary;
        $this->total_deductions = $this->late_deduction + $this->half_day_deduction + $this->absent_deduction + ($this->other_deductions ?? 0);
        $this->net_salary = $this->gross_salary - $this->total_deductions;

        // Store deduction details as JSON
        $this->deduction_details = json_encode([
            'late_deduction' => $this->late_deduction,
            'half_day_deduction' => $this->half_day_deduction,
            'absent_deduction' => $this->absent_deduction,
            'other_deductions' => $this->other_deductions ?? 0,
        ]);

        return $this;
    }

    // Mark as processed
    public function markAsProcessed()
    {
        $this->update([
            'is_processed' => true,
            'processed_at' => now(),
        ]);
    }
}
