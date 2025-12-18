<?php

namespace App\Http\Controllers;

use App\Models\BDM;
use App\Models\BDMDocument;
use App\Models\BDMLeaveApplication;
use App\Models\BDMTarget;
use App\Models\BDMNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BDMController extends Controller
{
    // Dashboard
    public function dashboard()
    {
        $bdm = Auth::user()->bdm;
        
        if (!$bdm) {
            return redirect()->route('login')->with('error', 'BDM profile not found. Please contact administrator.');
        }

        // Check if terminated - block access completely
        if ($bdm->isTerminated() || !$bdm->can_login) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Your account has been terminated. You no longer have access to the system.');
        }

        // Get current month target
        $currentTarget = $bdm->currentMonthTarget();
        
        // Get unread notifications
        $unreadNotifications = $bdm->notifications()->where('is_read', false)->count();
        
        // Get recent leave applications
        $recentLeaves = $bdm->leaveApplications()->latest()->take(5)->get();
        
        // Get leave balance
        $leaveBalance = $bdm->leaveBalance;

        return view('bdm.dashboard', compact('bdm', 'currentTarget', 'unreadNotifications', 'recentLeaves', 'leaveBalance'));
    }

    // Profile Management
    public function showProfile()
    {
        $bdm = Auth::user()->bdm;
        return view('bdm.profile', compact('bdm'));
    }

    public function updateProfile(Request $request)
    {
        $bdm = Auth::user()->bdm;

        $request->validate([
            'phone' => 'nullable|string|max:20',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['phone']);

        if ($request->hasFile('profile_image')) {
            // Delete old image
            if ($bdm->profile_image) {
                Storage::disk('public')->delete($bdm->profile_image);
            }

            $path = $request->file('profile_image')->store('bdm/profiles', 'public');
            $data['profile_image'] = $path;
        }

        $bdm->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    // Document Management
    public function showDocuments()
    {
        $bdm = Auth::user()->bdm;
        $documents = $bdm->documents;
        $documentTypes = BDMDocument::getDocumentTypes();
        
        // Get missing documents
        $uploadedTypes = $documents->pluck('document_type')->toArray();
        $missingTypes = array_diff(array_keys($documentTypes), $uploadedTypes);

        return view('bdm.documents', compact('bdm', 'documents', 'documentTypes', 'missingTypes'));
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:aadhaar_card,pan_card,10th_admit_card,12th_marksheet,graduation_certificate,last_company_offer_letter,salary_slip,reference_contact',
            'document_file' => 'required|file|max:5120', // 5MB max
        ]);

        $bdm = Auth::user()->bdm;

        // Check if document already exists
        $existingDoc = $bdm->documents()->where('document_type', $request->document_type)->first();
        if ($existingDoc) {
            // Delete old file
            Storage::disk('public')->delete($existingDoc->file_path);
            $existingDoc->delete();
        }

        // Upload new document
        $file = $request->file('document_file');
        $path = $file->store('bdm/documents/' . $bdm->id, 'public');

        $bdm->documents()->create([
            'document_type' => $request->document_type,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'uploaded_at' => now(),
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function downloadDocument($id)
    {
        $bdm = Auth::user()->bdm;
        $document = $bdm->documents()->findOrFail($id);

        if (!Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($document->file_path, $document->original_filename);
    }

    public function deleteDocument($id)
    {
        $bdm = Auth::user()->bdm;
        $document = $bdm->documents()->findOrFail($id);

        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }

    // Salary Management
    public function showSalary()
    {
        $bdm = Auth::user()->bdm;
        $salaries = $bdm->salaries()->orderBy('month_year', 'desc')->paginate(12);

        return view('bdm.salary', compact('bdm', 'salaries'));
    }

    public function downloadSalarySlip($id)
    {
        $bdm = Auth::user()->bdm;
        $salary = $bdm->salaries()->findOrFail($id);

        if (!$salary->salary_slip_path || !Storage::disk('public')->exists($salary->salary_slip_path)) {
            return back()->with('error', 'Salary slip not found.');
        }

        return Storage::disk('public')->download($salary->salary_slip_path, 'salary_slip_' . $salary->month_year . '.pdf');
    }

    // Leave Management
    public function showLeaves()
    {
        $bdm = Auth::user()->bdm;
        $leaveBalance = $bdm->leaveBalance;
        $leaveApplications = $bdm->leaveApplications()->orderBy('created_at', 'desc')->paginate(10);
        
        // Check eligibility
        $isEligible = $bdm->isEligibleForLeaves();
        $daysUntilEligible = null;
        
        if (!$isEligible) {
            $eligibilityDate = Carbon::parse($bdm->joining_date)->addMonths(6);
            $daysUntilEligible = Carbon::now()->diffInDays($eligibilityDate, false);
        }

        return view('bdm.leaves', compact('bdm', 'leaveBalance', 'leaveApplications', 'isEligible', 'daysUntilEligible'));
    }

    public function applyLeave(Request $request)
    {
        $bdm = Auth::user()->bdm;
        
        $request->validate([
            'leave_type' => 'required|in:casual,sick,unpaid',
            'leave_date' => 'required|date|after:today',
            'reason' => 'required|string|max:500',
        ]);

        $leaveType = $request->leave_type;
        $leaveDate = Carbon::parse($request->leave_date);

        // Check if eligible for CL/SL
        if (in_array($leaveType, ['casual', 'sick']) && !$bdm->isEligibleForLeaves()) {
            return back()->with('error', 'You are not eligible for Casual/Sick leave yet. Please wait 6 months from joining date.');
        }

        $leaveBalance = $bdm->leaveBalance;
        
        // Reset monthly usage if needed
        if ($leaveBalance) {
            $leaveBalance->resetMonthlyUsage();
        }

        // Validate leave type specific rules
        if ($leaveType === 'casual') {
            // Check balance
            if (!$leaveBalance || !$leaveBalance->canTakeCasualLeave()) {
                return back()->with('error', 'Insufficient casual leave balance or monthly limit exceeded (Max 1 per month).');
            }

            // Check 15 days advance notice
            $daysInAdvance = Carbon::now()->diffInDays($leaveDate, false);
            if ($daysInAdvance < 15) {
                return back()->with('error', 'Casual leave must be applied at least 15 days in advance.');
            }
        } elseif ($leaveType === 'sick') {
            // Check balance
            if (!$leaveBalance || !$leaveBalance->canTakeSickLeave()) {
                return back()->with('error', 'Insufficient sick leave balance or monthly limit exceeded (Max 1 per month).');
            }

            // Check same-day rule: must apply before 7:30 AM
            if ($leaveDate->isSameDay(Carbon::now()) && Carbon::now()->greaterThan(Carbon::now()->setTime(7, 30, 0))) {
                return back()->with('error', 'Sick leave for today must be applied before 7:30 AM.');
            }
        }

        // Create leave application
        $bdm->leaveApplications()->create([
            'leave_type' => $leaveType,
            'leave_date' => $leaveDate,
            'reason' => $request->reason,
            'status' => 'pending',
            'applied_at' => now(),
        ]);

        return back()->with('success', 'Leave application submitted successfully. Awaiting admin approval.');
    }

    // Target Management
    public function showTargets()
    {
        $bdm = Auth::user()->bdm;
        
        // Get current month target
        $currentMonthTarget = $bdm->currentMonthTarget();
        
        // Get all targets with pagination
        $targets = $bdm->targets()
            ->orderBy('start_date', 'desc')
            ->paginate(12);

        return view('bdm.targets', compact('bdm', 'currentMonthTarget', 'targets'));
    }

    public function showTargetDetail($id)
    {
        $bdm = Auth::user()->bdm;
        $target = $bdm->targets()->findOrFail($id);

        return view('bdm.target-detail', compact('bdm', 'target'));
    }

    // Notifications
    public function showNotifications()
    {
        $bdm = Auth::user()->bdm;
        $notifications = $bdm->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('bdm.notifications', compact('bdm', 'notifications'));
    }

    public function markNotificationRead($id)
    {
        $bdm = Auth::user()->bdm;
        $notification = $bdm->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllNotificationsRead()
    {
        $bdm = Auth::user()->bdm;
        $bdm->notifications()->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'All notifications marked as read.');
    }
}
