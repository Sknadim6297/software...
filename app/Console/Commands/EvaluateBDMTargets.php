<?php

namespace App\Console\Commands;

use App\Models\BDM;
use App\Models\BDMTarget;
use App\Models\Contract;
use App\Mail\BDMWarningNotification;
use App\Mail\BDMTerminationNotification;
use App\Mail\BDMTargetFailureNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EvaluateBDMTargets extends Command
{
    protected $signature = 'bdm:evaluate-targets';
    protected $description = 'Evaluate monthly BDM targets and issue warnings/terminations';

    public function handle()
    {
        $this->info('Starting BDM target evaluation...');
        
        $previousMonth = Carbon::now()->subMonth();
        $previousMonthPeriod = $previousMonth->format('Y-m');
        
        // Get all active and warned BDMs
        $bdms = BDM::whereIn('status', ['active', 'warned'])->get();
        
        foreach ($bdms as $bdm) {
            $this->info("Evaluating BDM: {$bdm->name} ({$bdm->employee_code})");
            
            // Get previous month target
            $target = $bdm->targets()
                ->where('target_type', 'monthly')
                ->where('period', $previousMonthPeriod)
                ->first();
            
            if (!$target) {
                $this->warn("No target found for {$bdm->name} for period {$previousMonthPeriod}");
                continue;
            }
            
            // Calculate achievements from contracts (finalized in current month only)
            $projectsAchieved = Contract::where('bdm_id', $bdm->id)
                ->where('status', 'finalized')
                ->whereMonth('finalized_at', $previousMonth->month)
                ->whereYear('finalized_at', $previousMonth->year)
                ->count();
            
            $revenueAchieved = Contract::where('bdm_id', $bdm->id)
                ->where('status', 'finalized')
                ->whereMonth('finalized_at', $previousMonth->month)
                ->whereYear('finalized_at', $previousMonth->year)
                ->sum('total_amount');
            
            // Update target with achievements
            $target->projects_achieved = $projectsAchieved;
            $target->revenue_achieved = $revenueAchieved;
            $target->calculateAchievement();
            
            $achievementPercentage = $target->achievement_percentage;
            
            $this->info("Achievement: {$achievementPercentage}%");
            
            // Check if target met (80% threshold)
            if ($achievementPercentage >= 80) {
                $target->markCompleted();
                $this->info("✓ Target achieved!");
                
                // Reset warning count if target met
                if ($bdm->warning_count > 0) {
                    $bdm->update([
                        'warning_count' => 0,
                        'status' => 'active',
                    ]);
                    $this->info("Warnings reset for {$bdm->name}");
                }
                
                // Create next month target (no carry forward needed)
                $this->createNextMonthTarget($bdm, 0, 0);
            } else {
                $target->markFailed();
                $this->warn("✗ Target not met!");
                
                // Send target failure email
                Mail::to($bdm->email)->send(new BDMTargetFailureNotification($bdm, $target));
                
                // Issue warning
                $bdm->issueWarning();
                $newWarningCount = $bdm->warning_count;
                
                $this->warn("Warning issued! Total warnings: {$newWarningCount}");
                
                // Send warning email
                Mail::to($bdm->email)->send(new BDMWarningNotification($bdm, $newWarningCount, $previousMonthPeriod));
                
                // Create notification
                $bdm->createNotification(
                    'warning',
                    "Performance Warning #{$newWarningCount}",
                    "You did not meet your target for {$previousMonthPeriod}. Achievement: {$achievementPercentage}%. This is warning #{$newWarningCount} of 3."
                );
                
                // Check for termination (3 consecutive failures)
                if ($newWarningCount >= 3) {
                    $reason = "Terminated due to 3 consecutive months of target failure ({$previousMonth->subMonth(2)->format('Y-m')} to {$previousMonthPeriod})";
                    $bdm->terminate($reason);
                    
                    $this->error("✗✗✗ BDM TERMINATED: {$bdm->name}");
                    
                    // Send termination email
                    Mail::to($bdm->email)->send(new BDMTerminationNotification($bdm, $reason));
                } else {
                    // Calculate carry forward
                    $projectDeficit = max(0, $target->total_project_target - $projectsAchieved);
                    $revenueDeficit = max(0, $target->total_revenue_target - $revenueAchieved);
                    
                    // Create next month target with carry forward
                    $this->createNextMonthTarget($bdm, $projectDeficit, $revenueDeficit);
                }
            }
        }
        
        $this->info('BDM target evaluation completed!');
    }
    
    private function createNextMonthTarget(BDM $bdm, int $carriedProjects, float $carriedRevenue)
    {
        $currentMonth = Carbon::now();
        $currentMonthPeriod = $currentMonth->format('Y-m');
        
        // Check if target already exists
        $existingTarget = $bdm->targets()
            ->where('target_type', 'monthly')
            ->where('period', $currentMonthPeriod)
            ->first();
        
        if ($existingTarget) {
            $this->warn("Target already exists for {$currentMonthPeriod}");
            return;
        }
        
        // Create new target (base targets should be set by admin, here we just carry forward)
        $target = $bdm->targets()->create([
            'target_type' => 'monthly',
            'period' => $currentMonthPeriod,
            'project_target' => 0, // Admin should set base target
            'revenue_target' => 0, // Admin should set base target
            'carried_forward_projects' => $carriedProjects,
            'carried_forward_revenue' => $carriedRevenue,
            'total_project_target' => $carriedProjects,
            'total_revenue_target' => $carriedRevenue,
            'projects_achieved' => 0,
            'revenue_achieved' => 0,
            'achievement_percentage' => 0,
            'target_met' => false,
            'status' => 'pending',
            'start_date' => $currentMonth->startOfMonth(),
            'end_date' => $currentMonth->endOfMonth(),
        ]);
        
        $this->info("Next month target created with carry forward: Projects={$carriedProjects}, Revenue={$carriedRevenue}");
    }
}
