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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Semak jika pengguna sudah log masuk dan mempunyai peranan yang betul
        // if (!auth()->check() || auth()->user()->role !== $role) {
        //     return redirect('/dashboard')->with('error', 'Akses tidak dibenarkan!');
        // }

        // Ensure the user is logged in
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Check if the user's role matches any of the allowed roles
        if (!in_array(auth()->user()->role, $roles)) {
            return redirect('/dashboard')->with('error', 'Akses tidak dibenarkan!');
        }

        return $next($request);
    }
}
