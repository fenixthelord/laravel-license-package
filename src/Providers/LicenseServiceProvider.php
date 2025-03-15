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
            exit('The license management package is missing. The application cannot run.');
        }
         $this->app['router']->aliasMiddleware('checkLicense', \Fenixthelord\LaravelLicense\Http\Middleware\CheckLicense::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // يمكنك إضافة أي خدمات هنا إذا لزم الأمر
    }
}
