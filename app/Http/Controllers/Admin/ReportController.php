<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BDM;
use App\Models\BDMTarget;
use App\Models\BDMSalary;
use App\Models\BDMLeaveApplication;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function targetReport(Request $request)
    {
        $month = $request->month ?? Carbon::now()->format('Y-m');
        
        $targets = BDMTarget::with('bdm')
            ->where('target_type', 'monthly')
            ->where('period', $month)
            ->get();
        
        return view('admin.reports.target', compact('targets', 'month'));
    }

    public function salaryReport(Request $request)
    {
        $month = $request->month ?? Carbon::now()->format('Y-m');
        
        $salaries = BDMSalary::with('bdm')
            ->where('month_year', $month)
            ->get();
        
        $totalSalary = $salaries->sum('net_salary');
        
        return view('admin.reports.salary', compact('salaries', 'month', 'totalSalary'));
    }

    public function leaveReport(Request $request)
    {
        $month = $request->month ?? Carbon::now()->format('Y-m');
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();
        
        $leaves = BDMLeaveApplication::with('bdm')
            ->whereBetween('leave_date', [$startDate, $endDate])
            ->get();
        
        return view('admin.reports.leave', compact('leaves', 'month'));
    }

    public function performanceReport(Request $request)
    {
        $period = $request->period ?? 'monthly';
        $date = $request->date ?? Carbon::now()->format('Y-m');
        
        $bdms = BDM::where('status', '!=', 'terminated')
            ->withCount([
                'targets as total_targets' => function($query) use ($date) {
                    $query->where('period', $date);
                },
                'targets as achieved_targets' => function($query) use ($date) {
                    $query->where('period', $date)
                          ->where('target_met', true);
                }
            ])
            ->with(['targets' => function($query) use ($date) {
                $query->where('period', $date);
            }])
            ->get();
        
        return view('admin.reports.performance', compact('bdms', 'period', 'date'));
    }

    public function attendanceReport(Request $request)
    {
        $month = $request->month ?? Carbon::now()->format('Y-m');
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();
        
        $bdms = BDM::where('status', 'active')
            ->with(['leaveApplications' => function($query) use ($startDate, $endDate) {
                $query->where('status', 'approved')
                      ->whereBetween('leave_date', [$startDate, $endDate]);
            }])
            ->get();
        
        return view('admin.reports.attendance', compact('bdms', 'month'));
    }
}
