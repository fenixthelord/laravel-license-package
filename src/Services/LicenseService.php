<?php

namespace Fenixthelord\License\Services;

use Illuminate\Support\Facades\Http;
use Fenixthelord\License\Exceptions\InvalidLicenseException;

class LicenseService
{
    public static function validateLicense()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('laravel-license.api_key'),
                'Accept' => 'application/json'
            ])->post(config('laravel-license.server_url') . '/api/licenses/verify',
                [
                    'license_key' => config('laravel-license.key'),
                    'product_id' => config('laravel-license.product_id'),
                    'domain' => request()->getHost()
                ]);
            return $response->json()['valid'] ?? false;
        } catch (\Exception $e) {
            // Handle offline scenario
            return config('laravel-license.allow_offline') ? true : false;        }
    }
}
