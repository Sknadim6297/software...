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
        
        // For callbacks - show ALL callbacks (incoming and outgoing)
        $upcomingCallbacks = Lead::whereNotNull('callback_time')
            ->where('callback_time', '>=', Carbon::now())
            ->orderBy('callback_time', 'asc')
            ->take(10)
            ->get();

        // For meetings - show ALL meetings (incoming and outgoing) regardless of assignment
        $upcomingMeetings = Lead::whereNotNull('meeting_time')
            ->where('meeting_time', '>=', Carbon::today()->startOfDay())
            ->orderBy('meeting_time', 'asc')
            ->take(10)
            ->get();

        // Count ALL meetings for today (both incoming and outgoing)
        $todayMeetingsCount = Lead::whereNotNull('meeting_time')
            ->whereDate('meeting_time', Carbon::today())
            ->count();
            
        // Count ALL meetings for tomorrow (both incoming and outgoing)
        $tomorrowMeetingsCount = Lead::whereNotNull('meeting_time')
            ->whereDate('meeting_time', Carbon::tomorrow())
            ->count();
            
        $dayAfterTomorrowMeetingsCount = Lead::whereNotNull('meeting_time')
            ->whereDate('meeting_time', Carbon::today()->addDays(2))
            ->count();

        // Get next 7 days meetings for overview
        $next7DaysMeetings = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->addDays($i);
            $count = Lead::whereNotNull('meeting_time')
                ->whereDate('meeting_time', $date)
                ->count();
            if ($count > 0) {
                $next7DaysMeetings[] = [
                    'date' => $date->format('M j'),
                    'day_name' => $i == 0 ? 'Today' : ($i == 1 ? 'Tomorrow' : $date->format('D')),
                    'count' => $count,
                    'full_date' => $date->format('Y-m-d')
                ];
            }
        }

        // Determine which count to show in main badge
        if ($todayMeetingsCount > 0) {
            $displayMeetingsCount = $todayMeetingsCount;
            $displayDate = 'Today';
        } elseif ($tomorrowMeetingsCount > 0) {
            $displayMeetingsCount = $tomorrowMeetingsCount;
            $displayDate = 'Tomorrow';
        } elseif ($dayAfterTomorrowMeetingsCount > 0) {
            $displayMeetingsCount = $dayAfterTomorrowMeetingsCount;
            $displayDate = Carbon::today()->addDays(2)->format('M j');
        } else {
            // Show next upcoming meeting day
            $nextMeetingDay = collect($next7DaysMeetings)->first();
            $displayMeetingsCount = $nextMeetingDay ? $nextMeetingDay['count'] : 0;
            $displayDate = $nextMeetingDay ? $nextMeetingDay['day_name'] : 'None';
        }

        // Did Not Receive Call List - show ALL leads
        $didNotReceiveList = Lead::where('status', 'did_not_receive')
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
            'todayMeetingsCount',
            'tomorrowMeetingsCount',
            'dayAfterTomorrowMeetingsCount',
            'next7DaysMeetings',
            'displayMeetingsCount',
            'displayDate',
            'didNotReceiveList'
        ));
    }
}
