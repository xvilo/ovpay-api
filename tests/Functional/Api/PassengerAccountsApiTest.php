<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Functional\Api;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpClient\Response\MockResponse;
use Xvilo\OVpayApi\Authentication\HeaderMethod;
use Xvilo\OVpayApi\Exception\ApiException;
use Xvilo\OVpayApi\Exception\ApiForbiddenException;
use Xvilo\OVpayApi\Exception\ApiResourceNotFound;
use Xvilo\OVpayApi\Exception\UnauthorizedException;
use Xvilo\OVpayApi\Tests\Functional\TestCase;
use Iterator;

final class PassengerAccountsApiTest extends TestCase
{
    public function testAddSuccessfulMatch(): void
    {
        $returnUUid = Uuid::uuid4();
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn ($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], sprintf('"%s"', $returnUUid))
        ));
        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));

        $resp = $apiClient->passengerAccounts()->addByServiceReferenceId('1234567ABCDEF', 1445);
        $this->assertEquals($returnUUid, $resp->getPaymentExternalBackOfficeToken());
    }

    public function testUnsuccessfulMatch(): void
    {
        $paymentServiceReferenceId = 'NLOV1234567ABCDEFG';
        $amountInCents = 4830;
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            new MockResponse($this->getFailedAddPassengerAccountResponse($paymentServiceReferenceId, $amountInCents), ['http_code' => 404])
        ));
        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));

        $this->expectException(ApiResourceNotFound::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage(sprintf("Payment not found with serviceReferenceId (SRFID) '%s' and amountInCents '%s'", $paymentServiceReferenceId, $amountInCents));

        $apiClient->passengerAccounts()->addByServiceReferenceId($paymentServiceReferenceId, $amountInCents);
    }

    public function testUnauthenticatedRequest(): void
    {
        $returnUUid = Uuid::uuid4();
        $paymentServiceReferenceId = 'NLOV1234567ABCDEFG';
        $amountInCents = 4830;
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn ($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], sprintf('"%s"', $returnUUid))
        ));

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Unauthorized. Either no credentials where provided, or the credentials have expired.');
        $this->expectExceptionCode(401);
        $apiClient->passengerAccounts()->addByServiceReferenceId($paymentServiceReferenceId, $amountInCents);
    }

    public function testWrongFormat(): void
    {
        $paymentServiceReferenceId = 'NLOV12345ABCDEF';
        $amountInCents = 4830;

        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            new MockResponse('', ['http_code' => 400])
        ));

        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('Something unexpected happend.');
        $this->expectExceptionCode(400);

        $apiClient->passengerAccounts()->addByServiceReferenceId($paymentServiceReferenceId, $amountInCents);
    }

    public function testDeletePassengerAccount(): void
    {
        $cardXtat = Uuid::uuid4();
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn ($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], '', 204)
        ));

        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));

        $resp = $apiClient->passengerAccounts()->deletePassengerAccount($cardXtat->toString());
        $this->assertTrue($resp);
    }

    public function testDeleteRandomPassengerAccount(): void
    {
        $cardXtat = Uuid::uuid4();
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            fn ($method, $url, $options): MockResponse => $this->isAuthenticatedRequest($options['normalized_headers'], '{"type":"https://tools.ietf.org/html/rfc7231#section-6.5.3","title":"Action not allowed.","status":403,"detail":"Not allowed to perform actions on specified xtat.","traceId":"00-abcdefghijklmnopqest-1234567890-00"}', 403)
        ));

        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));

        $this->expectException(ApiForbiddenException::class);
        $this->expectExceptionMessage('Action not allowed. Not allowed to perform actions on specified xtat.');
        $this->expectExceptionCode(403);
        $apiClient->passengerAccounts()->deletePassengerAccount($cardXtat->toString());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('paymentServiceReferenceIdProvider')]
    public function testCorrectPaymentServiceReferenceIdClean(
        string $expected,
        string $paymentServiceReferenceId
    ): void {
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(
            static function ($method, $url, $options) use ($expected): \Symfony\Component\HttpClient\Response\MockResponse {
                self::assertMatchesRegularExpression(sprintf('/\?serviceReferenceId=%s/', $expected), $url);

                return new MockResponse(sprintf('"%s"', Uuid::uuid4()->toString()));
            }
        ));

        $apiClient->Authenticate(new HeaderMethod('Authorization', 'Bearer TEST'));
        $apiClient->passengerAccounts()->addByServiceReferenceId($paymentServiceReferenceId, 2023);
    }

    public static function paymentServiceReferenceIdProvider(): Iterator
    {
        yield 'Correct clean' => ['12345ABCDEF', 'NLOV12345ABCDEF'];
        yield 'No clean needed' => ['12345ABCDEF', '12345ABCDEF'];
        yield 'Only clean at beginning' => ['12345NLOVEF', '12345NLOVEF'];
    }
}
