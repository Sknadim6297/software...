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
        $leads = Lead::with('assignedUser')->where('type', 'outgoing')->latest()->paginate(20);
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
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'platform' => 'required|string|max:100',
            'project_type' => 'required|string|max:100',
            'project_valuation' => 'nullable|numeric|min:0|max:99999999.99',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|string|in:pending,contacted,qualified,converted,rejected',
            'remarks' => 'nullable|string|max:1000',
        ], [
            'customer_name.required' => 'Customer name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'phone_number.required' => 'Phone number is required.',
            'platform.required' => 'Platform/Source is required.',
            'project_type.required' => 'Project type is required.',
            'status.in' => 'Please select a valid status.',
            'assigned_to.exists' => 'Selected user does not exist.',
        ]);

        try {
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
        } catch (\Exception $e) {
            Log::error('Error creating lead: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'There was an error saving the lead. Please try again.']);
        }
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
            'callback_time' => 'required|date|after:now',
            'call_notes' => 'nullable|string|max:500'
        ]);

        $lead->update([
            'callback_time' => $request->callback_time,
            'status' => 'callback_scheduled',
            'call_notes' => $request->call_notes,
            'callback_completed' => false
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Callback scheduled successfully! This will appear in your Dashboard → Upcoming Work section.'
        ]);
    }

    public function scheduleMeeting(Request $request, Lead $lead)
    {
        $request->validate([
            'meeting_time' => 'required|date|after:now',
            'meeting_address' => 'required|string|max:255',
            'meeting_person_name' => 'required|string|max:100',
            'meeting_phone_number' => 'required|string|max:20',
            'meeting_summary' => 'required|string|max:500'
        ]);

        // Check if BDM already has 3 meetings scheduled for this date
        $meetingDate = \Carbon\Carbon::parse($request->meeting_time)->format('Y-m-d');
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
            'meeting_time' => $request->meeting_time,
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
            
            // Send email to admin
            Mail::to('bdm.konnectixtech@gmail.com')->send(new MeetingScheduledNotification($lead, false));

            return response()->json([
                'success' => true, 
                'message' => 'Meeting scheduled successfully! Email notifications have been sent to customer and admin.'
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

    /**
     * Check meeting limit for today
     */
    public function checkMeetingLimit()
    {
        $today = \Carbon\Carbon::today();
        $count = Lead::where('assigned_to', Auth::id())
            ->whereDate('meeting_time', $today)
            ->where('status', 'meeting_scheduled')
            ->count();

        return response()->json([
            'count' => $count,
            'limit' => 3,
            'remaining' => 3 - $count
        ]);
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $request->validate([
            'status' => 'required|string|in:pending,contacted,callback_scheduled,did_not_receive,not_required,meeting_scheduled,not_interested,interested,qualified,converted,rejected',
            'notes' => 'nullable|string|max:500'
        ]);

        $updateData = ['status' => $request->status];
        
        // If completing callback, mark it as completed
        if ($lead->status === 'callback_scheduled') {
            $updateData['callback_completed'] = true;
        }
        
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

    /**
     * Complete callback and update lead status
     */
    public function completeCallback(Request $request, Lead $lead)
    {
        $request->validate([
            'new_status' => 'required|in:interested,not_interested,meeting_scheduled,did_not_receive,not_required,callback_scheduled',
            'notes' => 'nullable|string|max:500',
            // If scheduling another callback
            'callback_time' => 'required_if:new_status,callback_scheduled|date|after:now',
            // If scheduling meeting
            'meeting_time' => 'required_if:new_status,meeting_scheduled|date|after:now',
            'meeting_address' => 'required_if:new_status,meeting_scheduled|string|max:255',
            'meeting_person_name' => 'required_if:new_status,meeting_scheduled|string|max:100',
            'meeting_phone_number' => 'required_if:new_status,meeting_scheduled|string|max:20',
            'meeting_summary' => 'required_if:new_status,meeting_scheduled|string|max:500',
        ]);

        // Mark current callback as completed
        $updateData = [
            'callback_completed' => true,
            'status' => $request->new_status,
        ];

        // Add notes if provided
        if ($request->notes) {
            $updateData['call_notes'] = ($lead->call_notes ? $lead->call_notes . "\n\n" : '') . 
                                        now()->format('Y-m-d H:i') . ': ' . $request->notes;
        }

        // Handle different status outcomes
        switch ($request->new_status) {
            case 'callback_scheduled':
                $updateData['callback_time'] = $request->callback_time;
                $updateData['callback_completed'] = false; // New callback pending
                break;
                
            case 'meeting_scheduled':
                // Check meeting limit
                $todayMeetings = Lead::whereDate('meeting_time', now()->toDateString())->count();
                if ($todayMeetings >= 3) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Meeting limit reached! Maximum 3 meetings per day.'
                    ], 422);
                }
                
                $updateData['meeting_time'] = $request->meeting_time;
                $updateData['meeting_address'] = $request->meeting_address;
                $updateData['meeting_person_name'] = $request->meeting_person_name;
                $updateData['meeting_phone_number'] = $request->meeting_phone_number;
                $updateData['meeting_summary'] = $request->meeting_summary;
                break;
        }

        $lead->update($updateData);

        // Send emails for meeting scheduled
        if ($request->new_status === 'meeting_scheduled') {
            try {
                // Send to customer
                Mail::send('emails.meeting-scheduled', ['lead' => $lead], function($message) use ($lead) {
                    $message->to($lead->email)
                        ->subject('Meeting Scheduled - Konnectix Technologies');
                });
                
                // Send to admin
                Mail::send('emails.meeting-scheduled-admin', ['lead' => $lead], function($message) use ($lead) {
                    $message->to('bdm.konnectixtech@gmail.com')
                        ->subject('New Meeting Scheduled with ' . $lead->customer_name);
                });
            } catch (\Exception $e) {
                // Log error silently
            }
        }

        $statusLabels = [
            'interested' => 'Interested',
            'not_interested' => 'Not Interested',
            'meeting_scheduled' => 'Meeting Scheduled',
            'did_not_receive' => 'Did Not Receive',
            'not_required' => 'Not Required',
            'callback_scheduled' => 'New Callback Scheduled'
        ];

        return response()->json([
            'success' => true,
            'message' => 'Callback completed and status updated to: ' . $statusLabels[$request->new_status]
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