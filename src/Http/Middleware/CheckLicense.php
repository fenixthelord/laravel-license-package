<?php

namespace Fenixthelord\License\Http\Middleware;

use Closure;
use Fenixthelord\License\Exceptions\InvalidLicenseException;

class CheckLicense
{
    public function handle($request, Closure $next)
    {
        if (!app('laravel-license')->verify(
            config('laravel-license.key')
        )) {
            throw new InvalidLicenseException;
        }

        return $next($request);
    }
}
