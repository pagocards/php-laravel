# Examples

Real-world examples of using the Pagocards PHP SDK.

## Table of Contents

1. [Basic Setup](#basic-setup)
2. [Creating Cards](#creating-cards)
3. [Managing Cards](#managing-cards)
4. [Funding Cards](#funding-cards)
5. [Checking Balance](#checking-balance)
6. [Error Handling](#error-handling)
7. [Laravel Integration](#laravel-integration)

---

## Basic Setup

### Using Environment Variables

```php
use Pagocards\SDK\Client;

$client = new Client(
    env('PAGOCARDS_PUBLIC_KEY'),
    env('PAGOCARDS_SECRET_KEY')
);
```

### Using Facade (Laravel)

```php
use Pagocards\SDK\Facades\Pagocards;

// All methods available directly
Pagocards::getBalance();
```

---

## Creating Cards

### Create a Visa Card

```php
use Pagocards\SDK\Client;

$client = new Client(
    'your_public_key',
    'your_secret_key'
);

try {
    $card = $client->createVisacard([
        'email' => 'customer@example.com',
        'firstname' => 'John',
        'lastname' => 'Doe'
    ]);

    echo "Card created successfully!";
    echo "Card ID: " . $card['card_id'];
    echo "Card Number: " . $card['card_number'];

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

### Create a Mastercard

```php
$card = $client->createMastercard([
    'email' => 'customer@example.com',
    'firstname' => 'Jane',
    'lastname' => 'Smith'
]);

echo "Mastercard created: " . $card['card_id'];
```

### Batch Create Multiple Cards

```php
$emails = [
    'user1@example.com',
    'user2@example.com',
    'user3@example.com'
];

$createdCards = [];

foreach ($emails as $email) {
    try {
        $card = $client->createVisacard([
            'email' => $email,
            'firstname' => 'Customer',
            'lastname' => 'Name'
        ]);

        $createdCards[] = $card;

    } catch (\Exception $e) {
        echo "Failed to create card for {$email}: " . $e->getMessage();
    }
}

echo "Successfully created " . count($createdCards) . " cards";
```

---

## Managing Cards

### Get Card Details

```php
$cardId = 'card_123456';

try {
    $card = $client->getCard($cardId, 'visacard');

    echo "Card Status: " . $card['status'];
    echo "Card Balance: " . $card['balance'];
    echo "Created At: " . $card['created_at'];

} catch (\Exception $e) {
    echo "Card not found: " . $e->getMessage();
}
```

### List All Visa Cards

```php
try {
    // Get first page with 20 items per page
    $response = $client->listCards('visacard', 1, 20);

    foreach ($response['data'] as $card) {
        echo "Card ID: " . $card['card_id'] . "\n";
        echo "Status: " . $card['status'] . "\n";
        echo "---\n";
    }

    // Get next page
    $nextPage = $client->listCards('visacard', 2, 20);

} catch (\Exception $e) {
    echo "Error listing cards: " . $e->getMessage();
}
```

### Block a Card

```php
$cardId = 'card_123456';

try {
    $result = $client->toggleCard($cardId, 'block', 'visacard');
    echo "Card blocked successfully";

} catch (\Exception $e) {
    echo "Error blocking card: " . $e->getMessage();
}
```

### Unblock a Card

```php
try {
    $result = $client->toggleCard($cardId, 'unblock', 'visacard');
    echo "Card unblocked successfully";

} catch (\Exception $e) {
    echo "Error unblocking card: " . $e->getMessage();
}
```

---

## Funding Cards

### Fund a Visa Card

```php
$cardId = 'card_123456';
$amount = 100.00;

try {
    $result = $client->fundVisacard($cardId, $amount);

    echo "Card funded successfully!";
    echo "Transaction ID: " . $result['transaction_id'];
    echo "New Balance: $" . $result['balance'];

} catch (\Exception $e) {
    echo "Error funding card: " . $e->getMessage();
}
```

### Fund Multiple Cards

```php
$fundingData = [
    ['card_id' => 'card_123456', 'amount' => 100.00],
    ['card_id' => 'card_789012', 'amount' => 50.00],
    ['card_id' => 'card_345678', 'amount' => 75.00],
];

foreach ($fundingData as $data) {
    try {
        $result = $client->fundVisacard($data['card_id'], $data['amount']);
        echo "Funded {$data['card_id']} with ${data['amount']}\n";

    } catch (\Exception $e) {
        echo "Failed to fund {$data['card_id']}: " . $e->getMessage() . "\n";
    }
}
```

---

## Checking Balance

### Get Wallet Balance

```php
try {
    $balance = $client->getBalance();

    echo "Issuance Balance: $" . $balance['issuance_balance'];
    echo "Funding Balance: $" . $balance['funding_balance'];
    echo "Total Balance: $" . $balance['total_balance'];

} catch (\Exception $e) {
    echo "Error checking balance: " . $e->getMessage();
}
```

---

## Error Handling

### Comprehensive Error Handling

```php
use Pagocards\SDK\Client;

$client = new Client('key', 'secret');

try {
    $card = $client->createVisacard([
        'email' => 'user@example.com',
        'firstname' => 'John',
        'lastname' => 'Doe'
    ]);

    echo "Success: Card created";

} catch (\Exception $e) {
    $code = $e->getCode();
    $message = $e->getMessage();

    switch ($code) {
        case 400:
            echo "Bad Request: " . $message;
            break;
        case 401:
            echo "Unauthorized: Check your API keys";
            break;
        case 403:
            echo "Forbidden: Insufficient permissions";
            break;
        case 404:
            echo "Not Found: Resource doesn't exist";
            break;
        case 422:
            echo "Validation Error: " . $message;
            break;
        case 429:
            echo "Rate Limited: Please try again later";
            break;
        default:
            echo "Error: " . $message;
    }

    // Log error for debugging
    \Log::error('API Error', [
        'code' => $code,
        'message' => $message,
        'trace' => $e->getTraceAsString()
    ]);
}
```

### Custom Error Handler

```php
function handlePagocardException(\Exception $e, $context = '')
{
    $code = $e->getCode();
    $message = $e->getMessage();

    $errorLog = [
        'timestamp' => now(),
        'context' => $context,
        'code' => $code,
        'message' => $message
    ];

    \Log::error('Pagocards API Error', $errorLog);

    // Send notification to admin if critical
    if ($code >= 500) {
        \Log::critical('Pagocards API Critical Error', $errorLog);
        // Notify admin
    }

    return response()->json(['error' => 'An error occurred'], 500);
}

// Usage
try {
    $card = $client->createVisacard([...]);
} catch (\Exception $e) {
    handlePagocardException($e, 'card_creation');
}
```

---

## Laravel Integration

### Using in Controllers

```php
namespace App\Http\Controllers;

use Pagocards\SDK\Facades\Pagocards;

class CardController extends Controller
{
    public function createCard(Request $request)
    {
        try {
            $card = Pagocards::createVisacard([
                'email' => $request->email,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname
            ]);

            // Store in database
            Card::create([
                'user_id' => auth()->id(),
                'card_id' => $card['card_id'],
                'card_number' => $card['card_number'],
                'type' => 'visa'
            ]);

            return response()->json(['success' => true, 'card' => $card]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function fundCard(Request $request)
    {
        try {
            $card = Card::findOrFail($request->card_id);

            $result = Pagocards::fundVisacard($card->card_id, $request->amount);

            // Log transaction
            Transaction::create([
                'card_id' => $card->id,
                'amount' => $request->amount,
                'transaction_id' => $result['transaction_id']
            ]);

            return response()->json(['success' => true, 'result' => $result]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
```

### Using in Service Classes

```php
namespace App\Services;

use Pagocards\SDK\Facades\Pagocards;

class CardService
{
    public function createCardForUser($user)
    {
        try {
            $card = Pagocards::createVisacard([
                'email' => $user->email,
                'firstname' => $user->first_name,
                'lastname' => $user->last_name
            ]);

            return $card;

        } catch (\Exception $e) {
            \Log::error('Card creation failed for user: ' . $user->id, [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function getCardBalance($cardId)
    {
        try {
            $card = Pagocards::getCard($cardId);
            return $card['balance'];

        } catch (\Exception $e) {
            \Log::error('Failed to get card balance: ' . $cardId);
            throw $e;
        }
    }

    public function checkWalletBalance()
    {
        try {
            return Pagocards::getBalance();

        } catch (\Exception $e) {
            \Log::error('Failed to check wallet balance');
            throw $e;
        }
    }
}
```

### Using in Jobs (Queued Processing)

```php
namespace App\Jobs;

use Pagocards\SDK\Facades\Pagocards;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateCardJob implements ShouldQueue
{
    use Queueable, SerializesModels, InteractsWithQueue;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        try {
            $card = Pagocards::createVisacard([
                'email' => $this->user->email,
                'firstname' => $this->user->first_name,
                'lastname' => $this->user->last_name
            ]);

            $this->user->cards()->create([
                'card_id' => $card['card_id'],
                'card_number' => $card['card_number'],
                'type' => 'visa'
            ]);

        } catch (\Exception $e) {
            \Log::error('Card creation job failed: ' . $e->getMessage());
            $this->fail($e);
        }
    }
}

// Usage
CreateCardJob::dispatch($user);
```

---

For more examples and information, visit [docs.pagocards.com](https://docs.pagocards.com)

