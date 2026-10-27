<?php

use App\Http\Controllers\Admin\AuditLedgerController;
use App\Http\Controllers\Admin\CapitalShareController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ReceiptLogController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\UserHomeController;
use App\Http\Middleware\AdminGuestOnly;
use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\BorrowerOnly;
use App\Http\Middleware\GuestOnly;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route(Auth::guard('web')->check() ? 'home' : 'login');
});

Route::middleware(GuestOnly::class)->group(function () {
    Route::get('/login', [UserAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [UserAuthController::class, 'login'])
        ->middleware('throttle:6,1')
        ->name('login.attempt');

    Route::get('/register', [UserAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register'])
        ->middleware('throttle:10,1')
        ->name('register.store');

    Route::get('/forgot-password', [UserAuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [UserAuthController::class, 'sendResetLink'])
        ->middleware('throttle:5,1')
        ->name('password.email');

    Route::get('/reset-password/{token}', [UserAuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [UserAuthController::class, 'resetPassword'])
        ->middleware('throttle:5,1')
        ->name('password.update');

    Route::get('/auth/google', function () {
        return redirect()->route('login')->with('info', 'Google sign-in is not available yet.');
    })->name('auth.google');
});

Route::middleware(BorrowerOnly::class)->group(function () {
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
    Route::get('/home', [UserHomeController::class, 'index'])->name('home');
    Route::post('/loan-applications', [UserHomeController::class, 'store'])->name('loan-applications.store');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(AdminGuestOnly::class)->group(function () {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('login.attempt');
    });

    Route::middleware(AdminOnly::class)->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/members', [MemberController::class, 'index'])->name('members.index');
        Route::get('/capital-shares', [CapitalShareController::class, 'index'])->name('capital-shares.index');
        Route::get('/receipt-logs', [ReceiptLogController::class, 'index'])->name('receipt-logs.index');
        Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
        Route::patch('/loans/{application}', [LoanController::class, 'update'])->name('loans.update');
        Route::get('/audit-ledger', [AuditLedgerController::class, 'index'])->name('audit-ledger.index');
    });
});