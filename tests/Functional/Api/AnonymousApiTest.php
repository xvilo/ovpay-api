<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Functional\Api;

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
        self::assertInstanceOf(Notices\TermsAndConditions::class, $res->getTermsAndConditions());
        self::assertInstanceOf(Notices\PrivacyStatement::class, $res->getPrivacyStatement());
        self::assertIsArray($res->getServiceWebsiteDisruptions());
        self::assertIsArray($res->getOvPayAppDisruptions());
        self::assertEmpty($res->getServiceWebsiteDisruptions());
        self::assertEmpty($res->getOvPayAppDisruptions());
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

    public function registrationOpenDataProvider()
    {
        return [
            'bare-true' => ['data' => 'true', 'expected' => true],
            'true-in-array' => ['data' => '[true]', 'expected' => true],
            'bare-false' => ['data' => 'false', 'expected' => false],
            'false-in-array' => ['data' => '[false]', 'expected' => false],
            'random' => ['data' => 'random-content-in-here', 'expected' => false],
        ];
    }
}
