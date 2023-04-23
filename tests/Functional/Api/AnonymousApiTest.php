<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Functional\Api;

use DateTimeImmutable;
use Symfony\Component\HttpClient\Response\MockResponse;
use Xvilo\OVpayApi\Models\CorrectionOptions;
use Xvilo\OVpayApi\Models\Notices;
use Xvilo\OVpayApi\Tests\Functional\TestCase;

final class AnonymousApiTest extends TestCase
{
    public function testGetNotices(): void
    {
        $apiClient = $this->getApiClientWithHttpClient(
            $this->getMockHttpClient(new MockResponse($this->getNoticesJsonPayload()))
        );

        $res = $apiClient->anonymous()->getNotices();
        self::assertInstanceOf(Notices::class, $res);
        self::assertIsArray($res->getServiceWebsiteDisruptions());
        self::assertIsArray($res->getOvPayAppDisruptions());
        self::assertEmpty($res->getServiceWebsiteDisruptions());
        self::assertEmpty($res->getOvPayAppDisruptions());

        self::assertInstanceOf(Notices\TermsAndConditions::class, $res->getTermsAndConditions());
        self::assertEquals([], $res->getTermsAndConditions()->getHighlights());
        self::assertInstanceOf(DateTimeImmutable::class, $res->getTermsAndConditions()->getLastModified());

        self::assertInstanceOf(Notices\PrivacyStatement::class, $res->getPrivacyStatement());
        self::assertInstanceOf(DateTimeImmutable::class, $res->getPrivacyStatement()->getLastModified());
        self::assertEquals([], $res->getPrivacyStatement()->getHighlights());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('registrationOpenDataProvider')]
    public function testIsRegistrationOpen(string $data, bool $expected): void
    {
        $apiClient = $this->getApiClientWithHttpClient(
            $this->getMockHttpClient(new MockResponse($data))
        );
        self::assertEquals($expected, $apiClient->anonymous()->isRegistrationOpen());
    }

    public static function registrationOpenDataProvider(): array
    {
        return [
            'bare-true' => ['data' => 'true', 'expected' => true],
            'true-in-array' => ['data' => '[true]', 'expected' => true],
            'bare-false' => ['data' => 'false', 'expected' => false],
            'false-in-array' => ['data' => '[false]', 'expected' => false],
            'random' => ['data' => '{"foo": "bar"}', 'expected' => false],
        ];
    }

    public function testGetReceipt(): void
    {
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(new MockResponse($this->getAnonymousReceiptResponse())));

        $res = $apiClient->anonymous()->getReceipt('ABCD', 0000);
        self::assertCount(1, $res->getRelatedPayments());
        self::assertCount(4, $res->getRelatedTrips());
        self::assertCount(0, $res->getRelatedBalances());
        self::assertNull($res->getToken());

        foreach ($res->getRelatedTrips() as $relatedTrip) {
            if ($relatedTrip->getTrip()->getId() === 130788476) {
                $correctionOptions = $relatedTrip->getCorrectionOptions();
                self::assertInstanceOf(CorrectionOptions::class, $correctionOptions);
                self::assertEquals('CorrectableNoStops', $correctionOptions->getCorrectableStatus());
                self::assertInstanceOf(DateTimeImmutable::class, $correctionOptions->getCorrectionWindowEnd());
                self::assertInstanceOf(DateTimeImmutable::class, $correctionOptions->getCorrectionWindowStart());
                self::assertTrue($correctionOptions->isOnboardValidation());
                self::assertIsArray($correctionOptions->getStops());
                self::assertCount(1, $correctionOptions->getStops());

                self::assertEquals('45526', $correctionOptions->getStops()[0]->getPrivateCode());
                self::assertCount(2, $correctionOptions->getStops()[0]->getLocalizedNames());
                self::assertEquals('nl-NL', $correctionOptions->getStops()[0]->getLocalizedNames()[0]->getLanguage());
                self::assertEquals('City 1, Stop 2', $correctionOptions->getStops()[0]->getLocalizedNames()[0]->getText());
                self::assertEquals('en-US', $correctionOptions->getStops()[0]->getLocalizedNames()[1]->getLanguage());
                self::assertEquals('City 1, Stop 2', $correctionOptions->getStops()[0]->getLocalizedNames()[1]->getText());
            }
        }
    }
}
