<?php

namespace Database\Seeders;

use App\Models\ServiceRenewal;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ServiceRenewalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        
        if ($customers->isEmpty()) {
            $this->command->warn('No customers found. Please seed customers first.');
            return;
        }

        $serviceTypes = [
            'Domain',
            'Server',
            'Digital Marketing',
            'Website Maintenance',
            'Application Maintenance',
            'Software Maintenance'
        ];

        $renewalTypes = ['Monthly', 'Quarterly', 'Yearly'];

        foreach ($customers->take(6) as $index => $customer) {
            for ($i = 0; $i < 2; $i++) {
                $serviceType = $serviceTypes[($index * 2 + $i) % count($serviceTypes)];
                $renewalType = $renewalTypes[$i % count($renewalTypes)];
                $startDate = Carbon::now()->subMonths(random_int(2, 12));
                
                // Calculate renewal date based on renewal type
                $renewalDate = match($renewalType) {
                    'Monthly' => $startDate->copy()->addMonth(),
                    'Quarterly' => $startDate->copy()->addMonths(3),
                    'Annually' => $startDate->copy()->addYear(),
                    default => $startDate->copy()->addMonth(),
                };

                // Make some overdue or due soon
                if ($i === 0) {
                    $renewalDate = Carbon::now()->subDays(random_int(1, 30)); // Overdue
                } elseif ($i === 1) {
                    $renewalDate = Carbon::now()->addDays(random_int(1, 7)); // Due soon
                }

                ServiceRenewal::create([
                    'customer_id' => $customer->id,
                    'service_type' => $serviceType,
                    'start_date' => $startDate,
                    'renewal_date' => $renewalDate,
                    'renewal_type' => $renewalType,
                    'amount' => random_int(5, 20) * 1000,
                    'service_status' => $i === 0 ? 'Active' : ($i % 2 === 0 ? 'Deactive' : 'Active'),
                    'auto_renewal' => $i === 0 ? true : false,
                    'renewal_mail_sent' => true,
                    'last_renewal_mail_sent_at' => Carbon::now()->subDays(random_int(1, 15)),
                ]);
            }
        }

        $this->command->info('Service renewals seeded successfully!');
    }
}
