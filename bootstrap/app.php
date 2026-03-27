<?php

use App\Http\Middleware\AdminSidebarMenu;
use App\Http\Middleware\CheckUserLogin;
use App\Http\Middleware\IsInstalled;
use App\Http\Middleware\Language;
use App\Http\Middleware\SetSessionData;
use App\Http\Middleware\Superadmin;
use App\Http\Middleware\Timezone;
use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\ModuleAssetServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        AppServiceProvider::class,
        AuthServiceProvider::class,
        EventServiceProvider::class,
        ModuleAssetServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register route-level middleware aliases (from old Kernel::$routeMiddleware)
        $middleware->alias([
            'language' => Language::class,
            'timezone' => Timezone::class,
            'SetSessionData' => SetSessionData::class,
            'setData' => IsInstalled::class,
            'AdminSidebarMenu' => AdminSidebarMenu::class,
            'CheckUserLogin' => CheckUserLogin::class,
            'superadmin' => Superadmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
