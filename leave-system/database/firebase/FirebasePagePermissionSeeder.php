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
        // 🔐 Page permissions (Superadmin + Management)
        $permissions = [
            'admin_users'         => 'Allow access to view user list page',
            'admin_users_edit'    => 'Allow access to edit a user',
            'admin_users_update'  => 'Allow access to update a user',
            'admin_users_delete'  => 'Allow access to delete a user',
        ];

        foreach ($permissions as $key => $desc) {
            $this->firebase->set("page_permissions/{$key}", [
                'allowed_roles' => [1, 2],
                'description'   => $desc,
            ]);
        }
    }
}
