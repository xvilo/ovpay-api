<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Functional\Api;

use Symfony\Component\HttpClient\Response\MockResponse;
use Xvilo\OVpayApi\Authentication\HeaderMethod;
use Xvilo\OVpayApi\Exception\UnauthorizedException;
use Xvilo\OVpayApi\Models\Payment;
use Xvilo\OVpayApi\Models\Payments;
use Xvilo\OVpayApi\Models\Receipt\ReceiptTrip;
use Xvilo\OVpayApi\Models\Token;
use Xvilo\OVpayApi\Tests\Functional\TestCase;

final class PaymentApiTest extends TestCase
{
    public function testGetPayments(): void
    {
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn ($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], $this->getPaymentsData())
        ));
        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));

        $result = $apiClient->payment()->getPayments('2af820fb-30a4-48fe-881e-21521c94a95e');

        $this->assertInstanceOf(Payments::class, $result);
        $this->assertTrue($result->isEndOfListReached());
        $this->assertSame(3, $result->getOffset());
        $this->assertNotEmpty($result->getItems());
        $this->assertCount(1, $result->getItems());
        $this->assertInstanceOf(Payment::class, $result->getItems()[0]);
    }

    public function testGetPaymentsNoAuth(): void
    {
        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Unauthorized. Either no credentials where provided, or the credentials have expired.');
        $this->expectExceptionCode(401);

        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn ($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], $this->getPaymentsData())
        ));

        $apiClient->payment()->getPayments('2af820fb-30a4-48fe-881e-21521c94a95e');
    }

    public function testGetReceipt(): void
    {
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn ($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], $this->getReceiptsData())
        ));
        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));

        $result = $apiClient->payment()->getReceipt('f7386e11-1142-47a9-bf61-39ac6825588e', 'EVENT-O12-12345678901234567890123456789');
        $this->assertNotEmpty($result->getRelatedPayments());
        $this->assertCount(1, $result->getRelatedPayments());
        $this->assertInstanceOf(Payment::class, $result->getRelatedPayments()[0]);

        $this->assertNotEmpty($result->getRelatedTrips());
        $this->assertCount(1, $result->getRelatedTrips());
        $this->assertInstanceOf(ReceiptTrip::class, $result->getRelatedTrips()[0]);

        $this->assertEmpty($result->getRelatedBalances());
        $this->assertInstanceOf(Token::class, $result->getToken());
    }

    public function testGetReceiptNoAuth(): void
    {
        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Unauthorized. Either no credentials where provided, or the credentials have expired.');
        $this->expectExceptionCode(401);

        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn ($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], $this->getReceiptsData())
        ));

        $apiClient->payment()->getReceipt('f7386e11-1142-47a9-bf61-39ac6825588e', 'EVENT-O12-12345678901234567890123456789');
    }
}
