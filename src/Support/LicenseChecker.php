<?php

namespace Fenixthelord\License\Support;

class LicenseChecker
{
    public static function ensureLicensePackageExists()
    {
        if (!class_exists(\Fenixthelord\License\Providers\LicenseServiceProvider::class)) {
            abort(
                500,
                "Error: The required License package is missing. The application cannot run without it.
                    please Run `composer require fenixthelord/laravel-license-package `
                "
            );
        }
    }
}
