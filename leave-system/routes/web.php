<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckPagePermission;
use App\Http\Controllers\{
    AccountController,
    DashboardController,
    HolidayController,
    LeaveController,
    NotificationController,
    PagePermissionController,
    ProfileController,
    // ReportController,
    SystemController,
    UserController
};

// 🔹 Public Root
Route::get('/', fn () => view('welcome'));

// 🔒 Authenticated Dashboard
Route::get('/dashboard', fn () => view('dashboard'))
    ->middleware(['auth', 'verified'])->name('dashboard');

// 🔐 Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ User Management (All routes protected by Firebase-based permission check)
Route::middleware(['auth', CheckPagePermission::class])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');
});


// 🔐 Page Permission Management (Superadmin Only)
Route::middleware(['auth', 'ensure.superadmin'])->group(function () {
    Route::get('/page-permission', [PagePermissionController::class, 'index'])->name('admin.page-permission');
    Route::get('/admin/page-permissions', [PagePermissionController::class, 'index'])->name('admin.page-permissions');
    Route::post('/admin/page-permissions/update/{key}', [PagePermissionController::class, 'update'])
        ->where('key', '.*')
        ->name('admin.page-permissions.update');
});

// 🔒 Routes enforced by Firebase-based permission check
Route::middleware(['auth', CheckPagePermission::class])->group(function () {
    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');
});


    // 🔹 Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    
    Route::get('/notifications/read/{id}', function ($id) {
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();
    return redirect($notification->data['url'] ?? '/dashboard');
    })->name('notifications.read');

// 🗓️ Leave Management
Route::middleware('auth')->group(function () {
    Route::get('/leave', [LeaveController::class, 'index'])->name('leave.index'); // 管理页面（显示所有记录）
    Route::get('/leave/apply', [LeaveController::class, 'create'])->name('leave.apply');
    Route::post('/leave/store', [LeaveController::class, 'store'])->name('leave.store');
    Route::get('/leave/history', [LeaveController::class, 'history'])->name('leave.history');
    Route::get('/leave/calendar', [LeaveController::class, 'calendar'])->name('leave.calendar');
    Route::get('/leave/manage', [LeaveController::class, 'manage'])->name('leave.manage');
});

// ✅ Leave Approval (Manager/Admin)
Route::middleware(['auth', 'can:approve-leave'])->group(function () {
    Route::post('/leave/{id}/approve', [LeaveController::class, 'approve'])->name('leave.approve');
    Route::post('/leave/reject', [LeaveController::class, 'reject'])->name('leave.reject');
});

// ⚙️ System Settings + Admin-Only Panels
Route::middleware(['auth', 'can:is-admin'])->group(function () {
    // Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    // Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays');
    
    // Route::get('/system/settings', [SystemController::class, 'index'])->name('system.settings');
    // Route::post('/system/settings', [SystemController::class, 'update']);
});

// 👤 Account Settings
Route::middleware('auth')->group(function () {
    // Route::get('/account/settings', [AccountController::class, 'edit'])->name('account.settings');
    // Route::post('/account/settings', [AccountController::class, 'update']);
});

// 🛡️ Auth routes
require __DIR__.'/auth.php';
