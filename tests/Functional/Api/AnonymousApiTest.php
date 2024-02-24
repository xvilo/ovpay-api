<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Functional\Api;

use DateTimeImmutable;
use Symfony\Component\HttpClient\Response\MockResponse;
use Xvilo\OVpayApi\Models\CorrectionOptions;
use Xvilo\OVpayApi\Models\Notices;
use Xvilo\OVpayApi\Tests\Functional\TestCase;
use Iterator;

final class AnonymousApiTest extends TestCase
{
    public function testGetNotices(): void
    {
        $apiClient = $this->getApiClientWithHttpClient(
            $this->getMockHttpClient(new MockResponse($this->getNoticesJsonPayload()))
        );

        $res = $apiClient->anonymous()->getNotices();
        $this->assertInstanceOf(Notices::class, $res);
        $this->assertIsArray($res->getServiceWebsiteDisruptions());
        $this->assertIsArray($res->getOvPayAppDisruptions());
        $this->assertEmpty($res->getServiceWebsiteDisruptions());
        $this->assertEmpty($res->getOvPayAppDisruptions());

        $this->assertInstanceOf(Notices\TermsAndConditions::class, $res->getTermsAndConditions());
        $this->assertSame([], $res->getTermsAndConditions()->getHighlights());
        $this->assertInstanceOf(DateTimeImmutable::class, $res->getTermsAndConditions()->getLastModified());

        $this->assertInstanceOf(Notices\PrivacyStatement::class, $res->getPrivacyStatement());
        $this->assertInstanceOf(DateTimeImmutable::class, $res->getPrivacyStatement()->getLastModified());
        $this->assertSame([], $res->getPrivacyStatement()->getHighlights());
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('registrationOpenDataProvider')]
    public function testIsRegistrationOpen(string $data, bool $expected): void
    {
        $apiClient = $this->getApiClientWithHttpClient(
            $this->getMockHttpClient(new MockResponse($data))
        );
        $this->assertEquals($expected, $apiClient->anonymous()->isRegistrationOpen());
    }

    public static function registrationOpenDataProvider(): Iterator
    {
        yield 'bare-true' => ['data' => 'true', 'expected' => true];
        yield 'true-in-array' => ['data' => '[true]', 'expected' => true];
        yield 'bare-false' => ['data' => 'false', 'expected' => false];
        yield 'false-in-array' => ['data' => '[false]', 'expected' => false];
        yield 'random' => ['data' => '{"foo": "bar"}', 'expected' => false];
    }

    public function testGetReceipt(): void
    {
        $apiClient = $this->getApiClientWithHttpClient($this->getMockHttpClient(new MockResponse($this->getAnonymousReceiptResponse())));

        $res = $apiClient->anonymous()->getReceipt('ABCD', 0000);
        $this->assertCount(1, $res->getRelatedPayments());
        $this->assertCount(4, $res->getRelatedTrips());
        $this->assertCount(0, $res->getRelatedBalances());
        $this->assertNull($res->getToken());

        foreach ($res->getRelatedTrips() as $relatedTrip) {
            if ($relatedTrip->getTrip()->getId() === 130788476) {
                $correctionOptions = $relatedTrip->getCorrectionOptions();
                $this->assertInstanceOf(CorrectionOptions::class, $correctionOptions);
                $this->assertSame('CorrectableNoStops', $correctionOptions->getCorrectableStatus());
                $this->assertInstanceOf(DateTimeImmutable::class, $correctionOptions->getCorrectionWindowEnd());
                $this->assertInstanceOf(DateTimeImmutable::class, $correctionOptions->getCorrectionWindowStart());
                $this->assertTrue($correctionOptions->isOnboardValidation());
                $this->assertIsArray($correctionOptions->getStops());
                $this->assertCount(1, $correctionOptions->getStops());

                $this->assertSame('45526', $correctionOptions->getStops()[0]->getPrivateCode());
                $this->assertCount(2, $correctionOptions->getStops()[0]->getLocalizedNames());
                $this->assertSame('nl-NL', $correctionOptions->getStops()[0]->getLocalizedNames()[0]->getLanguage());
                $this->assertSame('City 1, Stop 2', $correctionOptions->getStops()[0]->getLocalizedNames()[0]->getText());
                $this->assertSame('en-US', $correctionOptions->getStops()[0]->getLocalizedNames()[1]->getLanguage());
                $this->assertSame('City 1, Stop 2', $correctionOptions->getStops()[0]->getLocalizedNames()[1]->getText());
            }
        }
    }
}
