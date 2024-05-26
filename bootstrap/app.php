<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'RefreshTokenIfExpired' => \App\Http\Middleware\RefreshTokenIfExpired::class,
            'IsAdmin' => \App\Http\Middleware\IsAdmin::class,
            'IsEmploye' => \App\Http\Middleware\IsEmploye::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();