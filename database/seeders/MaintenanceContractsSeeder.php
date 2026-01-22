<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MaintenanceContractsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all()->take(5);

        foreach ($projects as $index => $project) {
            $project->update([
                'status' => 'Completed',
                'project_status' => 'Completed',
                'maintenance_enabled' => true,
                'maintenance_type' => $index % 2 === 0 ? 'Chargeable' : 'Free',
                'maintenance_months' => $index % 2 === 0 ? null : 12,
                'maintenance_charge' => $index % 2 === 0 ? (5000 + ($index * 1000)) : 0,
                'maintenance_billing_cycle' => $index % 3 === 0 ? 'Monthly' : ($index % 3 === 1 ? 'Quarterly' : 'Annually'),
                'maintenance_start_date' => Carbon::now()->subDays(random_int(10, 30)),
                'domain_name' => 'example' . ($index + 1) . '.com',
                'domain_purchase_date' => Carbon::now()->subMonths(12),
                'domain_amount' => 1200,
                'domain_renewal_cycle' => 'Yearly',
                'domain_renewal_date' => Carbon::now()->addMonths(6 + $index),
                'hosting_provider' => ['AWS', 'DigitalOcean', 'Linode', 'Heroku', 'Render'][$index] ?? 'AWS',
                'hosting_purchase_date' => Carbon::now()->subMonths(12),
                'hosting_amount' => 5000 + ($index * 1000),
                'hosting_renewal_cycle' => 'Yearly',
                'hosting_renewal_date' => Carbon::now()->addMonths(8 + $index),
            ]);
        }

        $this->command->info('Maintenance contracts seeded successfully!');
    }
}
