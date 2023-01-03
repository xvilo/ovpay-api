<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Functional\Api;

use Symfony\Component\HttpClient\Response\MockResponse;
use Xvilo\OVpayApi\Authentication\HeaderMethod;
use Xvilo\OVpayApi\Exception\UnauthorizedException;
use Xvilo\OVpayApi\Tests\Functional\TestCase;

final class TripsApiTest extends TestCase
{
    public function testGetTrips(): void
    {
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(function ($method, $url, $options): MockResponse {
            return $this->isAuthenticatedRequest($options['normalized_headers'], $this->getTripsData());
        }));
        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));

        $this->assertEquals(json_decode($this->getTripsData(), true), $apiClient->trips()->getTrips('2af820fb-30a4-48fe-881e-21521c94a95e'));
    }

    public function testGetTripsNoAuth(): void
    {
        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Unauthorized. Either no credentials where provided, or the credentials have expired.');
        $this->expectExceptionCode(401);

        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(function ($method, $url, $options): MockResponse {
            return $this->isAuthenticatedRequest($options['normalized_headers'], $this->getTripsData());
        }));

        $apiClient->trips()->getTrips('2af820fb-30a4-48fe-881e-21521c94a95e');
    }

    public function testGetTrip(): void
    {
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(function ($method, $url, $options): MockResponse {
            return $this->isAuthenticatedRequest($options['normalized_headers'], $this->getTripData());
        }));
        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));

        $this->assertEquals(json_decode($this->getTripData(), true), $apiClient->trips()->getTrip('2af820fb-30a4-48fe-881e-21521c94a95e', 12345678));
    }

    public function testGetTripNoAuth(): void
    {
        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Unauthorized. Either no credentials where provided, or the credentials have expired.');
        $this->expectExceptionCode(401);

        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(function ($method, $url, $options): MockResponse {
            return $this->isAuthenticatedRequest($options['normalized_headers'], $this->getTripData());
        }));

        $apiClient->trips()->getTrip('2af820fb-30a4-48fe-881e-21521c94a95e', 12345678);
    }
}
