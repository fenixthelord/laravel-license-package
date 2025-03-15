<?php

namespace Fenixthelord\LaravelLicense\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class LicenseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // تحقق من وجود البكج
        if (!App::has(Fenixthelord\LaravelLicense\Providers\LicenseServiceProvider::class)) {
            // يمكن إلغاء تعليق هذه السطر إذا أردت إيقاف التطبيق عند غياب البكج
            // exit('The license management package is missing. The application cannot run.');
        }

        // تسجيل الـ Middleware
        $this->registerMiddleware();

        // تحميل الترحيلات والطرق للسيرفر
        $this->loadServerResources();

        // نشر الإعدادات والملفات الخاصة بالبكج
        $this->publishConfigs();
    }

    /**
     * Register the package middleware.
     *
     * @return void
     */
    protected function registerMiddleware(): void
    {
        // تسجيل الـ Middleware الخاص بالتحقق من الرخصة
        $this->app['router']->aliasMiddleware('checkLicense', \Fenixthelord\LaravelLicense\Http\Middleware\CheckLicense::class);

        // إعداد الـ Middleware للعميل
        if (config('laravel-license.mode') === 'client') {
            $this->app['router']->aliasMiddleware('license', \Fenixthelord\LaravelLicense\Http\Middleware\CheckLicense::class);
        }
    }

    /**
     * Load server migrations and routes if the mode is server.
     *
     * @return void
     */
    protected function loadServerResources(): void
    {
        if (config('laravel-license.mode') === 'server') {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations/2025_03_11_000000_create_licenses_table.php');
            $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
            
        }
    }

    /**
     * Publish configuration file and other resources.
     *
     * @return void
     */
    protected function publishConfigs(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/laravel-license.php' => config_path('laravel-license.php'),
            ], 'laravel-license-config');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/laravel-license.php', 'laravel-license');
        $this->commands([
            \Fenixthelord\LaravelLicense\Console\Commands\InstallLicensePackage::class,
        ]);
    }
}
