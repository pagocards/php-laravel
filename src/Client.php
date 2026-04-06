<?php

namespace Pagocards\SDK;

class Client
{
    /**
     * SDK Version
     */
    const VERSION = '1.0.0';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * Constructor
     *
     * @param string $publicKey
     * @param string $secretKey
     * @param string $baseUrl
     */
    public function __construct($publicKey, $secretKey, $baseUrl = 'https://pagocards.com')
    {
        $this->apiKey = $publicKey;
        $this->secretKey = $secretKey;
        $this->baseUrl = rtrim($baseUrl, '/');

        $this->httpClient = new \GuzzleHttp\Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 30.0,
            'headers' => [
                'User-Agent' => 'Pagocards-SDK/' . self::VERSION,
                'Accept' => 'application/json',
            ]
        ]);
    }

    /**
     * Create a new Mastercard
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createMastercard($data)
    {
        return $this->request('POST', '/api/mastercard/createcard', $data);
    }

    /**
     * Create a new Visa Card
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createVisacard($data)
    {
        return $this->request('POST', '/api/visacard/createcard', $data);
    }

    /**
     * Fund a Visa Card
     *
     * @param string $cardId
     * @param float $amount
     * @return array
     * @throws \Exception
     */
    public function fundVisacard($cardId, $amount)
    {
        return $this->request('POST', '/api/visacard/fundcard', [
            'public_key' => $this->apiKey,
            'secret_key' => $this->secretKey,
            'cardid' => $cardId,
            'amount' => $amount
        ]);
    }

    /**
     * Get card details
     *
     * @param string $cardId
     * @param string $type (mastercard|visacard)
     * @return array
     * @throws \Exception
     */
    public function getCard($cardId, $type = 'visacard')
    {
        if ($type === 'mastercard') {
            return $this->request('POST', '/api/mastercard/getcarddetails', [
                'public_key' => $this->apiKey,
                'secret_key' => $this->secretKey,
                'card_id' => $cardId
            ]);
        }

        return $this->request('POST', '/api/visacard/getcard', [
            'public_key' => $this->apiKey,
            'secret_key' => $this->secretKey,
            'card_id' => $cardId
        ]);
    }

    /**
     * List all cards
     *
     * @param string $type (mastercard|visacard)
     * @param int $page
     * @param int $perPage
     * @return array
     * @throws \Exception
     */
    public function listCards($type = 'visacard', $page = 1, $perPage = 50)
    {
        if ($type === 'mastercard') {
            return $this->request('POST', '/api/mastercard/getallcards', [
                'public_key' => $this->apiKey,
                'secret_key' => $this->secretKey,
                'page' => $page,
                'per_page' => $perPage
            ]);
        }

        return $this->request('POST', '/api/visacard/getallcards', [
            'public_key' => $this->apiKey,
            'secret_key' => $this->secretKey,
            'page' => $page,
            'per_page' => $perPage
        ]);
    }

    /**
     * Block/Unblock a card
     *
     * @param string $cardId
     * @param string $action (block|unblock)
     * @param string $type (mastercard|visacard)
     * @return array
     * @throws \Exception
     */
    public function toggleCard($cardId, $action = 'block', $type = 'visacard')
    {
        if ($type === 'mastercard') {
            if ($action === 'block') {
                return $this->request('POST', '/api/mastercard/blockdigital', [
                    'public_key' => $this->apiKey,
                    'secret_key' => $this->secretKey,
                    'card_id' => $cardId
                ]);
            } else {
                return $this->request('POST', '/api/mastercard/unblockdigital', [
                    'public_key' => $this->apiKey,
                    'secret_key' => $this->secretKey,
                    'card_id' => $cardId
                ]);
            }
        }

        // For visa cards, block/unblock not directly exposed
        throw new \Exception('Block/Unblock for Visa cards is managed through the application interface.');
    }

    /**
     * Get wallet balance
     *
     * @return array
     * @throws \Exception
     */
    public function getBalance()
    {
        return $this->request('POST', '/api/mastercard/checkwallet', [
            'public_key' => $this->apiKey,
            'secret_key' => $this->secretKey
        ]);
    }

    /**
     * Get sensitive card data (PAN, CVV)
     *
     * @param string $cardId
     * @return array
     * @throws \Exception
     */
    public function getSensitiveData($cardId)
    {
        return $this->request('POST', '/api/visacard/getsensitive', [
            'public_key' => $this->apiKey,
            'secret_key' => $this->secretKey,
            'card_id' => $cardId
        ]);
    }

    /**
     * Get card transaction
     *
     * @param string $cardId
     * @param string $transactionId
     * @return array
     * @throws \Exception
     */
    public function getTransaction($cardId, $transactionId)
    {
        return $this->request('POST', '/api/visacard/gettransaction', [
            'public_key' => $this->apiKey,
            'secret_key' => $this->secretKey,
            'card_id' => $cardId,
            'transaction_id' => $transactionId
        ]);
    }

    /**
     * Get OTP for card
     *
     * @param string $cardId
     * @return array
     * @throws \Exception
     */
    public function getOTP($cardId)
    {
        return $this->request('POST', '/api/visacard/getotp', [
            'public_key' => $this->apiKey,
            'secret_key' => $this->secretKey,
            'card_id' => $cardId
        ]);
    }

    /**
     * Check 3DS for Mastercard
     *
     * @param string $cardId
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function check3DS($cardId, $data = [])
    {
        $payload = array_merge([
            'public_key' => $this->apiKey,
            'secret_key' => $this->secretKey,
            'card_id' => $cardId
        ], $data);

        return $this->request('POST', '/api/mastercard/check3ds', $payload);
    }

    /**
     * Approve 3DS for Mastercard
     *
     * @param string $cardId
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function approve3DS($cardId, $data = [])
    {
        $payload = array_merge([
            'public_key' => $this->apiKey,
            'secret_key' => $this->secretKey,
            'card_id' => $cardId
        ], $data);

        return $this->request('POST', '/api/mastercard/approve3ds', $payload);
    }

    /**
     * Make an HTTP request
     *
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws \Exception
     */
    private function request($method, $endpoint, $data = [])
    {
        try {
            if ($method === 'GET') {
                $response = $this->httpClient->request($method, $endpoint, [
                    'query' => $data
                ]);
            } else {
                $response = $this->httpClient->request($method, $endpoint, [
                    'json' => $data
                ]);
            }

            $body = $response->getBody()->getContents();
            return json_decode($body, true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $response = $e->getResponse();

            if ($response) {
                $body = $response->getBody()->getContents();
                $data = json_decode($body, true);
                throw new \Exception($data['message'] ?? $e->getMessage(), $response->getStatusCode());
            }

            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Set a custom base URL
     *
     * @param string $baseUrl
     * @return $this
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        return $this;
    }

    /**
     * Get current base URL
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }
}

