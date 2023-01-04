<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Functional\Api;

use Symfony\Component\HttpClient\Response\MockResponse;
use Xvilo\OVpayApi\Authentication\HeaderMethod;
use Xvilo\OVpayApi\Exception\UnauthorizedException;
use Xvilo\OVpayApi\Models\Receipt\ReceiptTrip;
use Xvilo\OVpayApi\Models\Trip;
use Xvilo\OVpayApi\Models\Trips;
use Xvilo\OVpayApi\Tests\Functional\TestCase;

final class TripsApiTest extends TestCase
{
    public function testGetTrips(): void
    {
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], $this->getTripsData())
        ));
        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));
        /** @var Trips $trips */
        $trips = $apiClient->trips()->getTrips('2af820fb-30a4-48fe-881e-21521c94a95e');
        self::assertEquals(20, $trips->getOffset());
        self::assertEquals(true, $trips->isEndOfListReached());
        self::assertCount(1, $trips->getItems());
        self::assertInstanceOf(Trips\TripsItem::class, $trips->getItems()[0]);
        self::assertEquals(null, $trips->getItems()[0]->getCorrectedFrom());
        self::assertEquals(null, $trips->getItems()[0]->getCorrectedFromType());
    }

    public function testGetTripsNoAuth(): void
    {
        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Unauthorized. Either no credentials where provided, or the credentials have expired.');
        $this->expectExceptionCode(401);

        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], $this->getTripsData())
        ));

        $apiClient->trips()->getTrips('2af820fb-30a4-48fe-881e-21521c94a95e');
    }

    public function testGetTrip(): void
    {
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], $this->getTripData())
        ));
        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));

        $result = $apiClient->trips()->getTrip('2af820fb-30a4-48fe-881e-21521c94a95e', 12_345_678);
        self::assertInstanceOf(Trip::class, $result->getTrip());
        self::assertEquals(null, $result->getCorrectedFrom());
        self::assertEquals(null, $result->getCorrectedFromType());
        self::assertEquals(null, $result->getCorrectionOptions());
    }

    public function testGetTripNoAuth(): void
    {
        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Unauthorized. Either no credentials where provided, or the credentials have expired.');
        $this->expectExceptionCode(401);

        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], $this->getTripData())
        ));

        $apiClient->trips()->getTrip('2af820fb-30a4-48fe-881e-21521c94a95e', 12_345_678);
    }
}
