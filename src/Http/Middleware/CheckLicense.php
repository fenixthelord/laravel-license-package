<?php

namespace Fenixthelord\License\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Fenixthelord\License\Exceptions\InvalidLicenseException;

class CheckLicense
{
    public function handle(Request $request, Closure $next): Response
    {
        // التأكد من أن البكج مثبتة
        if (!class_exists(\Fenixthelord\License\LicenseServiceProvider::class)) {
            abort(403, 'License management package is missing.');
        }

        // تحميل إعدادات الرخصة
        $licenseKey = config('laravel-license.license_key');
        $licenseStatus = config('laravel-license.license_status'); // حالة الرخصة

        // تحقق من وجود مفتاح الرخصة وحالته
        if (!$licenseKey || $licenseStatus !== 'valid') {
            throw new InvalidLicenseException('The application license is invalid or missing.');
        }

        return $next($request);
    }
}
