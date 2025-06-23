<?php

namespace Database\Firebase;

use Illuminate\Support\Facades\Hash;
use App\Services\FirebaseService;

class FirebaseSAdminSeeder
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function run(): void
    {
        // Check if superadmin already seeded
        $users = $this->firebase->getUsers();
        $existing = collect($users ?? [])->firstWhere('email', 'superadmin@mail.com');

        if ($existing) return;

        $id = uniqid('user_');
        $this->firebase->createUser($id, [
            'name' => 'Super Admin',
            'email' => 'superadmin@mail.com',
            'role' => 'superadmin',
            'password' => Hash::make('admin123'),
        ]);
    }
}
