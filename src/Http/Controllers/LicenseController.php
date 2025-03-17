<?php


namespace Fenixthelord\License\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Fenixthelord\License\Models\License;
use Fenixthelord\License\Exceptions\InvalidLicenseException;

class LicenseController extends Controller
{
    public function verify(Request $request)
    {

        $validated = $request->validate([
            'license_key' => 'required|string',
            'product_id' => 'required|integer',
            'domain' => 'required|string'
        ]);

        $license = License::where('key', $validated['license_key'])
            ->where('domain', $validated['domain'])
            ->where('product_id', $validated['product_id'])
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
