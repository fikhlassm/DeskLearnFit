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
     * Verifies the authenticated user has one of the required roles.
     *
     * @param  string  ...$roles  Allowed role(s): 'siswa' | 'pengajar'
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! in_array($user->role, $roles, true)) {
            if ($user->role === 'pengajar') {
                return redirect()->route('dashboard.pengajar')
                    ->with('error', 'Akses ditolak. Halaman ini hanya untuk siswa.');
            }

            return redirect()->route('dashboard.siswa')
                ->with('error', 'Akses ditolak. Halaman ini hanya untuk pengajar.');
        }

        return $next($request);
    }
}
