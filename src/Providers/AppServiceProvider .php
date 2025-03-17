<?php

namespace Fenixthelord\License\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // التحقق من وجود البكج
        if (!class_exists(\Fenixthelord\License\Providers\LicenseServiceProvider::class)) {
            // إذا تم حذف البكج، إيقاف التطبيق
            exit("Error: The License package is missing. The application cannot run.");
        }
    }

    public function register(): void
    {
        
    }
}
