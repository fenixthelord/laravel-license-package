<?php

namespace Fenixthelord\License\Providers;

use Fenixthelord\License\Support\LicenseChecker;
use Fenixthelord\License\Console\Commands\InstallLicensePackage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LicenseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('laravel-license.mode') === 'client') {
            $this->ensureClientSetup();
        }

        if (config('laravel-license.mode') === 'server') {
            $this->registerApiRoutes();
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }

        if ($this->app->runningInConsole()) {
            $this->registerCommands();
            $this->publishResources();
        }

        $this->registerMiddleware();
        $this->publishBootstrap();
    }

    /**
     * تأكد من إعداد وضع العميل (Client)
     */
    protected function ensureClientSetup(): void
    {
        LicenseChecker::ensureLicensePackageExists();

        if (!class_exists(\Fenixthelord\License\Http\Middleware\CheckLicense::class)) {
            exit("Error: License package middleware is missing. The application cannot run.");
        }

        $this->app['router']->pushMiddlewareToGroup('web', \Fenixthelord\License\Http\Middleware\CheckLicense::class);
    }

    /**
     * تحميل مسارات API داخل مجموعة `api`
     */
    protected function registerApiRoutes(): void
    {
        $routePath = __DIR__ . '/../../routes/api.php';

        if (file_exists($routePath)) {
            Route::prefix('api')
                ->middleware('api')
                ->group($routePath);
        }
    }

    /**
     * تسجيل أوامر الـ Console.
     */
    protected function registerCommands(): void
    {
        $this->commands([
            InstallLicensePackage::class,
        ]);
    }

    /**
     * تسجيل الميدلوير
     */
    protected function registerMiddleware(): void
    {
        $this->app['router']->aliasMiddleware('checkLicense', \Fenixthelord\License\Http\Middleware\CheckLicense::class);
    }

    /**
     * نشر الملفات المطلوبة.
     */
    protected function publishResources(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/laravel-license.php' => config_path('laravel-license.php'),
            __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        ], 'laravel-license');
    }

    /**
     * نشر ملف bootstrap.
     */
    protected function publishBootstrap(): void
    {
        $this->publishes([
            __DIR__ . '/../Support/license-check.php' => base_path('bootstrap/license-check.php'),
        ], 'laravel-license');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/laravel-license.php', 'laravel-license');
    }
}
