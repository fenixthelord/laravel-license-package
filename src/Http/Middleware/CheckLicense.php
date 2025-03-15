<?php

namespace LaravelLicense\License\Http\Middleware;

use Closure;
use LaravelLicense\License\Exceptions\InvalidLicenseException;

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
