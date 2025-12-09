<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckForcePassword
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Jika user harus ganti password
        if ($user && $user->must_change_password) {
            $route = $request->route()->getName();
            // Hanya boleh akses halaman ganti password & logout
            if ($route != 'password.change' && $route != 'password.update' && $request->path() != 'keluar') {
                return redirect()->route('password.change');
            }
        }

        return $next($request);
    }
}
