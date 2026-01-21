<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalarySetting extends Model
{
    protected $table = 'salary_settings';

    protected $fillable = [
        'user_id',
        'base_salary',
        'late_penalty_per_mark',
        'half_day_deduction_percentage',
        'absent_deduction_percentage',
        'enable_deductions',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'late_penalty_per_mark' => 'decimal:2',
        'half_day_deduction_percentage' => 'decimal:2',
        'absent_deduction_percentage' => 'decimal:2',
        'enable_deductions' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
