<?php

namespace Database\Firebase;

use App\Services\FirebaseService;

class FirebaseMigration
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function run(): void
    {
        // Create empty root structure
        $this->firebase->createEmptyStructure([
            'users' => [],
            'roles' => [],
            'page_permissions' => [],
            'leaves' => [],
            'settings' => [
                'system_name' => 'Leave Management System',
            ],
        ]);
    }
}
