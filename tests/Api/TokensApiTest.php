<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Api;

use Symfony\Component\HttpClient\Response\MockResponse;
use Xvilo\OVpayApi\Authentication\HeaderMethod;
use Xvilo\OVpayApi\Tests\TestCase;

final class TokensApiTest extends TestCase
{
    public function testGetCards(): void
    {
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(function ($method, $url, $options): MockResponse {
            return $this->isAuthenticatedRequest($options['normalized_headers'], $this->getExampleCard());
        }));
        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));

        $this->assertEquals(json_decode($this->getExampleCard(), true), $apiClient->tokens()->getPaymentCards());
    }
}
