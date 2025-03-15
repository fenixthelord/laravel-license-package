<?php


namespace Fenixthelord\License\Http\Controllers;

use Illuminate\Http\Request;
use Fenixthelord\License\Models\License;
use Fenixthelord\License\Exceptions\InvalidLicenseException;

class LicenseController extends Controller
{
    public function verify(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'domain' => 'required|string'
        ]);

        $license = License::where('key', $validated['key'])
            ->where('domain', $validated['domain'])
            ->where('valid_until', '>', now())
            ->where('is_active', true)
            ->first();

        if (!$license) {
            throw new InvalidLicenseException;
        }

        return response()->json([
            'valid' => true,
            'expires_at' => $license->valid_until
        ]);
    }
}
