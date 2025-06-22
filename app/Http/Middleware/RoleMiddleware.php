<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
        return redirect()->route('login');
        }
        if (!auth()->check() || !in_array(auth()->user()->role->role_name, $roles)) {
            // Jika user tidak terautentikasi atau bukan admin/courier, redirect ke halaman login
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
