<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    // Cek apakah user yang login punya role yang diizinkan
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Belum login → redirect ke login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Role tidak sesuai → tampilkan 403
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}