<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\MeetingScheduledNotification;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::with('assignedUser')->latest()->paginate(15);
        
        // Get statistics for summary cards
        $totalLeads = Lead::count();
        $pendingLeads = Lead::where('status', 'pending')->count();
        $convertedLeads = Lead::where('status', 'converted')->count();
        $todayLeads = Lead::whereDate('created_at', today())->count();
        
        return view('leads.index', compact(
            'leads',
            'totalLeads',
            'pendingLeads', 
            'convertedLeads',
            'todayLeads'
        ));
    }

    public function incoming()
    {
        $leads = Lead::with('assignedUser')->where('type', 'incoming')->latest()->get();
        $bdms = User::all(); // Get all users since we don't have role column
        
        // Get unique customer names with phone numbers for dropdown
        $customers = Lead::select('customer_name', 'phone_number')
            ->distinct()
            ->orderBy('customer_name')
            ->get()
            ->map(function($lead) {
                return [
                    'name' => $lead->customer_name,
                    'phone' => $lead->phone_number,
                    'display' => $lead->customer_name . ' - ' . $lead->phone_number
                ];
            });
        
        // Status options for incoming leads
        $statusOptions = [
            'pending' => 'Pending',
            'callback_scheduled' => 'Call Back',
            'did_not_receive' => 'Did Not Receive', 
            'not_required' => 'Not Required',
            'meeting_scheduled' => 'Meeting',
            'not_interested' => 'Not Interested',
            'converted' => 'Converted'
        ];
        
        // Remarks options for filtering
        $remarksOptions = [
            'Call Back',
            'Did not receive',
            'Not Required',
            'Meeting',
            'Not Interested',
            'Follow up required',
            'Hot lead',
            'Cold lead'
        ];
        
        return view('leads.incoming', compact('leads', 'bdms', 'customers', 'statusOptions', 'remarksOptions'));
    }

    public function outgoing(Request $request)
    {
        $leads = Lead::with('assignedUser')->where('type', 'outgoing')->latest()->get();
        $bdms = User::all();
        
        // Statistics for outgoing leads
        $totalOutgoing = Lead::where('type', 'outgoing')->count();
        $pendingOutgoing = Lead::where('type', 'outgoing')->where('status', 'pending')->count();
        $interestedOutgoing = Lead::where('type', 'outgoing')->where('status', 'interested')->count();
        $scheduledOutgoing = Lead::where('type', 'outgoing')
            ->where(function($query) {
                $query->whereNotNull('callback_time')->where('callback_time', '>=', now())
                      ->orWhere(function($q) {
                          $q->whereNotNull('meeting_time')->where('meeting_time', '>=', now());
                      });
            })->count();
        
        // Get unique customer names with phone numbers for dropdown
        $customers = Lead::select('customer_name', 'phone_number')
            ->distinct()
            ->orderBy('customer_name')
            ->get()
            ->map(function($lead) {
                return [
                    'name' => $lead->customer_name,
                    'phone' => $lead->phone_number,
                    'display' => $lead->customer_name . ' - ' . $lead->phone_number
                ];
            });
        
        // Status options for outgoing leads (includes 'interested')
        $statusOptions = [
            'pending' => 'Pending',
            'callback_scheduled' => 'Call Back',
            'did_not_receive' => 'Did Not Receive', 
            'not_required' => 'Not Required',
            'meeting_scheduled' => 'Meeting',
            'not_interested' => 'Not Interested',
            'interested' => 'Interested',
            'converted' => 'Converted'
        ];
        
        // Remarks options for filtering
        $remarksOptions = [
            'Call Back',
            'Did not receive',
            'Not Required',
            'Meeting',
            'Not Interested',
            'Interested',
            'Follow up required',
            'Hot lead',
            'Cold lead'
        ];
        
        return view('leads.outgoing', compact(
            'leads', 
            'bdms', 
            'customers', 
            'statusOptions', 
            'remarksOptions',
            'totalOutgoing',
            'pendingOutgoing',
            'interestedOutgoing', 
            'scheduledOutgoing'
        ));
    }

    public function show(Lead $lead)
    {
        $daysInSystem = $lead->created_at->diffInDays(now());
        $totalInteractions = 0;
        $scheduledCallbacks = $lead->callback_time ? 1 : 0;
        $scheduledMeetings = $lead->meeting_time ? 1 : 0;
        
        return view('leads.show', compact('lead', 'daysInSystem', 'totalInteractions', 'scheduledCallbacks', 'scheduledMeetings'));
    }

    public function edit(Lead $lead)
    {
        $bdms = User::all(); // Get all users since we don't have role column
        
        // Get unique customer names with phone numbers for dropdown
        $customers = Lead::select('customer_name', 'phone_number')
            ->distinct()
            ->orderBy('customer_name')
            ->get()
            ->map(function($leadItem) {
                return [
                    'name' => $leadItem->customer_name,
                    'phone' => $leadItem->phone_number,
                    'display' => $leadItem->customer_name . ' - ' . $leadItem->phone_number
                ];
            });
        
        // Predefined remarks options
        $remarksOptions = [
            'Remarks',
            'Call Back',
            'Did not receive',
            'Not Required',
            'Meeting',
            'Not Interested'
        ];
        
        return view('leads.edit', compact('lead', 'bdms', 'customers', 'remarksOptions'));
    }

    public function create($type = 'incoming')
    {
        $users = User::all(); // Get all users since we don't have role column
        
        // Get unique customer names with phone numbers for dropdown
        $customers = Lead::select('customer_name', 'phone_number')
            ->distinct()
            ->orderBy('customer_name')
            ->get()
            ->map(function($lead) {
                return [
                    'name' => $lead->customer_name,
                    'phone' => $lead->phone_number,
                    'display' => $lead->customer_name . ' - ' . $lead->phone_number
                ];
            });
        
        // Predefined remarks options
        $remarksOptions = [
            'Remarks',
            'Call Back',
            'Did not receive',
            'Not Required',
            'Meeting',
            'Not Interested'
        ];
        
        return view('leads.create', compact('users', 'type', 'customers', 'remarksOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:20',
            'platform' => 'required|string',
            'project_type' => 'required|string',
            'project_valuation' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|string',
            'remarks' => 'nullable|string',
        ]);

        Lead::create([
            'type' => 'incoming',
            'date' => now()->toDateString(),
            'time' => now()->toTimeString(),
            'platform' => $validated['platform'],
            'customer_name' => $validated['customer_name'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'project_type' => $validated['project_type'],
            'project_valuation' => $validated['project_valuation'],
            'remarks' => $validated['remarks'],
            'status' => $validated['status'],
            'assigned_to' => $validated['assigned_to'] ?? Auth::id(),
        ]);

        return redirect()->route('leads.incoming')->with('success', 'Lead added successfully!');
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:20',
            'platform' => 'required|string',
            'project_type' => 'required|string',
            'project_valuation' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|string',
            'callback_time' => 'nullable|date',
            'meeting_time' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);

        $lead->update([
            'customer_name' => $validated['customer_name'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'platform' => $validated['platform'],
            'project_type' => $validated['project_type'],
            'project_valuation' => $validated['project_valuation'],
            'assigned_to' => $validated['assigned_to'],
            'status' => $validated['status'],
            'callback_time' => $validated['callback_time'],
            'meeting_time' => $validated['meeting_time'],
            'remarks' => $validated['remarks'],
        ]);

        return redirect()->route('leads.show', $lead)->with('success', 'Lead updated successfully!');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('leads.incoming')->with('success', 'Lead deleted successfully!');
    }

    public function scheduleCallback(Request $request, Lead $lead)
    {
        $request->validate([
            'callback_date' => 'required|date|after:now',
            'callback_notes' => 'nullable|string|max:500'
        ]);

        $lead->update([
            'callback_time' => $request->callback_date,
            'status' => 'callback_scheduled',
            'call_notes' => $request->callback_notes
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Callback scheduled successfully! This will appear in your Dashboard → Upcoming Work section.'
        ]);
    }

    public function scheduleMeeting(Request $request, Lead $lead)
    {
        $request->validate([
            'meeting_date' => 'required|date|after:now',
            'meeting_address' => 'required|string|max:255',
            'meeting_person_name' => 'required|string|max:100',
            'meeting_phone_number' => 'required|string|max:20',
            'meeting_summary' => 'required|string|max:500'
        ]);

        // Check if BDM already has 3 meetings scheduled for this date
        $meetingDate = \Carbon\Carbon::parse($request->meeting_date)->format('Y-m-d');
        $existingMeetings = Lead::where('assigned_to', Auth::id())
            ->whereDate('meeting_time', $meetingDate)
            ->where('status', 'meeting_scheduled')
            ->count();

        if ($existingMeetings >= 3) {
            return response()->json([
                'success' => false, 
                'message' => 'Maximum 3 meetings can be scheduled per day. Please choose a different date.'
            ]);
        }

        $lead->update([
            'meeting_time' => $request->meeting_date,
            'meeting_address' => $request->meeting_address,
            'meeting_person_name' => $request->meeting_person_name,
            'meeting_phone_number' => $request->meeting_phone_number,
            'meeting_summary' => $request->meeting_summary,
            'status' => 'meeting_scheduled'
        ]);

        // Send email notifications
        try {
            // Send email to customer if email is available
            if ($lead->email) {
                Mail::to($lead->email)->send(new MeetingScheduledNotification($lead, true));
            }
            
            // Send email to BDM
            Mail::to('bdm.konnectixtech@gmail.com')->send(new MeetingScheduledNotification($lead, false));

            return response()->json([
                'success' => true, 
                'message' => 'Meeting scheduled successfully! Email notifications have been sent to customer and BDM.'
            ]);
        } catch (\Exception $e) {
            // Meeting was scheduled but email failed
            Log::error('Failed to send meeting notification emails: ' . $e->getMessage());
            
            return response()->json([
                'success' => true, 
                'message' => 'Meeting scheduled successfully! (Note: Email notifications may have failed)'
            ]);
        }
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate([
            'status' => 'required|string|in:pending,callback_scheduled,did_not_receive,not_required,meeting_scheduled,not_interested,interested,converted',
            'notes' => 'nullable|string|max:500'
        ]);

        $updateData = ['status' => $request->status];
        
        // Update remarks/notes if provided
        if ($request->has('notes') && !empty($request->notes)) {
            $updateData['remarks'] = $request->notes;
        }
        
        $lead->update($updateData);
        
        return response()->json([
            'success' => true, 
            'message' => 'Status updated successfully!'
        ]);
    }

    public function convertToCustomer(Request $request, Lead $lead)
    {
        try {
            // Check if customer already exists
            $existingCustomer = Customer::where('email', $lead->email)
                ->orWhere('number', $lead->phone_number)
                ->first();

            if ($existingCustomer) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Customer already exists with this email or phone number.'
                ]);
            }

            // Create new customer
            Customer::create([
                'customer_name' => $lead->customer_name,
                'email' => $lead->email,
                'number' => $lead->phone_number,
                'project_type' => $lead->project_type,
                'project_valuation' => $lead->project_valuation,
                'lead_source' => $lead->platform,
                'remarks' => $lead->remarks,
                'active' => true
            ]);

            // Update lead status to converted
            $lead->update(['status' => 'converted']);

            return response()->json([
                'success' => true, 
                'message' => 'Lead converted to customer successfully! Customer details moved to Customer Management section.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Failed to convert lead to customer. Please try again.'
            ]);
        }
    }
}