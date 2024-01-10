<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Functional\Api;

use Symfony\Component\HttpClient\Response\MockResponse;
use Xvilo\OVpayApi\Authentication\HeaderMethod;
use Xvilo\OVpayApi\Exception\UnauthorizedException;
use Xvilo\OVpayApi\Models\Token\TokenPersonalization;
use Xvilo\OVpayApi\Tests\Functional\TestCase;

final class TokensApiTest extends TestCase
{
    public function testGetCards(): void
    {
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn ($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], $this->getExampleCard())
        ));
        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));

        $result = $apiClient->tokens()->getPaymentCards();
        self::assertCount(1, $result);
        self::assertIsString($result[0]->getXbot());
        self::assertIsString($result[0]->getMediumType());
        self::assertIsString($result[0]->getExternalTransitAccountToken());
        self::assertIsString($result[0]->getStatus());

        $tokenPersonalization = $result[0]->getPersonalization();
        self::assertInstanceOf(TokenPersonalization::class, $tokenPersonalization);
        self::assertEquals('Pink', $tokenPersonalization->getColor());
        self::assertEquals('PhysicalCard', $tokenPersonalization->getMedium());
        self::assertEquals('', $tokenPersonalization->getName());
    }

    public function testGetCardsNoAuth(): void
    {
        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Unauthorized. Either no credentials where provided, or the credentials have expired.');
        $this->expectExceptionCode(401);

        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn ($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], $this->getExampleCard())
        ));

        $apiClient->tokens()->getPaymentCards();
    }
}
