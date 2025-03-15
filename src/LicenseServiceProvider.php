<?php

namespace LaravelLicense\License;

use Illuminate\Support\ServiceProvider;
use LaravelLicense\License\Http\Middleware\CheckLicense;

class LicenseServiceProvider extends ServiceProvider
{
    public function register()
    {
        // دمج التكوين
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-license.php', 'laravel-license');
    }

    public function boot()
    {
        // تحقق إذا كان الملف موجودًا ثم نشره
        if (!file_exists(config_path('laravel-license.php'))) {
            $this->publishes([
                __DIR__ . '/../config/laravel-license.php' => config_path('laravel-license.php'),
            ], 'config');
        }

        // إعداد الـ Middleware للعميل
        if (config('laravel-license.mode') === 'client') {
            $this->app['router']->aliasMiddleware('license', CheckLicense::class);
        }

        // تحميل الترحيلات والطرق للسيرفر
        if (config('laravel-license.mode') === 'server') {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        }
    }
}
