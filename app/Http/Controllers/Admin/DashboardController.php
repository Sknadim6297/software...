<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BDM;
use App\Models\BDMLeaveApplication;
use App\Models\BDMTarget;
use App\Models\BDMSalary;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total BDMs count
        $totalBDMs = BDM::count();
        
        // Active BDMs
        $activeBDMs = BDM::where('status', 'active')->count();
        
        // Terminated BDMs
        $terminatedBDMs = BDM::where('status', 'terminated')->count();
        
        // BDMs on leave today
        $bdmsOnLeave = BDMLeaveApplication::where('status', 'approved')
            ->whereDate('leave_date', Carbon::today())
            ->distinct('bdm_id')
            ->count('bdm_id');
        
        // Pending leave approvals
        $pendingLeaves = BDMLeaveApplication::where('status', 'pending')->count();
        
        // Current month target summary
        $currentMonth = Carbon::now()->format('Y-m');
        $monthlyTargets = BDMTarget::where('target_type', 'monthly')
            ->where('period', $currentMonth)
            ->get();
        
        $totalTarget = $monthlyTargets->sum('total_revenue_target');
        $totalAchieved = $monthlyTargets->sum('revenue_achieved');
        
        // Current month salary payout
        $currentMonth = Carbon::now()->format('Y-m');
        $currentMonthSalary = BDMSalary::where('month_year', $currentMonth)
            ->sum('net_salary');
        
        // Recent BDMs
        $recentBDMs = BDM::latest()->take(5)->get();
        
        // Recent leave requests
        $recentLeaves = BDMLeaveApplication::with('bdm')
            ->latest()
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalBDMs',
            'activeBDMs',
            'terminatedBDMs',
            'bdmsOnLeave',
            'pendingLeaves',
            'totalTarget',
            'totalAchieved',
            'currentMonthSalary',
            'recentBDMs',
            'recentLeaves'
        ));
    }
}
