<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        
        // Evaluate BDM targets on the 1st of each month at 2 AM (for previous month)
        $schedule->command('bdm:evaluate-targets')->monthlyOn(1, '02:00');
        
        // Send renewal reminders daily at 9 AM
        $schedule->job(new \App\Jobs\SendRenewalReminders)->dailyAt('09:00');
        
        // Check and deactivate expired services daily at 1 AM
        $schedule->job(new \App\Jobs\DeactivateExpiredServices)->dailyAt('01:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

