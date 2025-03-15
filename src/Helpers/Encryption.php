<?php

namespace Fenixthelord\License\Helpers;

use Illuminate\Support\Facades\Crypt;

class Encryption
{
    public static function encryptLicenseData(array $data): string
    {
        return Crypt::encryptString(json_encode($data));
    }

    public static function decryptLicenseData(string $payload): array
    {
        return json_decode(Crypt::decryptString($payload), true);
    }
}