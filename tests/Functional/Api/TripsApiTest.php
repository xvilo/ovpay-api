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
        $this->assertSame(20, $trips->getOffset());
        $this->assertEquals(true, $trips->isEndOfListReached());
        $this->assertCount(1, $trips->getItems());
        $this->assertInstanceOf(Trips\TripsItem::class, $trips->getItems()[0]);
        $this->assertEquals(null, $trips->getItems()[0]->getCorrectedFrom());
        $this->assertEquals(null, $trips->getItems()[0]->getCorrectedFromType());
        $this->assertInstanceOf(Trip::class, $trips->getItems()[0]->getTrip());
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
        $this->assertEquals(null, $result->getCorrectedFrom());
        $this->assertEquals(null, $result->getCorrectedFromType());
        $this->assertEquals(null, $result->getCorrectionOptions());

        $trip = $result->getTrip();
        $this->assertInstanceOf(Trip::class, $trip);
        $this->assertSame('fac5b73b-54e6-4fc4-ab24-7249481c0fdb', $trip->getExternalBackOfficeToken());
        $this->assertSame(12345678, $trip->getId());
        $this->assertSame(1, $trip->getVersion());
        $this->assertSame('RAIL', $trip->getTransport());
        $this->assertSame('COMPLETE', $trip->getStatus());
        $this->assertSame('Utrecht Centraal', $trip->getCheckInLocation());
        $this->assertEquals(new DateTimeImmutable('2022-12-30 13:37:00.000000', new DateTimeZone('+0100')), $trip->getCheckInTimestamp());
        $this->assertSame('Utrecht Centraal', $trip->getCheckOutLocation());
        $this->assertEquals(new DateTimeImmutable('2022-12-30 14:37:00.000000', new DateTimeZone('+0100')), $trip->getCheckOutTimestamp());
        $this->assertSame('EUR', $trip->getCurrency());
        $this->assertSame(0, $trip->getFare());
        $this->assertSame('NS', $trip->getOrganisationName());
        $this->assertEquals(false, $trip->isLoyaltyOrDiscount());
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
