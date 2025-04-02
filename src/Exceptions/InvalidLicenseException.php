<?php


namespace Fenixthelord\License\Exceptions;

use Exception;

class InvalidLicenseException extends Exception
{
    public function render($request)
    {
        $developerName = config('laravel-license.developer_name', 'the developer');
        $supportContact = config('laravel-license.support_contact', 'the support channel');

        $message = sprintf(
            'Invalid license or subscription expired. Please contact %s via %s for assistance.',
            $developerName,
            $supportContact
        );

        return abort(403, $message);
    }
}
