<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\BDM;
use App\Models\BDMLeaveBalance;
use Carbon\Carbon;

class BDMSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first user (admin)
        $user = User::first();
        
        if (!$user) {
            $this->command->error('No users found. Please create a user first.');
            return;
        }
        
        // Check if BDM already exists
        if ($user->bdm) {
            $this->command->info('BDM profile already exists for this user.');
            return;
        }
        
        // Create BDM profile
        $bdm = BDM::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'father_name' => 'Father Name',
            'date_of_birth' => '1990-01-01',
            'highest_education' => 'Bachelor of Business Administration',
            'email' => $user->email,
            'phone' => '9876543210',
            'employee_code' => 'BDM001',
            'joining_date' => Carbon::now()->subMonths(7), // 7 months ago (eligible for leaves)
            'current_ctc' => 500000.00,
            'status' => 'active',
            'warning_count' => 0,
            'can_login' => true,
        ]);
        
        // Create leave balance (6 months completed, so eligible for CL/SL)
        BDMLeaveBalance::create([
            'bdm_id' => $bdm->id,
            'casual_leave_balance' => 6,
            'sick_leave_balance' => 6,
            'casual_leave_used_this_month' => 0,
            'sick_leave_used_this_month' => 0,
            'current_month' => Carbon::now()->format('Y-m'),
        ]);
        
        $this->command->info('✓ BDM Profile created successfully!');
        $this->command->info('  Name: ' . $bdm->name);
        $this->command->info('  Employee Code: ' . $bdm->employee_code);
        $this->command->info('  Email: ' . $bdm->email);
        $this->command->info('  Leave Balance: 6 CL + 6 SL');
    }
}
