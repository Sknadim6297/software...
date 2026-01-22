<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BDMSalary;
use App\Models\SalarySetting;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\BDM;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminSalaryController extends Controller
{
    /**
     * Salary management dashboard
     * UPDATED: Now uses BDMSalary model
     */
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        $monthYear = sprintf('%04d-%02d', $year, $month);

        $salaries = BDMSalary::where('month_year', $monthYear)
            ->with('bdm.user')
            ->paginate(20);

        return view('admin.salary.index', [
            'salaries' => $salaries,
            'year' => $year,
            'month' => $month,
        ]);
    }

    /**
     * Show salary details
     */
    public function show(BDMSalary $salary)
    {
        $salary->load('bdm.user');
        return view('admin.salary.show', ['salary' => $salary]);
    }

    /**
     * Salary settings for employee
     */
    public function editSettings(User $user)
    {
        $settings = SalarySetting::where('user_id', $user->id)->first() ?? new SalarySetting();

        return view('admin.salary.settings', ['user' => $user, 'settings' => $settings]);
    }

    /**
     * Update salary settings
     */
    public function updateSettings(Request $request, User $user)
    {
        $request->validate([
            'base_salary' => 'required|numeric|min:0',
            'late_penalty_per_mark' => 'required|numeric|min:0',
            'half_day_deduction_percentage' => 'required|numeric|min:0|max:100',
            'absent_deduction_percentage' => 'required|numeric|min:0|max:100',
        ]);

        $settings = SalarySetting::where('user_id', $user->id)->first();
        if ($settings) {
            $settings->update($request->only([
                'base_salary',
                'late_penalty_per_mark',
                'half_day_deduction_percentage',
                'absent_deduction_percentage',
            ]));
        } else {
            $request->merge(['user_id' => $user->id]);
            SalarySetting::create($request->only([
                'user_id',
                'base_salary',
                'late_penalty_per_mark',
                'half_day_deduction_percentage',
                'absent_deduction_percentage',
            ]));
        }

        return back()->with('success', 'Salary settings updated successfully');
    }

    /**
     * Generate salary for month
     */
    public function generateMonthly(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $year = $request->year;
        $month = $request->month;

        $users = User::whereHas('bdm')->get();

        foreach ($users as $user) {
            $settings = SalarySetting::where('user_id', $user->id)->first();
            if (!$settings) {
                continue;
            }

            // Check if already exists
            $existing = Salary::where('user_id', $user->id)
                ->where('year', $year)
                ->where('month', $month)
                ->first();

            if ($existing) {
                continue;
            }

            // Get attendance summary
            $summary = Attendance::getMonthlySummary($user->id, $year, $month);
            $approvedLeaves = LeaveRequest::where('user_id', $user->id)
                ->where('status', 'approved')
                ->whereYear('from_date', $year)
                ->whereMonth('from_date', $month)
                ->count();

            // Create salary record
            $salary = Salary::create([
                'user_id' => $user->id,
                'base_salary' => $settings->base_salary,
                'year' => $year,
                'month' => $month,
                'working_days' => 20, // TODO: Calculate based on holidays
                'present_days' => $summary->present_days ?? 0,
                'absent_days' => $summary->absent_days ?? 0,
                'half_days' => $summary->half_days ?? 0,
                'late_count' => $summary->late_count ?? 0,
                'approved_leaves' => $approvedLeaves,
            ]);

            $salary->calculateSalary();
            $salary->save();
        }

        return back()->with('success', 'Salaries generated successfully');
    }

    /**
     * Process all salaries for month
     */
    public function processMonth(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);
        
        $monthYear = sprintf('%04d-%02d', $request->year, $request->month);
        $salaries = BDMSalary::where('month_year', $monthYear)
            ->get();

        foreach ($salaries as $salary) {
            $salary->markAsProcessed();
            // TODO: Send email notification to employee with payslip
        }

        return back()->with('success', sprintf('%d salaries processed successfully', $salaries->count()));
    }

    /**
     * Export salary sheet
     */
    public function exportSheet(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
            'format' => 'required|in:excel,pdf',
        ]);

        $monthYear = sprintf('%04d-%02d', $request->year, $request->month);
        $salaries = BDMSalary::where('month_year', $monthYear)
            ->with('bdm')
            ->get();

        if ($request->format === 'excel') {
            return $this->exportToExcel($salaries, $request->year, $request->month);
        } else {
            return $this->exportToPDF($salaries, $request->year, $request->month);
        }
    }

    /**
     * Export to Excel
     */
    private function exportToExcel($salaries, $year, $month)
    {
        // TODO: Implement Excel export using Maatwebsite/Excel
        return response()->download($path);
    }

    /**
     * Export to PDF
     */
    private function exportToPDF($salaries, $year, $month)
    {
        // TODO: Implement PDF export using dompdf
        return response()->download($path);
    }

    /**
     * Email payslips to employees
     */
    public function emailPayslips(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $monthYear = sprintf('%04d-%02d', $request->year, $request->month);
        $salaries = BDMSalary::where('month_year', $monthYear)
            ->with('bdm.user')
            ->get();

        if ($salaries->isEmpty()) {
            return back()->with('error', 'No salary slips available for the selected month. Please generate salaries first.');
        }

        $emailCount = 0;
        foreach ($salaries as $salary) {
            if ($salary->bdm && $salary->bdm->user && $salary->bdm->user->email) {
                // TODO: Send email with payslip attachment
                // Mail::to($salary->bdm->user->email)->send(new PayslipMail($salary));
                $emailCount++;
            }
        }

        if ($emailCount > 0) {
            return back()->with('success', sprintf('Payslips will be emailed to %d employees', $emailCount));
        } else {
            return back()->with('error', 'No valid employee emails found to send payslips.');
        }
    }

    /**
     * Salary report
     */
    public function report(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);
        $monthYear = sprintf('%04d-%02d', $year, $month);

        $report = BDMSalary::where('month_year', $monthYear)
            ->selectRaw('
                COUNT(*) as total_employees,
                SUM(basic_salary) as total_base_salary,
                SUM(deductions) as total_deductions,
                SUM(net_salary) as total_net_salary,
                AVG(net_salary) as average_salary
            ')
            ->first();

        $topEarners = BDMSalary::where('month_year', $monthYear)
            ->with('bdm.user')
            ->orderByDesc('net_salary')
            ->limit(10)
            ->get();

        return view('admin.salary.report', [
            'report' => $report,
            'topEarners' => $topEarners,
            'year' => $year,
            'month' => $month,
        ]);
    }
}
