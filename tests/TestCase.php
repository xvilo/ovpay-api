<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use Psr\Http\Client\ClientInterface;
use Xvilo\OVpayApi\Client;
use Xvilo\OVpayApi\HttpClient\HttpClientBuilder;

abstract class TestCase extends BaseTestCase
{
    protected function getApiClientWithHttpClient(ClientInterface $httpClient): Client
    {
        return new Client(new HttpClientBuilder($httpClient));
    }
}
