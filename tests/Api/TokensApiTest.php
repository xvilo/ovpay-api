<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Api;

use Symfony\Component\HttpClient\HttplugClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Xvilo\OVpayApi\Authentication\HeaderMethod;
use Xvilo\OVpayApi\Tests\TestCase;

final class TokensApiTest extends TestCase
{
    public function testGetCards(): void
    {
        $client = new HttplugClient(new MockHttpClient([
            function ($method, $url, $options): MockResponse {
                return $this->isAuthenticatedRequest($options['normalized_headers'], $this->getExampleCard());
            }
        ]));
        $apiClient = $this->getApiClientWithHttpClient($client);
        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));

        $this->assertEquals(json_decode($this->getExampleCard(), true), $apiClient->tokens()->getPaymentCards());
    }
}
