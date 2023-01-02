<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests;

use Http\Client\HttpClient;
use PHPUnit\Framework\TestCase as BaseTestCase;
use Symfony\Component\HttpClient\Response\MockResponse;
use Xvilo\OVpayApi\Client;
use Xvilo\OVpayApi\HttpClient\HttpClientBuilder;

abstract class TestCase extends BaseTestCase
{
    protected function getApiClientWithHttpClient(HttpClient $httpClient): Client
    {
        return new Client(new HttpClientBuilder($httpClient));
    }

    protected function getExampleCard(
        string $mediumType = 'Emv',
        string $status = 'Active'
    ): string
    {
        return <<<JSON
[
    {
        "xtat": "2af820fb-30a4-48fe-881e-21521c94a95e",
        "mediumType": "$mediumType",
        "xbot": "3f71d274-45f0-4b26-a628-7b7853301e89",
        "status": "$status",
        "personalization": {
          "name": "",
          "color": "Pink",
          "medium": "PhysicalCard"
        }
    }
]
JSON;
    }

    protected function isAuthenticatedRequest(array $normalized_headers, string $returnData): MockResponse
    {
        $authHeader = ($normalized_headers['authorization'] ?? []);
        if (count($authHeader) === 1 && $authHeader[0] === 'Authorization: Bearer TEST') {
            return new MockResponse($returnData);
        }

        return new MockResponse('', ['http_code' => 401]);
    }
}
