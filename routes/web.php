<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Dashboard Route
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Customer Management Routes
    Route::resource('customers', CustomerController::class);
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
        Route::post('/{lead}/schedule-meeting', [LeadController::class, 'scheduleMeeting'])->name('schedule-meeting');
        Route::post('/{lead}/update-status', [LeadController::class, 'updateStatus'])->name('update-status');
        Route::post('/{lead}/convert-to-customer', [LeadController::class, 'convertToCustomer'])->name('convert-to-customer');
    });

    // API Routes for AJAX
    Route::prefix('api')->group(function () {
        Route::get('/check-meeting-limit', [LeadController::class, 'checkMeetingLimit']);
    });

    // Proposal Management Routes
    Route::prefix('proposals')->name('proposals.')->group(function () {
        Route::get('/', [ProposalController::class, 'index'])->name('index');
        Route::get('/create', [ProposalController::class, 'create'])->name('create');
        Route::post('/select-customer', [ProposalController::class, 'selectCustomer'])->name('select-customer');
        Route::get('/create-with-customer', [ProposalController::class, 'createWithCustomer'])->name('create-with-customer');
        Route::post('/store', [ProposalController::class, 'store'])->name('store');
        Route::get('/{proposal}', [ProposalController::class, 'show'])->name('show');
        Route::get('/{proposal}/edit', [ProposalController::class, 'edit'])->name('edit');
        Route::put('/{proposal}', [ProposalController::class, 'update'])->name('update');
        Route::delete('/{proposal}', [ProposalController::class, 'destroy'])->name('destroy');
        
        // Proposal Actions
        Route::post('/{proposal}/send', [ProposalController::class, 'send'])->name('send');
        Route::post('/{proposal}/mark-viewed', [ProposalController::class, 'markViewed'])->name('mark-viewed');
        Route::post('/{proposal}/accept', [ProposalController::class, 'accept'])->name('accept');
        Route::post('/{proposal}/reject', [ProposalController::class, 'reject'])->name('reject');
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
});
