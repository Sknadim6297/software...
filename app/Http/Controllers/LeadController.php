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
        return $this->allLeads();
    }

    public function allLeads()
    {
        $leads = Lead::with('assignedUser')->latest()->paginate(20);
        $bdms = User::all();
        
        // Get statistics for all leads
        $totalLeads = Lead::count();
        $incomingLeads = Lead::where('type', 'incoming')->count();
        $outgoingLeads = Lead::where('type', 'outgoing')->count();
        $pendingLeads = Lead::where('status', 'pending')->count();
        $interestedLeads = Lead::where('status', 'interested')->count();
        $convertedLeads = Lead::where('status', 'converted')->count();
        $todayLeads = Lead::whereDate('created_at', today())->count();
        
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
        
        // Status options for all leads
        $statusOptions = [
            'new' => 'New',
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
        
        return view('leads.all', compact(
            'leads', 
            'bdms', 
            'customers', 
            'statusOptions', 
            'remarksOptions',
            'totalLeads',
            'incomingLeads',
            'outgoingLeads',
            'pendingLeads',
            'interestedLeads',
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
            'type' => 'required|string|in:incoming,outgoing',
            'customer_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'required|string|max:20',
            'platform' => 'required|string|in:facebook,instagram,website,google,justdial,other',
            'platform_other' => 'nullable|required_if:platform,other|string|max:100',
            'project_type' => 'required|string|max:100',
            'project_valuation' => 'nullable|numeric|min:0|max:99999999.99',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|string|in:new,pending,callback_scheduled,did_not_receive,not_required,meeting_scheduled,not_interested,interested,converted',
            'remarks' => 'nullable|string|max:1000',
            // Callback fields
            'callback_time' => 'nullable|required_if:status,callback_scheduled|date|after:now',
            'call_notes' => 'nullable|string|max:500',
            // Meeting fields
            'meeting_time' => 'nullable|required_if:status,meeting_scheduled|date|after:now',
            'meeting_address' => 'nullable|required_if:status,meeting_scheduled|string|max:255',
            'meeting_person_name' => 'nullable|required_if:status,meeting_scheduled|string|max:100',
            'meeting_phone_number' => 'nullable|required_if:status,meeting_scheduled|string|max:20',
            'meeting_summary' => 'nullable|required_if:status,meeting_scheduled|string|max:500',
        ], [
            'customer_name.required' => 'Customer name is required.',
            'email.email' => 'Please enter a valid email address.',
            'phone_number.required' => 'Phone number is required.',
            'platform.required' => 'Platform / Source Type is required.',
            'platform.in' => 'Please select a valid Platform / Source Type.',
            'platform_other.required_if' => 'Please specify the source when selecting Other.',
            'project_type.required' => 'Project type is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Please select a valid status.',
            'callback_time.required_if' => 'Callback date & time is required for Call Back status.',
            'meeting_time.required_if' => 'Meeting date & time is required for Calendar status.',
            'meeting_address.required_if' => 'Meeting address is required for Calendar status.',
            'meeting_person_name.required_if' => 'Person name is required for Calendar status.',
            'meeting_phone_number.required_if' => 'Phone number is required for Calendar status.',
            'meeting_summary.required_if' => 'Meeting summary is required for Calendar status.',
        ]);

        // Enforce meeting per-day limit if scheduling meeting now
        if ($validated['status'] === 'meeting_scheduled') {
            $meetingDate = \Carbon\Carbon::parse($validated['meeting_time'])->format('Y-m-d');
            $existingMeetings = Lead::where('assigned_to', Auth::id())
                ->whereDate('meeting_time', $meetingDate)
                ->where('status', 'meeting_scheduled')
                ->count();
            if ($existingMeetings >= 3) {
                return redirect()->back()->withInput()->withErrors(['meeting_time' => 'Maximum 3 meetings can be scheduled per day. Please choose a different date.']);
            }
        }

        try {
            // Resolve platform value and custom text
            $platformValue = $validated['platform'];
            $platformCustom = null;
            if ($platformValue === 'other') {
                $platformCustom = trim($validated['platform_other'] ?? '');
            }

            $leadData = [
                'type' => $validated['type'],
                'date' => now()->toDateString(),
                'time' => now()->toTimeString(),
                'platform' => $platformValue,
                'platform_custom' => $platformCustom,
                'customer_name' => $validated['customer_name'],
                'phone_number' => $validated['phone_number'],
                'email' => $validated['email'],
                'project_type' => $validated['project_type'],
                'project_valuation' => $validated['project_valuation'],
                'remarks' => $validated['remarks'],
                'status' => $validated['status'],
                'assigned_to' => $validated['assigned_to'] ?? Auth::id(),
            ];

            // Only add callback fields if status is callback_scheduled
            if ($validated['status'] === 'callback_scheduled') {
                $leadData['callback_time'] = $validated['callback_time'];
                $leadData['call_notes'] = $validated['call_notes'];
                $leadData['callback_completed'] = false;
            }

            // Only add meeting fields if status is meeting_scheduled
            if ($validated['status'] === 'meeting_scheduled') {
                $leadData['meeting_time'] = $validated['meeting_time'];
                $leadData['meeting_address'] = $validated['meeting_address'];
                $leadData['meeting_person_name'] = $validated['meeting_person_name'];
                $leadData['meeting_phone_number'] = $validated['meeting_phone_number'];
                $leadData['meeting_summary'] = $validated['meeting_summary'];
            }

            $lead = Lead::create($leadData);

            // If meeting scheduled, attempt emails (re-use scheduleMeeting logic)
            if ($validated['status'] === 'meeting_scheduled') {
                try {
                    if ($lead->email) {
                        Mail::to($lead->email)->send(new MeetingScheduledNotification($lead, true));
                    }
                    Mail::to('bdm.konnectixtech@gmail.com')->send(new MeetingScheduledNotification($lead, false));
                } catch (\Exception $e) {
                    Log::error('Failed sending meeting creation emails: ' . $e->getMessage());
                }
            }

            // Redirect to appropriate page based on lead type
            $redirectRoute = $validated['type'] === 'incoming' ? 'leads.incoming' : 'leads.outgoing';
            return redirect()->route($redirectRoute)->with('success', 'Lead added successfully!');
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
            'email' => 'nullable|email',
            'phone_number' => 'required|string|max:20',
            'platform' => 'required|string|in:facebook,instagram,website,google,justdial,other',
            'platform_other' => 'nullable|required_if:platform,other|string|max:100',
            'project_type' => 'required|string',
            'project_valuation' => 'nullable|numeric|min:0',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'required|string',
            'callback_time' => 'nullable|date',
            'meeting_time' => 'nullable|date',
            'remarks' => 'nullable|string',
        ]);

        $platformValue = $validated['platform'];
        $platformCustom = null;
        if ($platformValue === 'other') {
            $platformCustom = trim($validated['platform_other'] ?? '');
        }

        $lead->update([
            'customer_name' => $validated['customer_name'],
            'phone_number' => $validated['phone_number'],
            'email' => $validated['email'],
            'platform' => $platformValue,
            'platform_custom' => $platformCustom,
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
            'meeting_time' => 'required|date',
            'meeting_address' => 'required|string|max:255',
            'meeting_person_name' => 'required|string|max:100',
            'meeting_phone_number' => 'required|string|max:20',
            'meeting_summary' => 'required|string|max:500'
        ]);

        $meetingDt = \Carbon\Carbon::parse($request->meeting_time);
        $meetingDate = $meetingDt->format('Y-m-d');

        // If selected date is today, only restrict time to be in the future
        if ($meetingDt->isToday() && $meetingDt->lessThanOrEqualTo(now())) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a meeting time in the future for today.'
            ]);
        }

        // Check if BDM already has 3 meetings scheduled for this date
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
     * Mark meeting as completed with summary
     */
    public function completeMeeting(Request $request, Lead $lead)
    {
        $request->validate([
            'summary' => 'required|string|max:1000'
        ]);

        $lead->update([
            'meeting_completed' => true,
            'meeting_completed_summary' => $request->summary,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meeting marked as completed and summary saved.'
        ]);
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