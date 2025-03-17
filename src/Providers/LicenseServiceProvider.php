<?php

namespace Fenixthelord\License\Providers;

use Fenixthelord\License\Support\LicenseChecker;
use Fenixthelord\License\Console\Commands\InstallServiceProvider;
use Illuminate\Support\ServiceProvider;
use Filament\FilamentServiceProvider;

class LicenseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('laravel-license.mode') === 'client') {
            LicenseChecker::ensureLicensePackageExists();
            if (!class_exists(\Fenixthelord\License\Http\Middleware\CheckLicense::class)) {
                exit("Error: License package middleware is missing. The application cannot run.");
            }
            // إذا كان موجودًا، نقوم بتسجيل الميدل وير
            $this->app['router']->pushMiddlewareToGroup('web', \Fenixthelord\License\Http\Middleware\CheckLicense::class);
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallServiceProvider::class,
            ]);
        }

        $this->registerMiddleware();
        $this->loadServerResources();
        $this->publishResources();
        $this->publishBootstrap();

        if (config('laravel-license.mode') === 'server') {
            $this->installFilament();
        }
    }

    protected function publishBootstrap(): void
    {
        $this->publishes([
            __DIR__ . '/../Support/license-check.php' => base_path('bootstrap/license-check.php'),
        ], 'laravel-license');
    }

    /**
     * Register the package middleware.
     */
    protected function registerMiddleware(): void
    {
        $this->app['router']->aliasMiddleware('checkLicense', \Fenixthelord\License\Http\Middleware\CheckLicense::class);

        if (config('laravel-license.mode') === 'client') {
            $this->app['router']->pushMiddlewareToGroup('web', \Fenixthelord\License\Http\Middleware\CheckLicense::class);
            LicenseChecker::ensureLicensePackageExists();
        }
    }

    /**
     * Load server migrations and routes if the mode is server.
     */
    protected function loadServerResources(): void
    {
        if (config('laravel-license.mode') === 'server') {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
            $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        }
    }

    /**
     * Publish configuration file and other resources.
     */
    protected function publishResources(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/laravel-license.php' => config_path('laravel-license.php'),
            ], 'laravel-license-config');

            $this->publishes([
                __DIR__ . '/../../database/migrations/' => database_path('migrations'),
            ], 'laravel-license-migrations');

            $this->publishes([
                __DIR__ . '/../../src/Models/License.php' => app_path('Models/License.php'),
            ], 'laravel-license-model');

            $this->publishes([
                __DIR__ . '/../../src/Http/Middleware/CheckLicense.php' => app_path('Http/Middleware/CheckLicense.php'),
            ], 'laravel-license-middleware');
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/laravel-license.php', 'laravel-license');

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Fenixthelord\License\Console\Commands\InstallLicensePackage::class,
                \Fenixthelord\License\Console\Commands\InstallServiceProvider::class,
            ]);
        }
    }

    protected function publishConfigs(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/laravel-license.php' => config_path('laravel-license.php'),
            ], 'laravel-license-config');

            $this->publishes([
                __DIR__ . '/../Http/Controllers/LicenseController.php' => app_path('Http/Controllers/LicenseController.php'),
            ], 'laravel-license-controller');
        }
    }

    protected function installFilament()
    {
        // تحقق إذا كان Filament مثبتًا بالفعل
        if (!class_exists(\Filament\FilamentServiceProvider::class)) {
            return;
        }
    
        \Artisan::call('filament:install');  // تثبيت Filament
        \Artisan::call('filament:make:resource', ['name' => 'LicenseResource']);  // إنشاء الـ Resource
        
        

        // تأكد من تضمين FilamentServiceProvider يدويًا
        $this->app->register(FilamentServiceProvider::class);

        // إنشاء Resource لـ License باستخدام الأمر الصحيح
        $resourceClass = 'Fenixthelord\License\Filament\Resources\LicenseResource';

        // تحقق من وجود resource مسبقًا
        if (!class_exists($resourceClass)) {
            // هنا نستخدم الأمر الصحيح لإنشاء resource
            \Artisan::call('filament:make:resource', ['name' => 'LicenseResource']);
        }

        $this->info("Admin panel installed with Filament.");
    }
}
