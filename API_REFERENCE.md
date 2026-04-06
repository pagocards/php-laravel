# API Reference

Complete API reference for the Pagocards PHP SDK.

## Table of Contents

1. [Client Class](#client-class)
2. [Card Methods](#card-methods)
3. [Wallet Methods](#wallet-methods)
4. [Configuration Methods](#configuration-methods)
5. [Error Handling](#error-handling)

---

## Client Class

### Constructor

```php
use Pagocards\SDK\Client;

$client = new Client($publicKey, $secretKey, $baseUrl = 'https://api.pagocards.com');
```

**Parameters:**
- `$publicKey` (string): Your Pagocards public API key
- `$secretKey` (string): Your Pagocards secret API key
- `$baseUrl` (string, optional): Base URL for API requests. Defaults to production.

**Example:**
```php
$client = new Client(
    'pk_live_xxxxx',
    'sk_live_xxxxx',
    'https://api.pagocards.com'
);
```

---

## Card Methods

### createMastercard()

Create a new Mastercard for a user.

**Signature:**
```php
public function createMastercard(array $data): array
```

**Parameters:**
- `$data` (array):
  - `email` (string, required): User's email address
  - `firstname` (string, required): User's first name
  - `lastname` (string, required): User's last name

**Returns:** Array containing card details

**Example:**
```php
$card = $client->createMastercard([
    'email' => 'john@example.com',
    'firstname' => 'John',
    'lastname' => 'Doe'
]);

// Response:
// {
//     "status": "success",
//     "card_id": "card_123456",
//     "card_number": "5555555555555555",
//     "expiry": "12/25",
//     "cvv": "123"
// }
```

**Throws:** `\Exception` on API error

---

### createVisacard()

Create a new Visa Card for a user.

**Signature:**
```php
public function createVisacard(array $data): array
```

**Parameters:**
- `$data` (array):
  - `email` (string, required): User's email address
  - `firstname` (string, required): User's first name
  - `lastname` (string, required): User's last name

**Returns:** Array containing card details

**Example:**
```php
$card = $client->createVisacard([
    'email' => 'jane@example.com',
    'firstname' => 'Jane',
    'lastname' => 'Smith'
]);
```

---

### fundVisacard()

Add funds to a Visa Card.

**Signature:**
```php
public function fundVisacard(string $cardId, float $amount): array
```

**Parameters:**
- `$cardId` (string): The card ID to fund
- `$amount` (float): Amount to fund in USD

**Returns:** Array containing transaction details

**Example:**
```php
$result = $client->fundVisacard('card_123456', 100.00);

// Response:
// {
//     "status": "success",
//     "transaction_id": "txn_123456",
//     "amount": 100.00,
//     "balance": 100.00
// }
```

**Throws:** `\Exception` on insufficient funds or invalid card

---

### getCard()

Retrieve details of a specific card.

**Signature:**
```php
public function getCard(string $cardId, string $type = 'visacard'): array
```

**Parameters:**
- `$cardId` (string): The card ID
- `$type` (string, optional): Card type - 'visacard' or 'mastercard'. Defaults to 'visacard'

**Returns:** Array containing card details

**Example:**
```php
$card = $client->getCard('card_123456', 'visacard');

// Response:
// {
//     "card_id": "card_123456",
//     "card_number": "4111111111111111",
//     "expiry": "12/25",
//     "status": "active",
//     "balance": 100.00,
//     "created_at": "2024-01-01T00:00:00Z"
// }
```

---

### listCards()

List all cards of a specific type.

**Signature:**
```php
public function listCards(string $type = 'visacard', int $page = 1, int $perPage = 50): array
```

**Parameters:**
- `$type` (string, optional): Card type - 'visacard' or 'mastercard'. Defaults to 'visacard'
- `$page` (int, optional): Page number. Defaults to 1
- `$perPage` (int, optional): Items per page. Defaults to 50

**Returns:** Array containing paginated list of cards

**Example:**
```php
$cards = $client->listCards('visacard', 1, 10);

// Response:
// {
//     "data": [
//         {
//             "card_id": "card_123456",
//             "card_number": "4111111111111111",
//             "status": "active"
//         },
//         // ... more cards
//     ],
//     "pagination": {
//         "current_page": 1,
//         "total_pages": 5,
//         "per_page": 10,
//         "total": 50
//     }
// }
```

---

### toggleCard()

Block or unblock a card.

**Signature:**
```php
public function toggleCard(string $cardId, string $action = 'block', string $type = 'visacard'): array
```

**Parameters:**
- `$cardId` (string): The card ID
- `$action` (string, optional): Action - 'block' or 'unblock'. Defaults to 'block'
- `$type` (string, optional): Card type - 'visacard' or 'mastercard'. Defaults to 'visacard'

**Returns:** Array containing updated card status

**Example:**
```php
// Block a card
$result = $client->toggleCard('card_123456', 'block', 'visacard');

// Unblock a card
$result = $client->toggleCard('card_123456', 'unblock', 'visacard');

// Response:
// {
//     "status": "success",
//     "card_id": "card_123456",
//     "card_status": "blocked"
// }
```

---

## Wallet Methods

### getBalance()

Get the current wallet balance.

**Signature:**
```php
public function getBalance(): array
```

**Endpoint:** `POST /api/mastercard/checkwallet`

**Parameters:** None

**Returns:** Array containing wallet balances

**Example:**
```php
$balance = $client->getBalance();

// Response:
// {
//     "issuance_balance": 500.00,
//     "funding_balance": 1000.00,
//     "total_balance": 1500.00,
//     "currency": "USD"
// }
```

---

## Card Details Methods

### getSensitiveData()

Get sensitive card data (PAN, CVV) for Visa cards.

**Signature:**
```php
public function getSensitiveData(string $cardId): array
```

**Endpoint:** `POST /api/visacard/getsensitive`

**Parameters:**
- `$cardId` (string): The card ID

**Returns:** Array containing card PAN, CVV, and expiry

**Example:**
```php
$data = $client->getSensitiveData('card_123456');

// Response includes: card number, CVV, expiry date
```

---

### getTransaction()

Get details of a specific transaction for a Visa card.

**Signature:**
```php
public function getTransaction(string $cardId, string $transactionId): array
```

**Endpoint:** `POST /api/visacard/gettransaction`

**Parameters:**
- `$cardId` (string): The card ID
- `$transactionId` (string): The transaction ID

**Returns:** Array containing transaction details

**Example:**
```php
$transaction = $client->getTransaction('card_123456', 'txn_789');
```

---

### getOTP()

Get OTP (One Time Password) for a Visa card for verification purposes.

**Signature:**
```php
public function getOTP(string $cardId): array
```

**Endpoint:** `POST /api/visacard/getotp`

**Parameters:**
- `$cardId` (string): The card ID

**Returns:** Array containing OTP information

**Example:**
```php
$otp = $client->getOTP('card_123456');
```

---

### check3DS()

Check 3D Secure status for a Mastercard.

**Signature:**
```php
public function check3DS(string $cardId, array $data = []): array
```

**Endpoint:** `POST /api/mastercard/check3ds`

**Parameters:**
- `$cardId` (string): The card ID
- `$data` (array, optional): Additional 3DS data

**Returns:** Array containing 3DS status

**Example:**
```php
$status = $client->check3DS('card_123456');
```

---

### approve3DS()

Approve 3D Secure transaction for a Mastercard.

**Signature:**
```php
public function approve3DS(string $cardId, array $data = []): array
```

**Endpoint:** `POST /api/mastercard/approve3ds`

**Parameters:**
- `$cardId` (string): The card ID
- `$data` (array, optional): Additional approval data

**Returns:** Array containing approval response

**Example:**
```php
$result = $client->approve3DS('card_123456', [
    'otp' => '123456',
    'transaction_id' => 'txn_789'
]);
```

---

## Configuration Methods

### setBaseUrl()

Set a custom base URL for API requests.

**Signature:**
```php
public function setBaseUrl(string $baseUrl): self
```

**Parameters:**
- `$baseUrl` (string): The base URL (trailing slash will be removed)

**Returns:** Self for method chaining

**Example:**
```php
$client->setBaseUrl('https://api.sandbox.pagocards.com')
       ->getBalance();
```

---

### getBaseUrl()

Get the current base URL.

**Signature:**
```php
public function getBaseUrl(): string
```

**Returns:** The current base URL

**Example:**
```php
$url = $client->getBaseUrl();
echo $url; // https://api.pagocards.com
```

---

## Error Handling

### Exception Handling

All methods throw `\Exception` on failure. The exception message contains details from the API.

**Example:**
```php
try {
    $card = $client->createVisacard([
        'email' => 'invalid-email',
        'firstname' => 'John',
        'lastname' => 'Doe'
    ]);
} catch (\Exception $e) {
    echo "Error Code: " . $e->getCode();
    echo "Error Message: " . $e->getMessage();

    // Log the error
    \Log::error('Card creation failed', [
        'code' => $e->getCode(),
        'message' => $e->getMessage()
    ]);
}
```

### Common Error Codes

| Code | Meaning |
|------|---------|
| 400 | Bad Request - Invalid parameters |
| 401 | Unauthorized - Invalid API keys |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource doesn't exist |
| 422 | Unprocessable Entity - Validation error |
| 429 | Too Many Requests - Rate limit exceeded |
| 500 | Server Error - API server error |

---

## Using the Facade

In Laravel applications, use the Facade for convenience:

```php
use Pagocards\SDK\Facades\Pagocards;

// All the same methods available
$card = Pagocards::createVisacard([...]);
$balance = Pagocards::getBalance();
```

---

## Examples

### Complete Card Creation and Funding Flow

```php
use Pagocards\SDK\Facades\Pagocards;

try {
    // Create a card
    $card = Pagocards::createVisacard([
        'email' => 'user@example.com',
        'firstname' => 'John',
        'lastname' => 'Doe'
    ]);

    $cardId = $card['card_id'];

    // Fund the card
    $result = Pagocards::fundVisacard($cardId, 100.00);

    // Get card details
    $details = Pagocards::getCard($cardId);

    // List all cards
    $cards = Pagocards::listCards('visacard');

    // Get wallet balance
    $balance = Pagocards::getBalance();

} catch (\Exception $e) {
    \Log::error('Card operation failed: ' . $e->getMessage());
    return response()->json(['error' => $e->getMessage()], $e->getCode());
}
```

---

For more information, visit [docs.pagocards.com](https://docs.pagocards.com)

