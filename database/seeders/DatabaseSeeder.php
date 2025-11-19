<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@konnectix.com',
            'password' => bcrypt('password'),
        ]);

        // Create BDM user
        User::factory()->create([
            'name' => 'BDM User',
            'email' => 'bdm@konnectix.com', 
            'password' => bcrypt('password'),
        ]);

        // Seed leads data
        $this->call([
            LeadSeeder::class,
        ]);
    }
}
