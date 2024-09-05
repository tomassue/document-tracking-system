<?php

use App\Http\Middleware\IsActive;
use App\Http\Middleware\SuperAdminAccess;
use App\Http\Middleware\UpdatedPassword;
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
        $middleware->alias([
            'super_admin_access_only' => SuperAdminAccess::class,
            'updated_password' => UpdatedPassword::class,
            'is_active' => IsActive::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
