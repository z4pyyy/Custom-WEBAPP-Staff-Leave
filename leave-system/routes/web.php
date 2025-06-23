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
use Kreait\Firebase\Factory;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
// 🔸 临时通知测试用
Route::get('/test-notification', function () {
    $user = auth()->user();

    $dummyLeave = (object)[
        'id' => 999,
        'user' => (object)[ 'name' => $user->name ],
        'created_at' => now(),
    ];

    $user->notify(new \App\Notifications\LeaveRequestNotification($dummyLeave));

    return redirect('/dashboard')->with('success', 'Test notification sent!');
});

    // 🔹 Leave Management
    Route::get('/leave', [LeaveController::class, 'index'])->name('leave.index');
    Route::get('/leave/apply', [LeaveController::class, 'create'])->name('leave.apply');
    Route::post('/leave/apply', [LeaveController::class, 'store']);
    Route::get('/leave/history', [LeaveController::class, 'history'])->name('leave.history');
    Route::get('/leave/calendar', [LeaveController::class, 'calendar'])->name('leave.calendar');
    Route::post('/leave/store', [LeaveController::class, 'store'])->name('leave.store');

    // 🔹 Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    // ✅ 点击通知时标记为已读 + 跳转
    Route::get('/notifications/read/{id}', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return redirect($notification->data['url'] ?? '/dashboard');
    })->name('notifications.read');

    // 🔹 Account Settings
    Route::get('/account/settings', [AccountController::class, 'edit'])->name('account.settings');
    Route::post('/account/settings', [AccountController::class, 'update']);

    // 🔸 Manager & Admin
    Route::middleware(['can:approve-leave'])->group(function () {
        Route::get('/leave/approve', [LeaveController::class, 'approvePage'])->name('leave.approve');
        Route::post('/leave/approve/{id}', [LeaveController::class, 'processApproval']);
        Route::get('/leave/approve/{id}', [LeaveController::class, 'showApprovalForm'])->name('leave.approve.single');
    });

    // 🔸 Admin Only
    Route::middleware(['can:is-admin'])->group(function () {
        Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::post('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
        Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays');
        Route::get('/system/settings', [SystemController::class, 'index'])->name('system.settings');
        Route::post('/system/settings', [SystemController::class, 'update']);
    });
});
require __DIR__.'/auth.php';
