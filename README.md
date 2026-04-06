# Pagocards PHP Laravel SDK

[![CI](https://github.com/pagocards/php-laravel/actions/workflows/ci.yml/badge.svg)](https://github.com/pagocards/php-laravel/actions/workflows/ci.yml)
[![Latest Version](https://img.shields.io/github/v/release/pagocards/php-laravel)](https://github.com/pagocards/php-laravel/releases)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue.svg)](https://www.php.net/)

The official PHP SDK for **Pagocards** - A powerful card issuance and virtual wallet platform. This SDK allows you to easily integrate Pagocards services into your Laravel applications.

## Features

- 🚀 Easy-to-use API client for Pagocards
- 💳 Create and manage Mastercard and Visa cards
- 💰 Fund cards and check wallet balances
- 🔐 Secure authentication with public/secret keys
- 📦 Laravel Service Provider integration
- ✅ Full test coverage
- 📝 Comprehensive documentation

## Requirements

- PHP 8.1 or higher
- Laravel 8.0 or higher
- Composer
- Guzzle HTTP client (installed via Composer)

## Installation

### Via Composer (From GitHub)

```bash
composer config repositories.pagocards vcs https://github.com/pagocards/php-laravel
composer require pagocards/php-laravel:^1.0
```

Or add to your `composer.json`:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/pagocards/php-laravel"
    }
  ],
  "require": {
    "pagocards/php-laravel": "^1.0"
  }
}
```

Then run:

```bash
composer install
```

### From Released Package

When the package is available on Packagist:

```bash
composer require pagocards/php-laravel:^1.0
```

## Configuration

### 1. Publish Configuration File

```bash
php artisan vendor:publish --provider="Pagocards\SDK\ServiceProvider" --tag="config"
```

This creates `config/pagocards.php` in your Laravel project.

### 2. Set Environment Variables

Add the following to your `.env` file:

```env
PAGOCARDS_PUBLIC_KEY=your_public_key_here
PAGOCARDS_SECRET_KEY=your_secret_key_here
PAGOCARDS_API_URL=https://pagocards.com
PAGOCARDS_DEBUG=false
```

You can get your API keys from your [Pagocards Dashboard](https://pagocards.com/settings/developer).

## Usage

### Using the Facade

```php
use Pagocards\SDK\Facades\Pagocards;

// Create a Mastercard
$card = Pagocards::createMastercard([
    'email' => 'user@example.com',
    'firstname' => 'John',
    'lastname' => 'Doe'
]);

// Create a Visa Card
$card = Pagocards::createVisacard([
    'email' => 'user@example.com',
    'firstname' => 'John',
    'lastname' => 'Doe'
]);

// Fund a Visa Card
$result = Pagocards::fundVisacard('card_id', 100.00);

// Get card details
$card = Pagocards::getCard('card_id', 'visacard');

// List cards
$cards = Pagocards::listCards('visacard', 1, 50);

// Block/Unblock Mastercard
Pagocards::toggleCard('card_id', 'block', 'mastercard');
Pagocards::toggleCard('card_id', 'unblock', 'mastercard');

// Get wallet balance
$balance = Pagocards::getBalance();
```

### Direct Client Usage

```php
use Pagocards\SDK\Client;

$client = new Client(
    'your_public_key',
    'your_secret_key',
    'https://pagocards.com'
);

// Use all the same methods as the facade
$card = $client->createVisacard([
    'email' => 'user@example.com',
    'firstname' => 'John',
    'lastname' => 'Doe'
]);
```

### In Service Classes or Controllers

```php
namespace App\Services;

use Pagocards\SDK\Facades\Pagocards;

class CardService
{
    public function createUserCard($email, $firstname, $lastname)
    {
        try {
            $card = Pagocards::createVisacard([
                'email' => $email,
                'firstname' => $firstname,
                'lastname' => $lastname
            ]);

            return $card;
        } catch (\Exception $e) {
            \Log::error('Card creation failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
```

## API Methods

### Cards

#### Create Mastercard
```php
Pagocards::createMastercard([
    'email' => 'user@example.com',
    'firstname' => 'John',
    'lastname' => 'Doe'
])
```

#### Create Visa Card
```php
Pagocards::createVisacard([
    'email' => 'user@example.com',
    'firstname' => 'John',
    'lastname' => 'Doe'
])
```

#### Get Card Details
```php
Pagocards::getCard('card_id', 'visacard');
// or
Pagocards::getCard('card_id', 'mastercard');
```

#### List Cards
```php
Pagocards::listCards('visacard', $page = 1, $perPage = 50);
```

#### Fund Visa Card
```php
Pagocards::fundVisacard('card_id', 100.00);
```

#### Block Mastercard
```php
Pagocards::toggleCard('card_id', 'block', 'mastercard');
```

#### Unblock Mastercard
```php
Pagocards::toggleCard('card_id', 'unblock', 'mastercard');
```

### Wallet

#### Get Wallet Balance
```php
$balance = Pagocards::getBalance();
// Returns: ['issuance_balance' => 100, 'funding_balance' => 500]
```

## Error Handling

All methods throw an `\Exception` on failure. The exception message contains the API error message.

```php
try {
    $card = Pagocards::createVisacard([
        'email' => 'invalid-email',
        'firstname' => 'John',
        'lastname' => 'Doe'
    ]);
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
    echo "Code: " . $e->getCode();
}
```

## Configuration

### Change Base URL

```php
Pagocards::setBaseUrl('https://pagocards.com');
```

### Get Current Base URL

```php
$url = Pagocards::getBaseUrl();
```

## Testing

Run tests with:

```bash
composer test
```

Generate coverage report:

```bash
composer test-coverage
```

Coverage reports will be generated in the `coverage/` directory.

## Development

### Clone the Repository

```bash
git clone https://github.com/pagocards/php-laravel.git
cd php-laravel
```

### Install Dependencies

```bash
composer install
```

### Run Tests

```bash
composer test
```

### Build and Contribute

1. Create a feature branch
2. Make your changes
3. Write tests for new features
4. Ensure all tests pass
5. Submit a pull request

## Troubleshooting

### "Could not resolve: api"

This error typically means the request is being sent to an invalid URL. Ensure:
- `PAGOCARDS_API_URL` is set correctly in `.env`
- The base URL is the full domain (e.g., `https://pagocards.com`)

### "An email must have a "To", "Cc", or "Bcc" header"

This error occurs when making API calls without proper authentication. Ensure:
- `PAGOCARDS_PUBLIC_KEY` and `PAGOCARDS_SECRET_KEY` are set in `.env`
- These keys are valid and not expired

### "Incorrect column count"

If you're experiencing data issues, ensure:
- Your API response format matches expectations
- Your API keys have proper permissions

## Documentation

For complete documentation and API reference, visit:
- [Pagocards Documentation](https://docs.pagocards.com)
- [API Reference](https://pagocards.com/api/docs)
- [Dashboard](https://pagocards.com/dashboard)

## Support

For support and questions:
- Email: [support@pagocards.com](mailto:support@pagocards.com)
- Website: [https://pagocards.com](https://pagocards.com)
- GitHub Issues: [github.com/pagocards/php-laravel/issues](https://github.com/pagocards/php-laravel/issues)

## License

This SDK is open-sourced software licensed under the [MIT License](LICENSE).

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for a list of changes in each version.

## Contributing

Thank you for considering contributing! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

---

**Pagocards**: Transform your financial products with our robust card issuance platform.

