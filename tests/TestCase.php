<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests;

use Http\Client\HttpClient;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Xvilo\OVpayApi\Client;
use Xvilo\OVpayApi\HttpClient\HttpClientBuilder;

abstract class TestCase extends BaseTestCase
{
    protected function getApiClientWithHttpClient(HttpClient $httpClient): Client
    {
        return new Client(new HttpClientBuilder($httpClient));
    }
}
