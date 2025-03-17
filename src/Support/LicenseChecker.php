<?php

namespace Fenixthelord\License\Support;

class LicenseChecker
{
    public static function ensureLicensePackageExists()
    {
        if (!class_exists(\Fenixthelord\License\Providers\LicenseServiceProvider::class)) {
            exit("Error: The required License package is missing. The application cannot run without it.");
        }
    }
}
