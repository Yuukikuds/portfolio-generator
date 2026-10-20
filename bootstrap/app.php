<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Not logged in -> the Login page (and come back to the page they wanted after logging in).
        $middleware->redirectGuestsTo(fn () => route('login'));
        // Already logged in -> skip the Login / Get Started pages and go to Manage.
        $middleware->redirectUsersTo(fn () => route('portfolios.index'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
