<?php


namespace Fenixthelord\License\Exceptions;

use Exception;

class InvalidLicenseException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'error' => 'Invalid license key',
            'message' => 'Please contact support'
        ], 403);
    }
}
