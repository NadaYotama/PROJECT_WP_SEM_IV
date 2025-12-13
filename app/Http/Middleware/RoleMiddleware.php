<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $userRole = auth()->user()->role;
        $allowedRoles = explode(',', $role);

        if (in_array($userRole, $allowedRoles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
