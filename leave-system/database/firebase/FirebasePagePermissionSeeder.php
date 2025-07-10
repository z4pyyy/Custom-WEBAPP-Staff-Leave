<?php

namespace Database\Firebase;

use App\Services\FirebaseService;

class FirebasePagePermissionSeeder
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
            'admin_users' => [
                'description'   => 'Allow access to view user list page',
                'allowed_roles' => [1], // Only Superadmin
            ],
            'admin_users_create' => [
                'description'   => 'Allow access to view add user form',
                'allowed_roles' => [1], // Only Superadmin
            ],
            'admin_users_store' => [
                'description'   => 'Allow access to create a new user',
                'allowed_roles' => [1], // Only Superadmin
            ],
            'admin_users_edit' => [
                'description'   => 'Allow access to edit a user',
                'allowed_roles' => [1], // Only Superadmin
            ],
            'admin_users_update' => [
                'description'   => 'Allow access to update a user',
                'allowed_roles' => [1], // Only Superadmin
            ],
            'admin_users_delete' => [
                'description'   => 'Allow access to delete a user',
                'allowed_roles' => [1], // Only Superadmin
            ],
        ];

        // 🔁 Seed all page permissions into Firebase
        foreach ($permissions as $key => $config) {
            $this->firebase->set("page_permissions/{$key}", [
                'allowed_roles' => $config['allowed_roles'],
                'description'   => $config['description'],
            ]);
        }
    }
}
