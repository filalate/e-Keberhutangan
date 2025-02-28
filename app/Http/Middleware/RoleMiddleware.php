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
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Semak jika pengguna sudah log masuk dan mempunyai peranan yang betul
        if (!auth()->check() || auth()->user()->role !== $role) {
            return redirect('/dashboard')->with('error', 'Akses tidak dibenarkan!');
        }

        return $next($request);
    }
}
