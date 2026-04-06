# Installation and Setup Guide

## Table of Contents
1. [System Requirements](#system-requirements)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Verification](#verification)
5. [Troubleshooting](#troubleshooting)

## System Requirements

Before installing the Pagocards PHP SDK, ensure your system meets the following requirements:

- **PHP**: 8.1 or higher
- **Composer**: Latest version
- **Laravel**: 8.0 or higher
- **Extensions**:
  - cURL
  - JSON
  - OpenSSL

Check your PHP version:
```bash
php --version
```

## Installation

### Step 1: Add Repository to composer.json

If the SDK is not yet on Packagist, add the GitHub repository to your `composer.json`:

```bash
composer config repositories.pagocards vcs https://github.com/pagocards/php-laravel
```

Or manually edit your `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/pagocards/php-laravel"
    }
  ]
}
```

### Step 2: Install the SDK

```bash
composer require pagocards/php-laravel:^1.0
```

Or when available on Packagist:

```bash
composer require pagocards/php-laravel:^1.0
```

### Step 3: Verify Installation

Check that the SDK is installed:

```bash
composer show pagocards/php-laravel
```

## Configuration

### Step 1: Publish Configuration

If you're using Laravel, publish the configuration file:

```bash
php artisan vendor:publish --provider="Pagocards\SDK\ServiceProvider" --tag="config"
```

This creates `config/pagocards.php` in your Laravel project.

### Step 2: Set Environment Variables

Add your API credentials to your `.env` file:

```env
PAGOCARDS_PUBLIC_KEY=your_public_key_here
PAGOCARDS_SECRET_KEY=your_secret_key_here
PAGOCARDS_API_URL=https://pagocards.com
PAGOCARDS_DEBUG=false
```

### Step 3: Get Your API Keys

1. Go to [Pagocards Dashboard](https://pagocards.com/dashboard)
2. Navigate to Settings → Developer
3. Copy your Public Key and Secret Key
4. Paste them into your `.env` file

## Verification

### Test the Installation

Create a test file or use tinker:

```bash
php artisan tinker
```

Then run:

```php
use Pagocards\SDK\Facades\Pagocards;

// Check if the facade works
Pagocards::getBaseUrl();

// Try to get balance (requires valid API keys)
Pagocards::getBalance();
```

You should see your wallet balance if the configuration is correct.

### Unit Tests

Run the SDK tests:

```bash
cd vendor/pagocards/php-laravel
composer test
```

## Troubleshooting

### Issue: "Class 'Pagocards\SDK\ServiceProvider' not found"

**Solution:**
Ensure the service provider is auto-discovered. If using Laravel < 5.5, manually add to `config/app.php`:

```php
'providers' => [
    // ...
    Pagocards\SDK\ServiceProvider::class,
],

'aliases' => [
    // ...
    'Pagocards' => Pagocards\SDK\Facades\Pagocards::class,
],
```

### Issue: "PAGOCARDS_PUBLIC_KEY not defined"

**Solution:**
Ensure your `.env` file has the keys:

```env
PAGOCARDS_PUBLIC_KEY=your_actual_public_key
PAGOCARDS_SECRET_KEY=your_actual_secret_key
```

Run:
```bash
php artisan config:clear
php artisan cache:clear
```

### Issue: "cURL error 6: Could not resolve: api"

**Solution:**
Ensure `PAGOCARDS_API_URL` is set to the complete domain:

```env
PAGOCARDS_API_URL=https://pagocards.com
```

Not just `pagocards.com` without the protocol prefix (it must include `https://`).

### Issue: "Call to undefined method Pagocards::..."

**Solution:**
The Facade isn't properly registered. Verify:

1. Service provider is published and auto-discovered
2. Your `.env` is loaded (run `php artisan config:cache`)
3. Try using the client directly:

```php
use Pagocards\SDK\Client;

$client = new Client(
    env('PAGOCARDS_PUBLIC_KEY'),
    env('PAGOCARDS_SECRET_KEY')
);

$balance = $client->getBalance();
```

### Issue: Composer can't find the package

**Solution:**
If installing from GitHub, ensure:

1. Repository is properly configured
2. Run `composer update`
3. Check GitHub token if private repo (though this is public)

```bash
composer config --list
```

## Next Steps

Once installed and verified:

1. Read the [README.md](README.md) for usage examples
2. Check the [API Documentation](https://pagocards.com/api/docs)
3. Review the example implementations
4. Join our [community](https://pagocards.com)

## Support

If you encounter any issues:

- **Email:** [support@pagocards.com](mailto:support@pagocards.com)
- **GitHub Issues:** [github.com/pagocards/php-laravel/issues](https://github.com/pagocards/php-laravel/issues)
- **Documentation:** [docs.pagocards.com](https://docs.pagocards.com)

---

Happy coding with Pagocards! 🚀

