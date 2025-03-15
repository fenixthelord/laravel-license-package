<?php

namespace Fenixthelord\LaravelLicense\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Container\BindingResolutionException;

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
        if (!App::bound(Fenixthelord\License\LicenseServiceProvider::class)) {
            abort(403,'The license management package is missing. The application cannot run.');
        }
         $this->app['router']->aliasMiddleware('checkLicense', \Fenixthelord\LaravelLicense\Http\Middleware\CheckLicense::class);

         // إعداد الـ Middleware للعميل
        if (config('laravel-license.mode') === 'client') {
            $this->app['router']->aliasMiddleware('license', CheckLicense::class);
        }

        // تحميل الترحيلات والطرق للسيرفر
        if (config('laravel-license.mode') === 'server') {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
            $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-license.php' => config_path('laravel-license.php'),
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
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-license.php', 'laravel-license');
    }
}
