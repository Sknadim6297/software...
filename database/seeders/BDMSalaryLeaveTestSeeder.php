<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BDM;
use App\Models\User;
use App\Models\BDMLeaveBalance;
use App\Models\BDMLeaveApplication;
use App\Models\BDMSalary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class BDMSalaryLeaveTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 test BDMs
        $bdms = [];
        $names = [
            ['name' => 'Rajesh Kumar', 'email' => 'rajesh.kumar@konnectix.com', 'phone' => '9876543210'],
            ['name' => 'Priya Sharma', 'email' => 'priya.sharma@konnectix.com', 'phone' => '9876543211'],
            ['name' => 'Amit Patel', 'email' => 'amit.patel@konnectix.com', 'phone' => '9876543212'],
            ['name' => 'Sneha Reddy', 'email' => 'sneha.reddy@konnectix.com', 'phone' => '9876543213'],
            ['name' => 'Vikram Singh', 'email' => 'vikram.singh@konnectix.com', 'phone' => '9876543214'],
            ['name' => 'Kavita Desai', 'email' => 'kavita.desai@konnectix.com', 'phone' => '9876543215'],
            ['name' => 'Arjun Mehta', 'email' => 'arjun.mehta@konnectix.com', 'phone' => '9876543216'],
            ['name' => 'Neha Gupta', 'email' => 'neha.gupta@konnectix.com', 'phone' => '9876543217'],
            ['name' => 'Rahul Joshi', 'email' => 'rahul.joshi@konnectix.com', 'phone' => '9876543218'],
            ['name' => 'Pooja Verma', 'email' => 'pooja.verma@konnectix.com', 'phone' => '9876543219'],
        ];

        $salaries = [25000, 30000, 35000, 28000, 32000, 27000, 33000, 29000, 31000, 26000];

        foreach ($names as $index => $data) {
            // Skip if email already exists
            if (User::where('email', $data['email'])->exists()) {
                $this->command->warn('Skipping ' . $data['email'] . ' - already exists');
                $existingUser = User::where('email', $data['email'])->first();
                $existingBdm = BDM::where('user_id', $existingUser->id)->first();
                if ($existingBdm) {
                    $bdms[] = $existingBdm;
                }
                continue;
            }

            // Create user for authentication
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password123'),
            ]);

            // Create BDM profile
            $bdm = BDM::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'father_name' => 'Test Father',
                'date_of_birth' => Carbon::now()->subYears(rand(25, 40)),
                'highest_education' => 'Graduate',
                'email' => $data['email'],
                'phone' => $data['phone'],
                'employee_code' => 'BDM' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'joining_date' => Carbon::now()->subMonths(rand(6, 24)),
                'current_ctc' => $salaries[$index],
                'can_login' => true,
                'status' => 'active',
            ]);

            $bdms[] = $bdm;

            // Create leave balance
            BDMLeaveBalance::create([
                'bdm_id' => $bdm->id,
                'casual_leave_allocated' => 12,
                'casual_leave_balance' => rand(8, 12),
                'sick_leave_allocated' => 12,
                'sick_leave_balance' => rand(8, 12),
                'year_month' => Carbon::now()->format('Y-m'),
            ]);
        }

        // Create leave applications for current month
        $leaveTypes = ['casual', 'sick', 'unpaid'];
        $statuses = ['pending', 'approved', 'rejected'];
        
        foreach ($bdms as $bdm) {
            // Create 2-4 leave applications per BDM
            $numLeaves = rand(2, 4);
            
            for ($i = 0; $i < $numLeaves; $i++) {
                $leaveType = $leaveTypes[array_rand($leaveTypes)];
                $status = $statuses[array_rand($statuses)];
                
                // Random date in current or last month
                $fromDate = Carbon::now()->subDays(rand(1, 45));
                $numberOfDays = rand(1, 5);
                $toDate = $fromDate->copy()->addDays($numberOfDays - 1);
                
                $leave = BDMLeaveApplication::create([
                    'bdm_id' => $bdm->id,
                    'leave_type' => $leaveType,
                    'from_date' => $fromDate,
                    'to_date' => $toDate,
                    'number_of_days' => $numberOfDays,
                    'reason' => 'Test leave application - ' . ucfirst($leaveType) . ' leave needed',
                    'status' => $status,
                    'applied_at' => $fromDate->copy()->subDays(rand(1, 7)),
                ]);

                // Add approval/rejection details
                if ($status === 'approved') {
                    $leave->update([
                        'admin_action_at' => $fromDate->copy()->subDays(rand(1, 3)),
                        'admin_remarks' => 'Approved - Valid reason',
                    ]);
                } elseif ($status === 'rejected') {
                    $leave->update([
                        'admin_action_at' => $fromDate->copy()->subDays(rand(1, 3)),
                        'admin_remarks' => 'Rejected - Insufficient information',
                    ]);
                }
            }
        }

        // Generate salary records for last 3 months
        $months = [
            Carbon::now()->subMonths(2)->format('Y-m'), // 2 months ago
            Carbon::now()->subMonths(1)->format('Y-m'), // Last month
            Carbon::now()->format('Y-m'), // Current month
        ];

        foreach ($months as $monthYear) {
            $monthDate = Carbon::parse($monthYear . '-01');
            
            foreach ($bdms as $bdm) {
                // Get approved leaves for this month
                $casualLeaves = BDMLeaveApplication::where('bdm_id', $bdm->id)
                    ->where('status', 'approved')
                    ->where('leave_type', 'casual')
                    ->whereYear('from_date', $monthDate->year)
                    ->whereMonth('from_date', $monthDate->month)
                    ->sum('number_of_days');

                $sickLeaves = BDMLeaveApplication::where('bdm_id', $bdm->id)
                    ->where('status', 'approved')
                    ->where('leave_type', 'sick')
                    ->whereYear('from_date', $monthDate->year)
                    ->whereMonth('from_date', $monthDate->month)
                    ->sum('number_of_days');

                $unpaidLeaves = BDMLeaveApplication::where('bdm_id', $bdm->id)
                    ->where('status', 'approved')
                    ->where('leave_type', 'unpaid')
                    ->whereYear('from_date', $monthDate->year)
                    ->whereMonth('from_date', $monthDate->month)
                    ->sum('number_of_days');

                // Calculate salary components
                $basicSalary = $bdm->current_ctc;
                $hra = $basicSalary * 0.40; // 40% HRA
                $otherAllowances = $basicSalary * 0.10; // 10% Other allowances
                $grossSalary = $basicSalary + $hra + $otherAllowances;
                $perDaySalary = $grossSalary / 30;

                // Calculate present days
                $totalDays = $monthDate->daysInMonth;
                $totalLeaves = $casualLeaves + $sickLeaves + $unpaidLeaves;
                $presentDays = $totalDays - $totalLeaves;

                // Calculate leave deduction (only unpaid leaves)
                $leaveDeduction = $unpaidLeaves * $perDaySalary;
                
                // Random other deductions (PF, etc.)
                $otherDeductions = rand(0, 1) ? rand(500, 1500) : 0;
                
                // Net salary
                $netSalary = $grossSalary - $leaveDeduction - $otherDeductions;

                BDMSalary::create([
                    'bdm_id' => $bdm->id,
                    'month_year' => $monthYear,
                    'basic_salary' => $basicSalary,
                    'hra' => $hra,
                    'other_allowances' => $otherAllowances,
                    'gross_salary' => $grossSalary,
                    'per_day_salary' => $perDaySalary,
                    'total_present_days' => $presentDays,
                    'casual_leave_taken' => $casualLeaves,
                    'sick_leave_taken' => $sickLeaves,
                    'unpaid_leave_taken' => $unpaidLeaves,
                    'leave_deduction' => $leaveDeduction,
                    'deductions' => $otherDeductions,
                    'net_salary' => $netSalary,
                    'is_regenerated' => false,
                    'remarks' => $monthYear === Carbon::now()->format('Y-m') ? 'Current month salary' : 'Previous month salary - Processed',
                ]);
            }
        }

        $this->command->info('✅ Created ' . count($bdms) . ' test BDMs');
        $this->command->info('✅ Created leave balances for all BDMs');
        $this->command->info('✅ Created ' . (count($bdms) * 3) . ' leave applications (pending, approved, rejected)');
        $this->command->info('✅ Created ' . (count($bdms) * count($months)) . ' salary records for 3 months');
        $this->command->info('');
        $this->command->info('Test Login Credentials:');
        $this->command->info('Email: rajesh.kumar@konnectix.com');
        $this->command->info('Password: password123');
        $this->command->info('');
        $this->command->info('(All BDMs have the same password: password123)');
    }
}
