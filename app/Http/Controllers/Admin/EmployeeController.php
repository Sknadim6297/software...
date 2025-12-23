<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BDM;
use App\Models\User;
use App\Models\BDMLeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function index()
    {
        $bdms = BDM::with('user')->latest()->paginate(15);
        return view('admin.employees.index', compact('bdms'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'highest_education' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'joining_date' => 'required|date',
            'current_ctc' => 'required|numeric|min:0',
            'password' => 'required|string|min:8',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Create user account
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Handle profile image
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('bdm/profiles', 'public');
        }

        // Generate employee code
        $employeeCode = 'BDM' . str_pad(BDM::count() + 1, 4, '0', STR_PAD_LEFT);

        // Create BDM profile
        $bdm = BDM::create([
            'user_id' => $user->id,
            'profile_image' => $profileImagePath,
            'name' => $request->name,
            'father_name' => $request->father_name,
            'date_of_birth' => $request->date_of_birth,
            'highest_education' => $request->highest_education,
            'email' => $request->email,
            'phone' => $request->phone,
            'employee_code' => $employeeCode,
            'joining_date' => $request->joining_date,
            'current_ctc' => $request->current_ctc,
            'status' => 'active',
            'can_login' => true,
            'warning_count' => 0,
        ]);

        // Create leave balance (initially 0 for first 6 months)
        BDMLeaveBalance::create([
            'bdm_id' => $bdm->id,
            'casual_leave' => 0,
            'sick_leave' => 0,
            'unpaid_leave' => 0,
        ]);

        return redirect()->route('admin.employees.show', $bdm->id)
            ->with('success', 'BDM profile created successfully!');
    }

    public function show(BDM $employee)
    {
        $employee->load(['user', 'documents', 'salaries', 'leaveBalance', 'leaveApplications', 'targets']);
        return view('admin.employees.show', compact('employee'));
    }

    public function edit(BDM $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, BDM $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'highest_education' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $employee->user_id,
            'phone' => 'required|string|max:20',
            'current_ctc' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive,terminated',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle profile image
        if ($request->hasFile('profile_image')) {
            // Delete old image
            if ($employee->profile_image) {
                Storage::disk('public')->delete($employee->profile_image);
            }
            $profileImagePath = $request->file('profile_image')->store('bdm/profiles', 'public');
            $employee->profile_image = $profileImagePath;
        }

        // Update BDM
        $employee->update([
            'name' => $request->name,
            'father_name' => $request->father_name,
            'date_of_birth' => $request->date_of_birth,
            'highest_education' => $request->highest_education,
            'email' => $request->email,
            'phone' => $request->phone,
            'current_ctc' => $request->current_ctc,
            'status' => $request->status,
        ]);

        // Update user email
        $employee->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.employees.show', $employee->id)
            ->with('success', 'BDM profile updated successfully!');
    }

    public function destroy(BDM $employee)
    {
        // Soft delete or mark as terminated
        $employee->update([
            'status' => 'terminated',
            'termination_date' => Carbon::now(),
            'can_login' => false,
        ]);

        return redirect()->route('admin.employees.index')
            ->with('success', 'BDM terminated successfully!');
    }

    public function deactivate(BDM $employee)
    {
        $employee->update([
            'status' => 'inactive',
            'can_login' => false,
        ]);

        return back()->with('success', 'BDM deactivated successfully!');
    }

    public function activate(BDM $employee)
    {
        $employee->update([
            'status' => 'active',
            'can_login' => true,
        ]);

        return back()->with('success', 'BDM activated successfully!');
    }

    public function terminate(Request $request, BDM $employee)
    {
        $request->validate([
            'termination_reason' => 'required|string',
        ]);

        $employee->update([
            'status' => 'terminated',
            'termination_date' => Carbon::now(),
            'termination_reason' => $request->termination_reason,
            'can_login' => false,
        ]);

        // Send termination email
        // Mail::to($employee->email)->send(new BDMTerminationNotification($employee));

        return redirect()->route('admin.employees.index')
            ->with('success', 'BDM terminated successfully!');
    }
}
