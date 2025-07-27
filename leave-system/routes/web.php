<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckPagePermission;
use App\Http\Controllers\{
    AccountController,
    AnnualLeaveBalanceController,
    DashboardController,
    PublicHolidayController,
    LeaveController,
    NotificationController,
    PagePermissionController,
    ProfileController,
    // ReportController,
    SystemSettingController,
    UserController
};

// 🔹 Public Root
Route::get('/', fn () => view('welcome'));

// 🔒 Authenticated Dashboard
Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 🔐 Profile
Route::middleware('auth')->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
    // 🧑‍💼 User Management
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');

    // 👤 Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    // 📝 Leave
    Route::get('/leave', [LeaveController::class, 'index'])->name('leave.index');
    Route::get('/leave/apply', [LeaveController::class, 'create'])->name('leave.apply');
    Route::post('/leave/store', [LeaveController::class, 'store'])->name('leave.store');
    Route::get('/leave/history', [LeaveController::class, 'history'])->name('leave.history');
    Route::get('/leave/calendar', [LeaveController::class, 'calendar'])->name('leave.calendar');
    Route::get('/leave/calendar/data', [LeaveController::class, 'calendarData'])->name('leave.calendar.data');
    Route::get('/leave/manage', [LeaveController::class, 'manage'])->name('leave.manage');
    Route::get('/leave/report', [LeaveController::class, 'report'])->name('leave.report');

    // 🧾 Balance
    Route::get('/balance', [AnnualLeaveBalanceController::class, 'index'])->name('balance.index');
    Route::post('/balance/update', [AnnualLeaveBalanceController::class, 'update'])->name('balance.update');
    Route::post('/balance/store', [AnnualLeaveBalanceController::class, 'store'])->name('balance.store');
    Route::delete('/balance/{id}', [AnnualLeaveBalanceController::class, 'destroy'])->name('balance.destroy');

    // ⚙️ System Settings
    Route::get('/system/settings', [SystemSettingController::class, 'index'])->name('system.index');
    Route::post('/system/settings/updateAnnualLeave', [SystemSettingController::class, 'updateAnnualLeave'])->name('system.updateAnnualLeave');
    Route::post('/system/settings/updateSystemInfo', [SystemSettingController::class, 'updateSystemInfo'])->name('system.updateSystemInfo');
    Route::post('/system/settings/storeLeaveType', [SystemSettingController::class, 'storeLeaveType'])->name('system.storeLeaveType');
    Route::delete('/system/settings/deleteLeaveType/{id}', [SystemSettingController::class, 'deleteLeaveType'])->name('system.deleteLeaveType');

    // 📅 Public Holidays
    Route::get('/holidays', [PublicHolidayController::class, 'index'])->name('public_holiday.index');
    Route::post('/holidays/store', [PublicHolidayController::class, 'store'])->name('public_holiday.store');
    Route::post('/holidays/update/{id}', [PublicHolidayController::class, 'update'])->name('public_holiday.update');
    Route::delete('/holidays/delete/{id}', [PublicHolidayController::class, 'destroy'])->name('public_holiday.destroy');
});

// 🔹 Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    
Route::get('/notifications/read/{id}', function ($id) {
    $notification = auth()->user()->notifications()->findOrFail($id);
    $notification->markAsRead();

    $leaveId = $notification->data['leave_id'] ?? null;
    $roleId = auth()->user()->role_id;

    if ($leaveId) {
        if (in_array($roleId, [1, 2])) {
            // Superadmin 或 Management → Leave Manage
            return redirect()->route('leave.manage', ['highlight' => $leaveId]);
        } else {
            // 普通员工 → Leave History
            return redirect()->route('leave.history', ['highlight' => $leaveId]);
        }
    }

    return redirect('/dashboard');
})->name('notifications.read');


// ✅ Leave Approval (Manager/Admin)
Route::middleware(['auth', 'can:approve-leave'])->group(function () {
    Route::post('/leave/{id}/approve', [LeaveController::class, 'approve'])->name('leave.approve');
    Route::post('/leave/reject', [LeaveController::class, 'reject'])->name('leave.reject');
});

// 👤 Account Settings
Route::middleware('auth')->group(function () {
    Route::get('/account/settings', [AccountController::class, 'edit'])->name('account.settings');
    Route::post('/account/settings', [AccountController::class, 'update']);
    Route::post('/account/change-password', [AccountController::class, 'changePassword'])->name('account.change-password');
});

// 🛡️ Auth routes
require __DIR__.'/auth.php';
