<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \App\Providers\AppServiceProvider::class,
        \App\Providers\AuthServiceProvider::class,
        \App\Providers\EventServiceProvider::class,
        \App\Providers\ModuleAssetServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register route-level middleware aliases (from old Kernel::$routeMiddleware)
        $middleware->alias([
            'language' => \App\Http\Middleware\Language::class,
            'timezone' => \App\Http\Middleware\Timezone::class,
            'SetSessionData' => \App\Http\Middleware\SetSessionData::class,
            'setData' => \App\Http\Middleware\IsInstalled::class,
            'AdminSidebarMenu' => \App\Http\Middleware\AdminSidebarMenu::class,
            'CheckUserLogin' => \App\Http\Middleware\CheckUserLogin::class,
            'superadmin' => \App\Http\Middleware\Superadmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
