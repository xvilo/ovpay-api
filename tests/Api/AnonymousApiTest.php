<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Api;

use Symfony\Component\HttpClient\HttplugClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Xvilo\OVpayApi\Authentication\HeaderMethod;
use Xvilo\OVpayApi\Tests\TestCase;

final class AnonymousApiTest extends TestCase
{
    public function testGetNotices(): void
    {
        $client = new HttplugClient(new MockHttpClient([
            new MockResponse($this->getNoticesJsonPayload())
        ]));
        $apiClient = $this->getApiClientWithHttpClient($client);
        self::assertEquals(json_decode($this->getNoticesJsonPayload(), true), $apiClient->anonymous()->getNotices());
    }

    /**
     * @dataProvider registrationOpenDataProvider
     */
    public function testIsRegistrationOpen(string $data, bool $expected): void
    {
        $client = new HttplugClient(new MockHttpClient([
            new MockResponse($data)
        ]));
        $apiClient = $this->getApiClientWithHttpClient($client);
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
