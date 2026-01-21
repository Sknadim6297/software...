<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\BDMController;
use App\Http\Controllers\ServiceRenewalController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Artisan;

// Employee Controllers
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\BDMLeaveSalaryController;

// Admin Controllers
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\AdminSalaryController;
use App\Http\Controllers\Admin\AdminLeaveController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\TargetController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\BDMLeaveSalaryController as AdminBDMLeaveSalaryController;

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('admin.guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    });
    
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Admin Panel Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Attendance Management
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AdminAttendanceController::class, 'dashboard'])->name('dashboard');
        Route::get('/all', [AdminAttendanceController::class, 'index'])->name('index');
        Route::get('/{attendance}/edit', [AdminAttendanceController::class, 'edit'])->name('edit');
        Route::put('/{attendance}', [AdminAttendanceController::class, 'update'])->name('update');
        Route::post('/add-manual', [AdminAttendanceController::class, 'addManual'])->name('add-manual');
        Route::post('/{attendance}/unlock-checkout', [AdminAttendanceController::class, 'unlockCheckout'])->name('unlock-checkout');
        Route::post('/{attendance}/remove-penalty', [AdminAttendanceController::class, 'removePenalty'])->name('remove-penalty');
        Route::get('/employee/{user}/history', [AdminAttendanceController::class, 'employeeHistory'])->name('employee-history');
        Route::get('/settings', [AdminAttendanceController::class, 'settings'])->name('settings');
        Route::post('/settings/update', [AdminAttendanceController::class, 'updateSettings'])->name('update-settings');
        Route::get('/holidays', [AdminAttendanceController::class, 'holidays'])->name('holidays');
        Route::post('/holidays/add', [AdminAttendanceController::class, 'addHoliday'])->name('add-holiday');
        Route::delete('/holidays/{holiday}', [AdminAttendanceController::class, 'deleteHoliday'])->name('delete-holiday');
    });
    
    // Salary Management
    Route::prefix('salary')->name('salary.')->group(function () {
        Route::get('/', [AdminSalaryController::class, 'index'])->name('index');
        Route::get('/{salary}', [AdminSalaryController::class, 'show'])->name('show');
        Route::get('/employee/{user}/settings', [AdminSalaryController::class, 'editSettings'])->name('settings');
        Route::put('/employee/{user}/settings', [AdminSalaryController::class, 'updateSettings'])->name('update-settings');
        Route::post('/generate-monthly', [AdminSalaryController::class, 'generateMonthly'])->name('generate-monthly');
        Route::post('/process-month', [AdminSalaryController::class, 'processMonth'])->name('process-month');
        Route::post('/export', [AdminSalaryController::class, 'exportSheet'])->name('export');
        Route::post('/email-payslips', [AdminSalaryController::class, 'emailPayslips'])->name('email-payslips');
        Route::get('/reports', [AdminSalaryController::class, 'report'])->name('report');
    });
    
    // BDM Leave & Salary Management (New Module)
    Route::prefix('leave-salary')->name('leave-salary.')->group(function () {
        // Leave Management
        Route::prefix('leaves')->name('leaves.')->group(function () {
            Route::get('/', [AdminBDMLeaveSalaryController::class, 'index'])->name('index');
            Route::get('/{leave}', [AdminBDMLeaveSalaryController::class, 'show'])->name('show');
            Route::post('/{leave}/approve', [AdminBDMLeaveSalaryController::class, 'approve'])->name('approve');
            Route::post('/{leave}/reject', [AdminBDMLeaveSalaryController::class, 'reject'])->name('reject');
            Route::get('/balances', [AdminBDMLeaveSalaryController::class, 'leaveBalances'])->name('balances');
            Route::post('/allocate/{bdm}', [AdminBDMLeaveSalaryController::class, 'setLeaveAllocation'])->name('allocate');
            Route::get('/reports/monthly', [AdminBDMLeaveSalaryController::class, 'monthlyLeaveReport'])->name('monthly-report');
        });
        
        // Salary Management
        Route::prefix('salary')->name('salary.')->group(function () {
            Route::get('/', [AdminBDMLeaveSalaryController::class, 'salaryIndex'])->name('index');
            Route::get('/{salary}', [AdminBDMLeaveSalaryController::class, 'salaryShow'])->name('show');
            Route::post('/{salary}/regenerate', [AdminBDMLeaveSalaryController::class, 'regenerateSalary'])->name('regenerate');
            Route::post('/generate-monthly', [AdminBDMLeaveSalaryController::class, 'generateMonthlySalaries'])->name('generate-monthly');
            Route::post('/process-month', [AdminBDMLeaveSalaryController::class, 'processMonthSalaries'])->name('process-month');
            Route::post('/export', [AdminBDMLeaveSalaryController::class, 'exportSalarySheet'])->name('export');
            Route::post('/email-payslips', [AdminBDMLeaveSalaryController::class, 'emailPayslips'])->name('email-payslips');
        });
    });
    
    // BDM Leave Management (Old routes - to be deprecated)
    Route::prefix('admin/leaves')->name('admin.leaves.')->group(function () {
        Route::get('/', [AdminLeaveController::class, 'index'])->name('index');
        Route::get('/balances', [AdminLeaveController::class, 'balances'])->name('balances');
        Route::get('/{leave}', [AdminLeaveController::class, 'show'])->name('show');
        Route::post('/{leave}/approve', [AdminLeaveController::class, 'approve'])->name('approve');
        Route::post('/{leave}/reject', [AdminLeaveController::class, 'reject'])->name('reject');
        Route::get('/bdm/{bdm}/history', [AdminLeaveController::class, 'employeeHistory'])->name('employee-history');
        Route::get('/reports/monthly', [AdminLeaveController::class, 'report'])->name('report');
        Route::get('/reports/summary', [AdminLeaveController::class, 'monthlySummary'])->name('summary');
    });
    
    // Employee Management
    Route::resource('employees', EmployeeController::class);
    Route::post('employees/{employee}/deactivate', [EmployeeController::class, 'deactivate'])->name('employees.deactivate');
    Route::post('employees/{employee}/activate', [EmployeeController::class, 'activate'])->name('employees.activate');
    Route::post('employees/{employee}/terminate', [EmployeeController::class, 'terminate'])->name('employees.terminate');
    
    // Document Management
    Route::get('employees/{employee}/documents', [DocumentController::class, 'show'])->name('documents.show');
    Route::post('employees/{employee}/documents/upload', [DocumentController::class, 'upload'])->name('documents.upload');
    Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    
    // Salary Management
    Route::resource('salaries', SalaryController::class);
    Route::get('salaries/{salary}/download', [SalaryController::class, 'download'])->name('salaries.download');
    Route::post('salaries/{salary}/upload-slip', [SalaryController::class, 'uploadSlip'])->name('salaries.upload-slip');
    
    // Target Management
    Route::resource('targets', TargetController::class);
    Route::post('targets/{target}/update-achievement', [TargetController::class, 'updateAchievement'])->name('targets.update-achievement');
    Route::get('targets-bulk/create', [TargetController::class, 'bulkCreate'])->name('targets.bulk-create');
    Route::post('targets-bulk/store', [TargetController::class, 'bulkStore'])->name('targets.bulk-store');
    
    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/target', [ReportController::class, 'targetReport'])->name('reports.target');
    Route::get('reports/salary', [ReportController::class, 'salaryReport'])->name('reports.salary');
    Route::get('reports/leave', [ReportController::class, 'leaveReport'])->name('reports.leave');
    Route::get('reports/performance', [ReportController::class, 'performanceReport'])->name('reports.performance');
    Route::get('reports/attendance', [ReportController::class, 'attendanceReport'])->name('reports.attendance');
    
    // Project Management (Admin)
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ProjectController::class, 'index'])->name('index');
        Route::get('/payments', [\App\Http\Controllers\Admin\ProjectController::class, 'payments'])->name('payments');
        Route::get('/maintenance', [\App\Http\Controllers\Admin\ProjectController::class, 'maintenance'])->name('maintenance');
        Route::get('/statistics', [\App\Http\Controllers\Admin\ProjectController::class, 'statistics'])->name('statistics');
        Route::get('/{project}', [\App\Http\Controllers\Admin\ProjectController::class, 'show'])->name('show');
    });
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/run-migrations', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return "Migrations ran successfully!";
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
    return 'Storage link created!';
});

// Password Reset Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Protected Routes (require authentication and BDM status check)
Route::middleware(['auth', 'bdm.check'])->group(function () {
    // Dashboard Route
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Customer Management Routes
    Route::resource('customers', CustomerController::class);
    Route::get('/customers-history', [CustomerController::class, 'history'])->name('customers.history');
    Route::get('/customers-debug', function () {
        return view('customers.debug');
    })->name('customers.debug');

    // Leads Management Routes
    Route::prefix('leads')->name('leads.')->group(function () {
        Route::get('/', [LeadController::class, 'allLeads'])->name('index');
        Route::get('/all', [LeadController::class, 'allLeads'])->name('all');
        Route::get('/incoming', [LeadController::class, 'incoming'])->name('incoming');
        Route::get('/outgoing', [LeadController::class, 'outgoing'])->name('outgoing');
        Route::get('/create/{type?}', [LeadController::class, 'create'])->name('create');
        Route::post('/store', [LeadController::class, 'store'])->name('store');
        Route::get('/{lead}', [LeadController::class, 'show'])->name('show');
        Route::get('/{lead}/edit', [LeadController::class, 'edit'])->name('edit');
        Route::put('/{lead}', [LeadController::class, 'update'])->name('update');
        Route::delete('/{lead}', [LeadController::class, 'destroy'])->name('destroy');

        // Lead Actions
        Route::post('/{lead}/schedule-callback', [LeadController::class, 'scheduleCallback'])->name('schedule-callback');
        Route::post('/{lead}/complete-callback', [LeadController::class, 'completeCallback'])->name('complete-callback');
        Route::post('/{lead}/cancel-callback', [LeadController::class, 'cancelCallback'])->name('cancel-callback');
        Route::post('/{lead}/postpone-callback', [LeadController::class, 'postponeCallback'])->name('postpone-callback');
        Route::post('/{lead}/schedule-meeting', [LeadController::class, 'scheduleMeeting'])->name('schedule-meeting');
        Route::post('/{lead}/complete-meeting', [LeadController::class, 'completeMeeting'])->name('complete-meeting');
        Route::post('/{lead}/cancel-meeting', [LeadController::class, 'cancelMeeting'])->name('cancel-meeting');
        Route::patch('/{lead}/update-meeting', [LeadController::class, 'updateMeeting'])->name('update-meeting');
        Route::post('/{lead}/postpone-meeting', [LeadController::class, 'postponeMeeting'])->name('postpone-meeting');

        // Additional Lead Actions
        Route::patch('/{lead}/update-status', [LeadController::class, 'updateStatus'])->name('update-status');
        Route::post('/{lead}/update-interested-status', [LeadController::class, 'updateInterestedStatus'])->name('update-interested-status');
        Route::post('/{lead}/convert-to-customer', [LeadController::class, 'convertToCustomer'])->name('convert-to-customer');
    });

    // API Routes for AJAX
    Route::prefix('api')->group(function () {
        Route::get('/check-meeting-limit', [LeadController::class, 'checkMeetingLimit']);
        Route::post('/check-meeting-availability', [LeadController::class, 'checkMeetingAvailability']);
        Route::post('/check-duplicate-contact', [LeadController::class, 'checkDuplicateContact']);
    });

    // Proposal Management Routes
    Route::prefix('proposals')->name('proposals.')->group(function () {
        Route::get('/', [ProposalController::class, 'index'])->name('index');
        Route::get('/create', [ProposalController::class, 'create'])->name('create');
        Route::get('/select-customer', [ProposalController::class, 'selectCustomer'])->name('select-customer');
        Route::post('/select-customer', [ProposalController::class, 'selectCustomer']);
        Route::get('/create-with-customer', [ProposalController::class, 'createWithCustomer'])->name('create-with-customer');
        Route::post('/store', [ProposalController::class, 'store'])->name('store');
        Route::post('/store-social-media', [ProposalController::class, 'storeSocialMedia'])->name('store-social-media');
        Route::post('/store-erp-software', [ProposalController::class, 'storeErpSoftware'])->name('store-erp-software');
        Route::post('/store-app-website', [ProposalController::class, 'storeAppWebsite'])->name('store-app-website');
        Route::get('/{proposal}', [ProposalController::class, 'show'])->name('show');
        Route::get('/{proposal}/edit', [ProposalController::class, 'edit'])->name('edit');
        Route::put('/{proposal}', [ProposalController::class, 'update'])->name('update');
        Route::delete('/{proposal}', [ProposalController::class, 'destroy'])->name('destroy');

        // Proposal Actions
        Route::post('/{proposal}/send', [ProposalController::class, 'send'])->name('send');
        Route::post('/{proposal}/mark-viewed', [ProposalController::class, 'markViewed'])->name('mark-viewed');
        Route::post('/{proposal}/accept', [ProposalController::class, 'accept'])->name('accept');
        Route::post('/{proposal}/reject', [ProposalController::class, 'reject'])->name('reject');
        
        // View Agreement as Webpage
        Route::get('/{proposal}/agreement', [ProposalController::class, 'viewAgreement'])->name('agreement');
        
        // View Contract as Webpage (for accepted proposals)
        Route::get('/{proposal}/contract', [ProposalController::class, 'viewContract'])->name('contract');
    });

    // Contract Management Routes
    Route::prefix('contracts')->name('contracts.')->group(function () {
        Route::get('/', [ContractController::class, 'index'])->name('index');
        Route::get('/{contract}', [ContractController::class, 'show'])->name('show');
        Route::get('/{contract}/edit', [ContractController::class, 'edit'])->name('edit');
        Route::put('/{contract}', [ContractController::class, 'update'])->name('update');
        Route::post('/{contract}/cancel', [ContractController::class, 'cancel'])->name('cancel');
        Route::post('/{contract}/complete', [ContractController::class, 'complete'])->name('complete');
    });

    // Invoice Management Routes
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/export/excel', [InvoiceController::class, 'exportExcel'])->name('invoices.export.excel');
    Route::get('/invoices/export/pdf', [InvoiceController::class, 'exportPdf'])->name('invoices.export.pdf');
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
    Route::get('/invoices/contract/{id}/details', [InvoiceController::class, 'getContractDetails'])->name('invoices.contract.details');
    Route::post('/invoices/get-invoice-number', [InvoiceController::class, 'getInvoiceNumber'])->name('invoices.get-invoice-number');

    // Attendance Management Routes
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/today', [AttendanceController::class, 'today'])->name('today');
        Route::post('/check-in', [AttendanceController::class, 'checkIn'])->name('check-in');
        Route::post('/check-out', [AttendanceController::class, 'checkOut'])->name('check-out');
        Route::get('/select-date', [AttendanceController::class, 'selectDate'])->name('select-date');
        Route::get('/month-history', [AttendanceController::class, 'monthHistory'])->name('month-history');
        Route::get('/monthly-summary', [AttendanceController::class, 'monthlySummary'])->name('monthly-summary');
    });

    // Salary Management Routes
    Route::prefix('salary')->name('salary.')->group(function () {
        Route::get('/', [SalaryController::class, 'index'])->name('index');
        Route::get('/{salary}', [SalaryController::class, 'show'])->name('show');
        Route::get('/{salary}/download', [SalaryController::class, 'downloadPayslip'])->name('download');
        Route::get('/current/calculate', [SalaryController::class, 'calculateCurrent'])->name('calculate-current');
    });

    // BDM Panel Routes
    Route::prefix('bdm')->name('bdm.')->group(function () {
        Route::get('/dashboard', [BDMController::class, 'dashboard'])->name('dashboard');
        
        // Profile
        Route::get('/profile', [BDMController::class, 'showProfile'])->name('profile');
        Route::post('/profile', [BDMController::class, 'updateProfile'])->name('profile.update');
        
        // Documents
        Route::get('/documents', [BDMController::class, 'showDocuments'])->name('documents');
        Route::post('/documents/upload', [BDMController::class, 'uploadDocument'])->name('documents.upload');
        Route::get('/documents/{id}/download', [BDMController::class, 'downloadDocument'])->name('documents.download');
        Route::delete('/documents/{id}', [BDMController::class, 'deleteDocument'])->name('documents.delete');
        
        // Salary
        Route::get('/salary', [BDMController::class, 'showSalary'])->name('salary');
        Route::get('/salary/{id}/download', [BDMController::class, 'downloadSalarySlip'])->name('salary.download');
        
        // Leaves
        Route::get('/leaves', [BDMController::class, 'showLeaves'])->name('leaves');
        Route::post('/leaves/apply', [BDMController::class, 'applyLeave'])->name('leaves.apply');
        
        // New Leave & Salary Slip Module
        Route::prefix('leave-salary')->name('leave-salary.')->group(function () {
            Route::get('/', [BDMLeaveSalaryController::class, 'index'])->name('index');
            Route::get('/apply', [BDMLeaveSalaryController::class, 'applyLeaveForm'])->name('apply-form');
            Route::post('/apply', [BDMLeaveSalaryController::class, 'applyLeave'])->name('apply');
            Route::get('/history', [BDMLeaveSalaryController::class, 'leaveHistory'])->name('history');
            Route::get('/{salary}/download', [BDMLeaveSalaryController::class, 'downloadSalarySlip'])->name('salary-download');
            Route::get('/{salary}/details', [BDMLeaveSalaryController::class, 'salarySlipDetails'])->name('salary-details');
        });
        
        // Targets
        Route::get('/targets', [BDMController::class, 'showTargets'])->name('targets');
        Route::get('/targets/{id}', [BDMController::class, 'showTargetDetail'])->name('targets.detail');
        
        // Notifications
        Route::get('/notifications', [BDMController::class, 'showNotifications'])->name('notifications');
        Route::post('/notifications/{id}/read', [BDMController::class, 'markNotificationRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [BDMController::class, 'markAllNotificationsRead'])->name('notifications.read-all');
    });

    // Service Renewal Management Routes
    Route::prefix('service-renewals')->name('service-renewals.')->group(function () {
        Route::get('/', [ServiceRenewalController::class, 'index'])->name('index');
        Route::get('/create', [ServiceRenewalController::class, 'create'])->name('create');
        Route::post('/store', [ServiceRenewalController::class, 'store'])->name('store');
        Route::get('/{serviceRenewal}', [ServiceRenewalController::class, 'show'])->name('show');
        Route::get('/{serviceRenewal}/edit', [ServiceRenewalController::class, 'edit'])->name('edit');
        Route::put('/{serviceRenewal}', [ServiceRenewalController::class, 'update'])->name('update');
        Route::delete('/{serviceRenewal}', [ServiceRenewalController::class, 'destroy'])->name('destroy');
        
        // Service Renewal Actions
        Route::post('/{serviceRenewal}/process-renewal', [ServiceRenewalController::class, 'processRenewal'])->name('process-renewal');
        Route::post('/{serviceRenewal}/verify', [ServiceRenewalController::class, 'verifyRenewal'])->name('verify');
        Route::post('/{serviceRenewal}/stop-renewal', [ServiceRenewalController::class, 'stopRenewal'])->name('stop-renewal');
        Route::post('/{serviceRenewal}/send-reminder', [ServiceRenewalController::class, 'sendRenewalReminder'])->name('send-reminder');
    });

    // Project Management Routes
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::post('/store', [ProjectController::class, 'store'])->name('store');
        Route::get('/{project}', [ProjectController::class, 'show'])->name('show');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->name('edit');
        Route::put('/{project}', [ProjectController::class, 'update'])->name('update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('destroy');
        
        // Project Payment Actions
        Route::get('/{project}/take-payment', [ProjectController::class, 'takePayment'])->name('take-payment');
        Route::post('/{project}/process-payment', [ProjectController::class, 'processPayment'])->name('process-payment');
        
        // Project Completion
        Route::get('/{project}/complete', [ProjectController::class, 'complete'])->name('complete');
        Route::post('/{project}/store-completion', [ProjectController::class, 'storeCompletion'])->name('store-completion');
    });

});
