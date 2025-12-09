<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // 1. Middleware Force Change Password (Yang sebelumnya sudah ada)
        $middleware->append(\App\Http\Middleware\CheckForcePassword::class);

        // 2. Middleware Anti-Back (BARU - Tambahkan ini)
        // Kita pasang di group 'web' agar semua halaman web kena efeknya
        $middleware->web(append: [
            \App\Http\Middleware\PreventBackHistory::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
