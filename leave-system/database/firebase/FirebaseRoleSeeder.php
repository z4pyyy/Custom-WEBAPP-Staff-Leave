<?php

namespace Database\Firebase;

use App\Services\FirebaseService;

class FirebaseRoleSeeder
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function run(): void
    {
        $roles = [
            1 => ['name' => 'superadmin'],
            2 => ['name' => 'management'],
            3 => ['name' => 'employee'],
        ];

        foreach ($roles as $id => $data) {
            $this->firebase->set('roles/' . $id, $data);
        }
    }
}
