<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Functional\Api;

use Symfony\Component\HttpClient\Response\MockResponse;
use Xvilo\OVpayApi\Authentication\HeaderMethod;
use Xvilo\OVpayApi\Tests\Functional\TestCase;

final class PaymentApiTest extends TestCase
{
    public function testGetPayments(): void
    {
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(function ($method, $url, $options): MockResponse {
            return $this->isAuthenticatedRequest($options['normalized_headers'], $this->getPaymentsData());
        }));
        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));

        $this->assertEquals(
            json_decode($this->getPaymentsData(), true),
            $apiClient->payment()->getPayments('2af820fb-30a4-48fe-881e-21521c94a95e')
        );
    }
}
