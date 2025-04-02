# Laravel License 🔑

A simple package for adding software license validation to Laravel applications. Operates in two modes:

*   **Client:** Verifies a license key against a remote server.
*   **Server:** Acts as the API endpoint to validate keys.

## Installation

1.  **Require via Composer:**
    ```bash
    composer require fenixthelord/laravel-license
    ```

2.  **Run Install Command:** Choose `client` or `server` mode.
    ```bash
    php artisan license:install
    ```
    This publishes `config/laravel-license.php`.

3.  **(Server Mode Only)** Run migrations:
    ```bash
    php artisan migrate
    ```

## Configuration

*   Set your mode (`client` or `server`) in `config/laravel-license.php`.
*   Configure necessary details (Server URL, API keys, License Key, Developer Contact) via your `.env` file, referencing the keys in the config file.

## Usage

*   **Client Mode:** The included middleware automatically checks the configured license key against the server URL on web routes.
*   **Server Mode:** Provides an API endpoint (`/api/licenses/verify`) for clients to validate their keys against the `licenses` database table.


## API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/license/verify` | POST | Verify license validity |
| `/api/license/generate` | POST | Generate new license |
