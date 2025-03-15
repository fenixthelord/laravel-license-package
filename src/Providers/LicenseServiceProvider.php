<?php

namespace LaravelLicense\Providers;

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
        if (!App::bound('LaravelLicense')) {
            exit('The license management package is missing. The application cannot run.');
        }
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
