<?php

namespace LaravelLicense\License;

use Illuminate\Support\ServiceProvider;
use LaravelLicense\License\Http\Middleware\CheckLicense;

class LicenseServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-license.php', 'laravel-license');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-license.php' => config_path('laravel-license.php'),
        ], 'config');

        if (config('laravel-license.mode') === 'client') {
            $this->app['router']->aliasMiddleware('license', CheckLicense::class);
        }

        if (config('laravel-license.mode') === 'server') {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        }
    }
}

