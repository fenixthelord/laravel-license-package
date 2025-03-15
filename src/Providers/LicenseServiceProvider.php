<?php

namespace LaravelLicense\Providers;

use Illuminate\Support\ServiceProvider;

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
        if (!class_exists('YourLicensePackageClass')) {
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
