<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class LicensePackageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stops_application_if_license_package_is_missing_in_client_mode()
    {
        Config::set('laravel-license.mode', 'client');

        // محاكاة عدم وجود التحقق من الترخيص
        Config::set('laravel-license.enabled', false);
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Error: License package middleware is missing. The application cannot run.");

        Artisan::call('route:list');
    }

    /** @test */
    public function it_allows_application_to_run_in_server_mode()
    {
        Config::set('laravel-license.mode', 'server');
        
        $this->artisan('route:list')->assertExitCode(0);
    }

    /** @test */
    public function it_can_run_license_install_command_as_client()
    {
        $this->artisan('license:install')
            ->expectsQuestion('Do you want to use the package as a Client or Server?', 'client')
            ->expectsOutput('Setting up for Client mode...')
            ->assertExitCode(0);
    }

    /** @test */
    public function it_can_run_license_install_command_as_server()
    {
        $this->artisan('license:install')
            ->expectsQuestion('Do you want to use the package as a Client or Server?', 'server')
            ->expectsOutput('Setting up for Server mode...')
            ->assertExitCode(0);
    }
}
