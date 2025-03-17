<?php


namespace Fenixthelord\License\Exceptions;

use Exception;

class InvalidLicenseException extends Exception
{
    public function render($request)
    {
       return abort(403, 'Invalid license or subscription expired | Please contact with "Eng.Muhammad Khalaf" https://wa.me/+963945235962');
    }
}
