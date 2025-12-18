<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class BDMTarget extends Model
{
    protected $table = 'bdm_targets';
    
    protected $fillable = [
        'bdm_id',
        'target_type',
        'period',
        'project_target',
        'revenue_target',
        'carried_forward_projects',
        'carried_forward_revenue',
        'total_project_target',
        'total_revenue_target',
        'projects_achieved',
        'revenue_achieved',
        'achievement_percentage',
        'target_met',
        'status',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'revenue_target' => 'decimal:2',
        'carried_forward_revenue' => 'decimal:2',
        'total_revenue_target' => 'decimal:2',
        'revenue_achieved' => 'decimal:2',
        'achievement_percentage' => 'decimal:2',
        'target_met' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function bdm(): BelongsTo
    {
        return $this->belongsTo(BDM::class, 'bdm_id');
    }

    public function calculateTotalTargets(): void
    {
        $this->total_project_target = $this->project_target + $this->carried_forward_projects;
        $this->total_revenue_target = $this->revenue_target + $this->carried_forward_revenue;
        $this->save();
    }

    public function calculateAchievement(): void
    {
        if ($this->total_project_target == 0 && $this->total_revenue_target == 0) {
            $this->achievement_percentage = 0;
            $this->target_met = false;
            $this->save();
            return;
        }

        $projectPercentage = $this->total_project_target > 0 
            ? ($this->projects_achieved / $this->total_project_target) * 100 
            : 0;

        $revenuePercentage = $this->total_revenue_target > 0 
            ? ($this->revenue_achieved / $this->total_revenue_target) * 100 
            : 0;

        $this->achievement_percentage = ($projectPercentage + $revenuePercentage) / 2;
        $this->target_met = $this->achievement_percentage >= 80;
        $this->save();
    }

    public function markCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }

    public function markFailed(): void
    {
        $this->update(['status' => 'failed']);
    }

    public function getFormattedPeriodAttribute(): string
    {
        if ($this->target_type === 'monthly') {
            return Carbon::parse($this->period . '-01')->format('F Y');
        } elseif ($this->target_type === 'quarterly') {
            return str_replace('-', ' ', $this->period);
        } else {
            return 'Year ' . $this->period;
        }
    }
}
