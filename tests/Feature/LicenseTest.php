<?php


namespace LaravelLicense\License\Tests\Feature;

use LaravelLicense\License\Models\License;
use Tests\TestCase;

class LicenseTest extends TestCase
{
    public function test_valid_license()
    {
        $license = License::factory()->create();
        
        $response = $this->postJson('/api/license/verify', [
            'key' => $license->key,
            'domain' => $license->domain
        ]);

        $response->assertJson(['valid' => true]);
    }

    public function test_invalid_license()
    {
        $response = $this->postJson('/api/license/verify', [
            'key' => 'invalid-key',
            'domain' => 'invalid-domain'
        ]);

        $response->assertStatus(403);
    }
}
