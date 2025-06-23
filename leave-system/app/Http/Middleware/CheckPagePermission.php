<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Services\FirebaseService;

class CheckPagePermission
{
    protected FirebaseService $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function handle($request, Closure $next)
    {
        $routeName = Route::currentRouteName();

        if (!$routeName) {
            abort(403, 'Unauthorized: Route is not named.');
        }

        $firebaseKey = str_replace('.', '_', $routeName); // e.g., admin_users_edit
        $permissions = $this->firebase->getData("page_permissions/{$firebaseKey}");

        $user = Auth::user();
        $userRoleId = (int) ($user->role_id ?? $user->role ?? 0);

        if (!$permissions || !in_array($userRoleId, $permissions['allowed_roles'] ?? [])) {
            abort(403, 'Unauthorized: This role cannot access this page.');
        }

        return $next($request);
    }
}