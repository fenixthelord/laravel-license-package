<?php

namespace LaravelLicense\License\Database\Factories;

use LaravelLicense\License\Models\License;
use Illuminate\Database\Eloquent\Factories\Factory;

class LicenseFactory extends Factory
{
    protected $model = License::class;

    public function definition()
    {
        return [
            'key' => bin2hex(random_bytes(32)),
            'domain' => $this->faker->domainName,
            'valid_until' => now()->addYear(),
            'is_active' => true
        ];
    }
}
