<?php

namespace Fenixthelord\License\Http\Middleware;

use Closure;
use Fenixthelord\License\Providers\LicenseServiceProvider;
use Fenixthelord\License\Services\LicenseService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Fenixthelord\License\Exceptions\InvalidLicenseException;

class CheckLicense
{


    public function handle(Request $request, Closure $next)
    {


        $licenseService =  LicenseService::validateLicense();
        
        if (!$licenseService) {
            abort(403, 'Invalid license or subscription expired | Please contact with "Eng.Muhammad Khalaf" https://wa.me/+963945235962');
        }

        return $next($request);
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|string',
            'valid_days' => 'required|integer'
        ]);

        $license = License::create([
            'key' => Str::random(64),
            'product_id' => $validated['product_id'],
            'valid_until' => now()->addDays($validated['valid_days'])
        ]);

        return response()->json($license);
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'license_key' => 'required|string',
            'product_id' => 'required|string',
            'domain' => 'required|string'
        ]);

        $license = License::where('key', $validated['license_key'])
            ->where('product_id', $validated['product_id'])
            ->first();

        if (!$license || !$license->is_active || $license->valid_until < now()) {
            return response()->json(['valid' => false]);
        }

        // Optional domain locking
        if ($license->domain && $license->domain !== $validated['domain']) {
            return response()->json(['valid' => false]);
        }

        return response()->json([
            'valid' => true,
            'expires_at' => $license->valid_until
        ]);
    }
}
