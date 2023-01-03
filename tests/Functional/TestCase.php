<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Functional;

use Http\Client\HttpClient;
use Xvilo\OVpayApi\Tests\TestCase as BaseTestCase;
use Symfony\Component\HttpClient\HttplugClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param callable|callable[]|ResponseInterface|ResponseInterface[]|iterable|null $responseFactory
     */
    protected function getMockHttpClient(callable|ResponseInterface|iterable|null $responseFactory = null): HttpClient
    {
        return new HttplugClient(new MockHttpClient($responseFactory));
    }

    protected function getTripsData(): string
    {
        return <<<JSON
{
  "offset": 20,
  "endOfListReached": true,
  "items": [
    {
      "trip": {$this->getTrip()},
      "correctedFrom": null,
      "correctedFromType": null
    }
  ]
}
JSON;
    }

    protected function getTripData(): string
    {
        return <<<JSON
{
  "token": {
    "mediumType": "Emv",
    "xbot": "13fa524a-14e3-4fb4-ba28-cbc56bac45ea",
    "status": "Active",
    "personalization": {
      "name": "",
      "color": "Pink",
      "medium": "PhysicalCard"
    }
  },
  "correctionOptions": null,
  "trip": {$this->getTrip()},
  "correctedFrom": null,
  "correctedFromType": null
}
JSON;
    }

    protected function getTrip(): string
    {
        return <<<JSON
{
    "xbot": "fac5b73b-54e6-4fc4-ab24-7249481c0fdb",
    "id": 12345678,
    "version": 1,
    "transport": "RAIL",
    "status": "COMPLETE",
    "checkInLocation": "Utrecht Centraal",
    "checkInTimestamp": "2022-12-30T13:37:00+01:00",
    "checkOutLocation": "Utrecht Centraal",
    "checkOutTimestamp": "2022-12-30T14:37:00+01:00",
    "currency": "EUR",
    "fare": 0,
    "organisationName": "NS",
    "loyaltyOrDiscount": false
}
JSON;
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

    protected function getNoticesJsonPayload(): string
    {
        return <<<JSON
{
  "serviceWebsiteDisruptions": [],
  "ovPayAppDisruptions": [],
  "termsAndConditions": {
    "lastModified": "2022-12-19T00:00:00+01:00",
    "highlights": []
  },
  "privacyStatement": {
    "lastModified": "2022-12-19T00:00:00+01:00",
    "highlights": []
  }
}
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
