<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Current month data
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Monthly Amount (Total invoices amount for current month)
        $monthlyAmount = Invoice::whereMonth('invoice_date', $currentMonth)
            ->whereYear('invoice_date', $currentYear)
            ->sum('grand_total');
        
        // Monthly Invoices Count
        $monthlyInvoices = Invoice::whereMonth('invoice_date', $currentMonth)
            ->whereYear('invoice_date', $currentYear)
            ->count();
        
        // Monthly GST (Total tax collected in current month)
        $monthlyGST = Invoice::whereMonth('invoice_date', $currentMonth)
            ->whereYear('invoice_date', $currentYear)
            ->sum('tax_total');
        
        // New Customers (Added this month)
        $newCustomers = Customer::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        
        // Total Customers
        $totalCustomers = Customer::where('active', true)->count();
        
        // Total Invoices
        $totalInvoices = Invoice::count();
        
        // Total Revenue (All time)
        $totalRevenue = Invoice::sum('grand_total');
        
        // Recent Invoices
        $recentInvoices = Invoice::with('customer')
            ->latest()
            ->take(5)
            ->get();
        
        // Recent Customers
        $recentCustomers = Customer::latest()
            ->take(5)
            ->get();
        
        // Total Salary (placeholder - will be implemented when salary module is added)
        $totalSalary = 0;
        
        // Total Expense (placeholder - will be implemented when expense module is added)
        $totalExpense = 0;
        
        // Monthly trend data (last 6 months)
        $monthlyTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyTrend[] = [
                'month' => $date->format('M'),
                'amount' => Invoice::whereMonth('invoice_date', $date->month)
                    ->whereYear('invoice_date', $date->year)
                    ->sum('grand_total'),
                'invoices' => Invoice::whereMonth('invoice_date', $date->month)
                    ->whereYear('invoice_date', $date->year)
                    ->count(),
            ];
        }

        // Upcoming Work - Callbacks and Meetings
        $currentUserId = Auth::id();
        
        // For callbacks - check both callback_scheduled status and leads with callback_time set
        $upcomingCallbacks = Lead::where('assigned_to', $currentUserId)
            ->where(function ($query) {
                $query->where('status', 'callback_scheduled')
                      ->orWhere(function ($q) {
                          $q->whereNotNull('callback_time')
                            ->where('callback_time', '>=', Carbon::now());
                      });
            })
            ->whereNotNull('callback_time')
            ->where('callback_time', '>=', Carbon::now())
            ->orderBy('callback_time', 'asc')
            ->take(10)
            ->get();

        // For meetings - include all leads with future meeting times
        $upcomingMeetings = Lead::where('assigned_to', $currentUserId)
            ->where(function ($query) {
                $query->where('status', 'meeting_scheduled')
                      ->orWhere(function ($q) {
                          $q->whereNotNull('meeting_time')
                            ->where('meeting_time', '>=', Carbon::now());
                      });
            })
            ->whereNotNull('meeting_time')
            ->where('meeting_time', '>=', Carbon::now())
            ->orderBy('meeting_time', 'asc')
            ->take(10)
            ->get();

        // Did Not Receive Call List
        $didNotReceiveList = Lead::where('assigned_to', $currentUserId)
            ->where('status', 'did_not_receive')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();
        
        return view('dashboard', compact(
            'monthlyAmount',
            'monthlyInvoices',
            'monthlyGST',
            'newCustomers',
            'totalCustomers',
            'totalInvoices',
            'totalRevenue',
            'totalSalary',
            'totalExpense',
            'recentInvoices',
            'recentCustomers',
            'monthlyTrend',
            'upcomingCallbacks',
            'upcomingMeetings',
            'didNotReceiveList'
        ));
    }
}
