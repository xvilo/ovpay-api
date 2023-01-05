<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Functional\Api;

use DateTimeImmutable;
use DateTimeZone;
use Symfony\Component\HttpClient\Response\MockResponse;
use Xvilo\OVpayApi\Authentication\HeaderMethod;
use Xvilo\OVpayApi\Exception\UnauthorizedException;
use Xvilo\OVpayApi\Models\Trip;
use Xvilo\OVpayApi\Models\Trips;
use Xvilo\OVpayApi\Tests\Functional\TestCase;
use Exception;

final class TripsApiTest extends TestCase
{
    public function testGetTrips(): void
    {
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn ($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], $this->getTripsData())
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
            fn ($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], $this->getTripsData())
        ));

        $apiClient->trips()->getTrips('2af820fb-30a4-48fe-881e-21521c94a95e');
    }

    /**
     * @throws Exception
     */
    public function testGetTrip(): void
    {
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn ($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], $this->getTripData())
        ));
        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));

        $result = $apiClient->trips()->getTrip('2af820fb-30a4-48fe-881e-21521c94a95e', 12_345_678);
        self::assertEquals(null, $result->getCorrectedFrom());
        self::assertEquals(null, $result->getCorrectedFromType());
        self::assertEquals(null, $result->getCorrectionOptions());

        $trip = $result->getTrip();
        self::assertInstanceOf(Trip::class, $trip);
        self::assertEquals('fac5b73b-54e6-4fc4-ab24-7249481c0fdb', $trip->getXbot());
        self::assertEquals(12345678, $trip->getId());
        self::assertEquals(1, $trip->getVersion());
        self::assertEquals('RAIL', $trip->getTransport());
        self::assertEquals('COMPLETE', $trip->getStatus());
        self::assertEquals('Utrecht Centraal', $trip->getCheckInLocation());
        self::assertEquals(new DateTimeImmutable('2022-12-30 13:37:00.000000', new DateTimeZone('+0100')), $trip->getCheckInTimestamp());
        self::assertEquals('Utrecht Centraal', $trip->getCheckOutLocation());
        self::assertEquals(new DateTimeImmutable('2022-12-30 14:37:00.000000', new DateTimeZone('+0100')), $trip->getCheckOutTimestamp());
        self::assertEquals('EUR', $trip->getCurrency());
        self::assertEquals(0, $trip->getFare());
        self::assertEquals('NS', $trip->getOrganisationName());
        self::assertEquals(false, $trip->isLoyaltyOrDiscount());
    }

    public function testGetTripNoAuth(): void
    {
        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Unauthorized. Either no credentials where provided, or the credentials have expired.');
        $this->expectExceptionCode(401);

        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn ($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], $this->getTripData())
        ));

        $apiClient->trips()->getTrip('2af820fb-30a4-48fe-881e-21521c94a95e', 12_345_678);
    }
}
