<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BDM;
use Illuminate\Support\Facades\Hash;

class CreateTestBDMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update test user with BDM profile
        $user = User::where('email', 'bdm@konnectix.com')->first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'Test BDM',
                'email' => 'bdm@konnectix.com',
                'password' => Hash::make('password'),
            ]);
        }

        // Create BDM profile if doesn't exist
        $bdm = BDM::where('user_id', $user->id)->first();
        
        if (!$bdm) {
            BDM::create([
                'user_id' => $user->id,
                'name' => 'Test BDM Employee',
                'father_name' => 'Test Father',
                'date_of_birth' => '1990-01-15',
                'highest_education' => 'Bachelor',
                'email' => 'bdm@konnectix.com',
                'phone' => '9876543210',
                'employee_code' => 'BDM0001',
                'joining_date' => now()->startOfMonth(),
                'current_ctc' => 50000,
                'status' => 'active',
                'can_login' => true,
            ]);
        }
    }
}

