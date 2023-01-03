<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Functional\Api;

use Symfony\Component\HttpClient\Response\MockResponse;
use Xvilo\OVpayApi\Authentication\HeaderMethod;
use Xvilo\OVpayApi\Exception\UnauthorizedException;
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
    public function testGetPaymentsNoAuth(): void
    {
        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Unauthorized. Either no credentials where provided, or the credentials have expired.');
        $this->expectExceptionCode(401);

        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(function ($method, $url, $options): MockResponse {
            return $this->isAuthenticatedRequest($options['normalized_headers'], $this->getPaymentsData());
        }));

        $apiClient->payment()->getPayments('2af820fb-30a4-48fe-881e-21521c94a95e');
    }

    public function testGetReceipt(): void
    {
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(function ($method, $url, $options): MockResponse {
            return $this->isAuthenticatedRequest($options['normalized_headers'], $this->getReceiptsData());
        }));
        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));

        $this->assertEquals(
            json_decode($this->getReceiptsData(), true),
            $apiClient->payment()->getReceipt('f7386e11-1142-47a9-bf61-39ac6825588e', 'EVENT-O12-12345678901234567890123456789')
        );
    }

    public function testGetReceiptNoAuth(): void
    {
        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Unauthorized. Either no credentials where provided, or the credentials have expired.');
        $this->expectExceptionCode(401);

        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(function ($method, $url, $options): MockResponse {
            return $this->isAuthenticatedRequest($options['normalized_headers'], $this->getReceiptsData());
        }));

        $apiClient->payment()->getReceipt('f7386e11-1142-47a9-bf61-39ac6825588e', 'EVENT-O12-12345678901234567890123456789');
    }
}
