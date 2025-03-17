# Laravel License Package

Secure license management for Laravel applications.

## Installation

composer require laravel-license/auth-package


## Configuration

Publish config file:

php artisan vendor:publish --tag=config


Add to `.env`:

LICENSE_KEY=your_license_key
LICENSE_SERVER_URL=https://your-license-server.com
LICENSE_ENCRYPTION_KEY=your_encryption_key


## Usage

1. **Apply Middleware**


Route::middleware('license')->group(function () {
// Protected routes
});


2. **Check License Manually**


## API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/license/verify` | POST | Verify license validity |
| `/api/license/generate` | POST | Generate new license |

## Security

Use HTTPS for all license server communications.


// database/factories/LicenseFactory.php
namespace License\License\Database\Factories;

use License\License\Models\License;
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
