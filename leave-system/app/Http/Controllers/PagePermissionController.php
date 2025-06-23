<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class PagePermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'ensure.superadmin']);
    }

    public function index(FirebaseService $firebase)
    {
        $permissions = $firebase->getData('page_permissions') ?? [];

        // Convert keys back to route-style (dots) for display
        $formatted = [];
        foreach ($permissions as $key => $data) {
            $displayKey = str_replace('_', '.', $key);
            $formatted[$displayKey] = $data;
        }

        $roles = [
            1 => 'Superadmin',
            2 => 'Management',
            3 => 'Employee',
        ];

        return view('admin.pages.page-permission', [
            'permissions' => $formatted,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, FirebaseService $firebase, $key)
    {
        $allowedRoles = $request->input('allowed_roles', []);

        // Convert key from dot → underscore for Firebase
        $firebaseKey = str_replace('.', '_', $key);

        // Fetch current entry
        $current = $firebase->getData("page_permissions/{$firebaseKey}") ?? [];

        $firebase->set("page_permissions/{$firebaseKey}", array_merge($current, [
            'allowed_roles' => $allowedRoles,
        ]));

        return redirect()->route('admin.page-permissions')
            ->with('success', "Updated access for '{$key}'");
    }
}
