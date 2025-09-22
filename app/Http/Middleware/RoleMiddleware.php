<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure  $next
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        // pastikan user sudah login
        if (! Auth::check()) {
            abort(403, 'Unauthorized');
        }

        $userRole = Auth::user()->role ?? null;

        // dukung beberapa role dipisah dengan '|' atau ','
        if (! is_null($role)) {
            $roles = preg_split('/[|,]/', $role);
            if (! in_array($userRole, $roles, true)) {
                abort(403, 'Unauthorized');
            }
        }

        return $next($request);
    }
}