<?php

namespace Fenixthelord\LaravelLicense\Providers;

use Illuminate\Support\ServiceProvider;

class LicenseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // تسجيل الـ Middleware
        $this->registerMiddleware();

        // تحميل الترحيلات والطرق إذا كان الوضع سيرفر
        $this->loadServerResources();

        // نشر الإعدادات والملفات الخاصة بالبكج
        $this->publishResources();
    }

    /**
     * Register the package middleware.
     */
    protected function registerMiddleware(): void
    {
        $this->app['router']->aliasMiddleware('checkLicense', \Fenixthelord\LaravelLicense\Http\Middleware\CheckLicense::class);

        if (config('laravel-license.mode') === 'client') {
            $this->app['router']->aliasMiddleware('license', \Fenixthelord\LaravelLicense\Http\Middleware\CheckLicense::class);
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
            // نشر ملف الإعدادات
            $this->publishes([
                __DIR__ . '/../../config/laravel-license.php' => config_path('laravel-license.php'),
            ], 'laravel-license-config');

            // نشر المهاجرات
            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations'),
            ], 'laravel-license-migrations');

            // نشر الموديل License.php
            $this->publishes([
                __DIR__ . '/../src/Models/License.php' => app_path('Models/License.php'),
            ], 'laravel-license-model');
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/laravel-license.php', 'laravel-license');

        // تسجيل الأوامر الخاصة بالحزمة
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Fenixthelord\LaravelLicense\Console\Commands\InstallLicensePackage::class,
            ]);
        }
    }
}
