<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSuperadmin
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (Auth::check() && Auth::user()->role_id == 1) {
            return $next($request);
        }

        abort(403, 'Access denied. Superadmin only.');
    }
}
