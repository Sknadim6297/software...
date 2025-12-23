<?php

namespace Database\Seeders;

use App\Models\Lead;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Faker\Factory as Faker;

class IncomingLeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('en_IN');
        
        // Define project types with sample data
        $projectTypes = [
            [
                'type' => 'web_development',
                'name' => 'Tech Solutions Ltd.',
                'valuation' => 8000,
                'platform' => 'facebook',
                'status' => 'interested'
            ],
            [
                'type' => 'mobile_app',
                'name' => 'Retail Innovations',
                'valuation' => 15000,
                'platform' => 'google',
                'status' => 'interested'
            ],
            [
                'type' => 'software_development',
                'name' => 'Enterprise Solutions',
                'valuation' => 50000,
                'platform' => 'justdial',
                'status' => 'interested'
            ],
            [
                'type' => 'ui_ux_design',
                'name' => 'Design Studio Pro',
                'valuation' => 12000,
                'platform' => 'facebook',
                'status' => 'interested'
            ],
            [
                'type' => 'social_media_marketing',
                'name' => 'Fashion Boutique',
                'valuation' => 19000,
                'platform' => 'other',
                'platform_custom' => 'WhatsApp',
                'status' => 'interested'
            ],
            [
                'type' => 'youtube_marketing',
                'name' => 'Content Creators Hub',
                'valuation' => 25000,
                'platform' => 'other',
                'platform_custom' => 'YouTube',
                'status' => 'interested'
            ],
            [
                'type' => 'graphic_designing',
                'name' => 'Marketing Agency',
                'valuation' => 5000,
                'platform' => 'google',
                'status' => 'interested'
            ],
            [
                'type' => 'reels_design',
                'name' => 'Digital Media Co.',
                'valuation' => 8000,
                'platform' => 'facebook',
                'status' => 'interested'
            ],
            [
                'type' => 'ecommerce_development',
                'name' => 'Online Store Plus',
                'valuation' => 35000,
                'platform' => 'google',
                'status' => 'interested'
            ],
        ];

        // Add one lead per project type
        foreach ($projectTypes as $projectData) {
            // Check if lead already exists to avoid duplicates
            $exists = Lead::where('customer_name', $projectData['name'])
                ->where('project_type', $projectData['type'])
                ->exists();

            if (!$exists) {
                Lead::create([
                    'type' => 'incoming',
                    'customer_name' => $projectData['name'],
                    'email' => strtolower(str_replace(' ', '', $projectData['name'])) . '@example.com',
                    'phone_number' => $faker->numerify('+91 ######### '),
                    'platform' => $projectData['platform'],
                    'platform_custom' => null,
                    'date' => now()->subDays(rand(1, 7)),
                    'time' => now()->subHours(rand(1, 24)),
                    'project_type' => $projectData['type'],
                    'project_valuation' => $projectData['valuation'],
                    'status' => $projectData['status'],
                    'remarks' => 'Sample lead - ' . $projectData['type'],
                    'call_notes' => 'Interested in our services',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $this->command->info('✅ Incoming leads seeded successfully! One lead for each project type.');
    }
}
