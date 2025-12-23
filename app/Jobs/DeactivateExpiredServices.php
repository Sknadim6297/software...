<?php

namespace App\Jobs;

use App\Models\ServiceRenewal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class DeactivateExpiredServices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get all services that are overdue and auto_renewal is disabled
        $expiredServices = ServiceRenewal::where('renewal_date', '<', Carbon::now())
            ->where('service_status', 'Active')
            ->where('auto_renewal', false)
            ->get();

        foreach ($expiredServices as $service) {
            $service->update([
                'service_status' => 'Deactive',
                'stop_reason' => 'Service expired - auto renewal disabled',
            ]);

            \Log::info("Service #{$service->id} deactivated due to expiration");
        }

        // Also deactivate services that are 30+ days overdue even with auto_renewal
        $overdueServices = ServiceRenewal::where('renewal_date', '<', Carbon::now()->subDays(30))
            ->where('service_status', 'Active')
            ->get();

        foreach ($overdueServices as $service) {
            $service->update([
                'service_status' => 'Deactive',
                'auto_renewal' => false,
                'stop_reason' => 'Service expired - 30+ days overdue',
            ]);

            \Log::info("Service #{$service->id} deactivated due to being 30+ days overdue");
        }
    }
}
