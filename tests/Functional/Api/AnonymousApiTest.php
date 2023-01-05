<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Functional\Api;

use DateTimeImmutable;
use Symfony\Component\HttpClient\Response\MockResponse;
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

    /**
     * @dataProvider registrationOpenDataProvider
     */
    public function testIsRegistrationOpen(string $data, bool $expected): void
    {
        $apiClient = $this->getApiClientWithHttpClient(
            $this->getMockHttpClient(new MockResponse($data))
        );
        self::assertEquals($expected, $apiClient->anonymous()->isRegistrationOpen());
    }

    public function registrationOpenDataProvider(): array
    {
        return [
            'bare-true' => ['data' => 'true', 'expected' => true],
            'true-in-array' => ['data' => '[true]', 'expected' => true],
            'bare-false' => ['data' => 'false', 'expected' => false],
            'false-in-array' => ['data' => '[false]', 'expected' => false],
            'random' => ['data' => '{"foo": "bar"}', 'expected' => false],
        ];
    }
}
