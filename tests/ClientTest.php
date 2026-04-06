<?php

namespace Pagocards\SDK\Tests;

use PHPUnit\Framework\TestCase;
use Pagocards\SDK\Client;

class ClientTest extends TestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = new Client(
            'test_public_key',
            'test_secret_key',
            'https://api.test.pagocards.com'
        );
    }

    /**
     * Test client initialization
     */
    public function testClientInitialization()
    {
        $this->assertInstanceOf(Client::class, $this->client);
    }

    /**
     * Test base URL getter
     */
    public function testGetBaseUrl()
    {
        $this->assertEquals('https://api.test.pagocards.com', $this->client->getBaseUrl());
    }

    /**
     * Test base URL setter
     */
    public function testSetBaseUrl()
    {
        $this->client->setBaseUrl('https://api.pagocards.com');
        $this->assertEquals('https://api.pagocards.com', $this->client->getBaseUrl());
    }

    /**
     * Test base URL trailing slash removal
     */
    public function testSetBaseUrlRemovesTrailingSlash()
    {
        $this->client->setBaseUrl('https://api.pagocards.com/');
        $this->assertEquals('https://api.pagocards.com', $this->client->getBaseUrl());
    }

    /**
     * Test method chaining
     */
    public function testMethodChaining()
    {
        $result = $this->client->setBaseUrl('https://api.pagocards.com');
        $this->assertInstanceOf(Client::class, $result);
    }
}

