<?php

namespace Fenixthelord\License\Services;

use Illuminate\Support\Facades\Http;
use Fenixthelord\License\Exceptions\InvalidLicenseException;

class LicenseService
{
    public function verify(string $licenseKey)
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'X-License-Key' => $licenseKey
            ])->post(config('laravel-license.server_url').'/api/verify');
            
            if ($response->failed() || !$response->json('valid')) {
                throw new InvalidLicenseException;
            }

            return $response->json();

        } catch (\Exception $e) {
            if (config('laravel-license.offline_mode')) {
                return $this->checkLocalLicense($licenseKey);
            }
            throw $e;
        }
    }
}
