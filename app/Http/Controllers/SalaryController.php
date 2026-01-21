<?php

namespace App\Http\Controllers;

use App\Models\BDMSalary;
use App\Models\Attendance;
use App\Models\SalarySetting;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SalaryController extends Controller
{
    /**
     * Show employee salary dashboard
     * UPDATED: Now uses BDMSalary model
     */
    public function index()
    {
        $user = Auth::user();
        $bdm = $user->bdm;
        
        if (!$bdm) {
            return redirect()->route('dashboard')->with('error', 'BDM profile not found.');
        }
        
        $salaries = BDMSalary::where('bdm_id', $bdm->id)
            ->orderBy('month_year', 'desc')
            ->paginate(12);

        return view('salary.index', ['salaries' => $salaries]);
    }

    /**
     * Show salary details for a specific month
     */
    public function show(BDMSalary $salary)
    {
        $user = Auth::user();
        if ($salary->bdm_id !== $user->bdm->id) {
            abort(403, 'Unauthorized access');
        }

        return view('salary.show', ['salary' => $salary]);
    }

    /**
     * Download payslip as PDF
     */
    public function downloadPayslip(BDMSalary $salary)
    {
        $user = Auth::user();
        if ($salary->bdm_id !== $user->bdm->id) {
            abort(403, 'Unauthorized access');
        }

        // TODO: Generate PDF using dompdf or similar
        // return $salary->generatePDF();
    }

    /**
     * Calculate current month salary (for preview)
     */
    public function calculateCurrent()
    {
        $user = Auth::user();
        $now = Carbon::now();
        
        $settings = SalarySetting::where('user_id', $user->id)->first();
        if (!$settings) {
            return view('salary.preview', ['message' => 'Salary settings not configured']);
        }

        // Get attendance summary for current month
        $summary = Attendance::getMonthlySummary($user->id, $now->year, $now->month);

        // Create salary instance for calculation
        $salary = new Salary([
            'user_id' => $user->id,
            'base_salary' => $settings->base_salary,
            'year' => $now->year,
            'month' => $now->month,
            'present_days' => $summary->present_days ?? 0,
            'absent_days' => $summary->absent_days ?? 0,
            'half_days' => $summary->half_days ?? 0,
            'late_count' => $summary->late_count ?? 0,
            'approved_leaves' => $summary->approved_leaves ?? 0,
        ]);

        $salary->calculateSalary();

        return view('salary.preview', ['salary' => $salary]);
    }
}
