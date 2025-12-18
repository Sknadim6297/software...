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
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'hra' => 'decimal:2',
        'other_allowances' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function bdm(): BelongsTo
    {
        return $this->belongsTo(BDM::class, 'bdm_id');
    }

    public function getFormattedMonthAttribute(): string
    {
        return \Carbon\Carbon::parse($this->month_year . '-01')->format('F Y');
    }
}
