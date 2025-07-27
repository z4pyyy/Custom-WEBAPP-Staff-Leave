<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\FirebaseService;

class FirebasePagePermissionSeeder extends Seeder
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function run(): void
    {
        // 🛡️ Default page permission configuration
        $permissions = [
            // Dashboard
            'dashboard' => [
                'description' => 'Access to dashboard',
                'allowed_roles' => [1, 2, 3],
            ],

            // User Management
            'admin.users' => [
                'description' => 'Allow access to view user list page',
                'allowed_roles' => [1, 2],
            ],

            // Profile
            'profile.edit' => [
                'description' => 'Allow user to edit their own profile',
                'allowed_roles' => [1, 2, 3],
            ],

            // Leave Management
            'leave.index' => [
                'description' => 'View list of all leave records',
                'allowed_roles' => [1, 2, 3],
            ],
            'leave.apply' => [
                'description' => 'Apply for leave',
                'allowed_roles' => [1, 2, 3],
            ],
            'leave.store' => [
                'description' => 'Store new leave application',
                'allowed_roles' => [1, 2, 3],
            ],
            'leave.history' => [
                'description' => 'View own leave history',
                'allowed_roles' => [1, 2, 3],
            ],
            'leave.calendar' => [
                'description' => 'View leave calendar',
                'allowed_roles' => [1, 2, 3],
            ],
            'leave.calendar_data' => [
                'description' => 'Load leave calendar events',
                'allowed_roles' => [1, 2, 3],
            ],
            'leave.manage' => [
                'description' => 'Approve/Reject leave (Management only)',
                'allowed_roles' => [1, 2],
            ],

            // Leave Report
            'leave.report' => [
                'description' => 'Generate & view leave reports',
                'allowed_roles' => [1, 2],
            ],

            // Annual Leave Balance
            'balance.index' => [
                'description' => 'View annual leave balance',
                'allowed_roles' => [1, 2],
            ],
            'balance.update' => [
                'description' => 'Update annual leave balance',
                'allowed_roles' => [1, 2],
            ],
            'balance.store' => [
                'description' => 'Store annual leave balance',
                'allowed_roles' => [1, 2],
            ],
            'balance.destroy' => [
                'description' => 'Delete annual leave record',
                'allowed_roles' => [1, 2],
            ],

            // Public Holidays
            'public_holiday.index' => [
                'description' => 'View/manage public holidays',
                'allowed_roles' => [1, 2],
            ],
            'public_holiday.store' => [
                'description' => 'Store public holiday',
                'allowed_roles' => [1, 2],
            ],
            'public_holiday.update' => [
                'description' => 'Update public holiday',
                'allowed_roles' => [1, 2],
            ],
            'public_holiday.destroy' => [
                'description' => 'Delete public holiday',
                'allowed_roles' => [1, 2],
            ],

            // System Settings
            'system.index' => [
                'description' => 'Access system settings',
                'allowed_roles' => [1, 2],
            ],
            'system.updateAnnualLeave' => [
                'description' => 'Update annual leave rule',
                'allowed_roles' => [1, 2],
            ],
            'system.updateSystemInfo' => [
                'description' => 'Update system info',
                'allowed_roles' => [1, 2],
            ],
            'system.storeLeaveType' => [
                'description' => 'Add leave type',
                'allowed_roles' => [1, 2],
            ],
            'system.deleteLeaveType' => [
                'description' => 'Delete leave type',
                'allowed_roles' => [1, 2],
            ],

            // Page Permission Settings
            'admin.page_permission' => [
                'description' => 'Access page permission management',
                'allowed_roles' => [1],
            ],
            'admin.page_permissions' => [
                'description' => 'Access page permission panel',
                'allowed_roles' => [1],
            ],
            'admin.page_permissions.update' => [
                'description' => 'Update Firebase page permissions',
                'allowed_roles' => [1],
            ],
        ];

        // 🔁 Seed all page permissions into Firebase
        foreach ($permissions as $key => $value) {
            $firebaseKey = str_replace('.', '_', $key);

            $this->firebase->set("page_permissions/{$firebaseKey}", [
                'description' => $value['description'],
                'allowed_roles' => $value['allowed_roles'],
            ]);
        }
    }
}
