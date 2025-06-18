<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\AccountController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->group(function () {

    // 🔹 Leave Management
    Route::get('/leave/apply', [LeaveController::class, 'create'])->name('leave.apply');
    Route::post('/leave/apply', [LeaveController::class, 'store']);
    Route::get('/leave/history', [LeaveController::class, 'history'])->name('leave.history');
    Route::get('/leave/calendar', [LeaveController::class, 'calendar'])->name('leave.calendar');

    // 🔹 Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

    // 🔹 Account Settings
    Route::get('/account/settings', [AccountController::class, 'edit'])->name('account.settings');
    Route::post('/account/settings', [AccountController::class, 'update']);

    // 🔸 Manager & Admin
    Route::middleware(['can:approve-leave'])->group(function () {
        Route::get('/leave/approve', [LeaveController::class, 'approvePage'])->name('leave.approve');
        Route::post('/leave/approve/{id}', [LeaveController::class, 'processApproval']);
    });

    // 🔸 Admin Only
    Route::middleware(['can:is-admin'])->group(function () {
        Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
        Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays');
        Route::get('/system/settings', [SystemController::class, 'index'])->name('system.settings');
        Route::post('/system/settings', [SystemController::class, 'update']);
    });
});

require __DIR__.'/auth.php';
