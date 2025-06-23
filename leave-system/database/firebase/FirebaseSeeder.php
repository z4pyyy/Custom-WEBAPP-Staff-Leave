<?php

namespace Database\Firebase;

use Illuminate\Support\Facades\Hash;
use App\Services\FirebaseService;

class FirebaseSeeder
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function run(): void
    {
        // Seed roles
        (new FirebaseRoleSeeder($this->firebase))->run();

        // Seed page permissions
        (new FirebasePagePermissionSeeder($this->firebase))->run();

        // Seed Super Admin
        $users = $this->firebase->getUsers();
        $exists = collect($users ?? [])->firstWhere('email', 'superadmin@gmail.com');

        if (!$exists) {
            $id = uniqid('user_');

            $this->firebase->createUser($id, [
                'name'     => 'Super Admin',
                'email'    => 'superadmin@gmail.com',
                'password' => Hash::make('admin123'),
                'role_id'  => 1
            ]);
        }
    }
}
