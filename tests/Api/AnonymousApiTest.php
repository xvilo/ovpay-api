<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Api;

use Symfony\Component\HttpClient\Response\MockResponse;
use Xvilo\OVpayApi\Tests\TestCase;

final class AnonymousApiTest extends TestCase
{
    public function testGetNotices(): void
    {
        $apiClient = $this->getApiClientWithHttpClient(
            $this->getMockHttpClient(new MockResponse($this->getNoticesJsonPayload()))
        );
        self::assertEquals(json_decode($this->getNoticesJsonPayload(), true), $apiClient->anonymous()->getNotices());
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
