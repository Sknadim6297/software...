<?php

namespace Database\Seeders;

use App\Models\Lead;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run()
    {
        // Incoming leads
        Lead::create([
            'type' => 'incoming',
            'date' => now()->subDays(2),
            'time' => now()->subDays(2)->format('H:i:s'),
            'platform' => 'facebook',
            'customer_name' => 'John Doe',
            'phone_number' => '9876543210',
            'email' => 'john.doe@example.com',
            'project_type' => 'web_development',
            'project_valuation' => 50000,
            'remarks' => 'Interested in e-commerce website',
            'status' => 'pending',
        ]);

        Lead::create([
            'type' => 'incoming',
            'date' => now()->subDays(1),
            'time' => now()->subDays(1)->format('H:i:s'),
            'platform' => 'google',
            'customer_name' => 'Jane Smith',
            'phone_number' => '9876543211',
            'email' => 'jane.smith@example.com',
            'project_type' => 'mobile_app',
            'project_valuation' => 75000,
            'remarks' => 'Need iOS and Android app',
            'status' => 'callback_scheduled',
            'callback_time' => now()->addHours(2),
        ]);

        // Outgoing leads
        Lead::create([
            'type' => 'outgoing',
            'date' => now()->subDays(3),
            'time' => now()->subDays(3)->format('H:i:s'),
            'platform' => 'cold_call',
            'customer_name' => 'Mike Johnson',
            'phone_number' => '9876543212',
            'email' => 'mike.johnson@example.com',
            'project_type' => 'digital_marketing',
            'project_valuation' => 25000,
            'remarks' => 'Need SEO services',
            'status' => 'interested',
            'assigned_to' => 1,
        ]);

        Lead::create([
            'type' => 'outgoing',
            'date' => now()->subDays(1),
            'time' => now()->subDays(1)->format('H:i:s'),
            'platform' => 'linkedin',
            'customer_name' => 'Sarah Wilson',
            'phone_number' => '9876543213',
            'email' => 'sarah.wilson@example.com',
            'project_type' => 'ui_ux_design',
            'project_valuation' => 40000,
            'remarks' => 'UI/UX redesign project',
            'status' => 'meeting_scheduled',
            'meeting_time' => now()->addDays(1),
            'meeting_address' => '123 Business Center, Mumbai',
            'meeting_person_name' => 'Sarah Wilson',
            'meeting_phone_number' => '9876543213',
            'meeting_summary' => 'Discuss UI/UX requirements and timeline',
            'assigned_to' => 2,
        ]);
    }
}