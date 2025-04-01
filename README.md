# Laravel License Package

Secure license management for Laravel applications.

## Installation

composer require fenixthelord/laravel-license-package





Add to `.env`:

LICENSE_MODE=server
LICENSE_KEY=your_license_key
LICENSE_SERVER_URL=https://your-license-server.com
LICENSE_ENCRYPTION_KEY=your_encryption_key


## Usage

php artisan license:install

## API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/license/verify` | POST | Verify license validity |
| `/api/license/generate` | POST | Generate new license |

## Security

Use HTTPS for all license server communications.

