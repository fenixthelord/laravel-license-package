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
        $this->registerMiddleware();
        $this->loadServerResources();
        $this->publishResources();
    }

    /**
     * Register the package middleware.
     */
    protected function registerMiddleware(): void
    {
        $this->app['router']->aliasMiddleware('checkLicense', \Fenixthelord\LaravelLicense\Http\Middleware\CheckLicense::class);

        if (config('laravel-license.mode') === 'client') {
            $this->app['router']->pushMiddlewareToGroup('web', \Fenixthelord\LaravelLicense\Http\Middleware\CheckLicense::class);
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
                \Fenixthelord\LaravelLicense\Console\Commands\InstallLicensePackage::class,
            ]);
        }
    }
}
