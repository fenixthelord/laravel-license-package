<?php

namespace Fenixthelord\License\Http\Middleware;

use Closure;
use Fenixthelord\License\Exceptions\InvalidLicenseException;

class CheckLicense
{
    public function handle($request, Closure $next)
{
    if (!class_exists('Fenixthelord\\LaravelLicense\\LicenseServiceProvider')) {
        abort(403, 'License management package is missing.');
    }

    return $next($request);
}

}
