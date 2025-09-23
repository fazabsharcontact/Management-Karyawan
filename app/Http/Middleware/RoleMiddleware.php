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
        dd('Belum login', Auth::user());
    }

        $userRole = Auth::user()->role ?? null;

        
        return $next($request);
    }
}