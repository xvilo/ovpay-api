<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Unit\Authentication;

use Lcobucci\JWT\Token as TokenInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\RequestInterface;
use Xvilo\OVpayApi\Authentication\TokenMethod;
use Xvilo\OVpayApi\Tests\Unit\TestCase;

final class TokenMethodTest extends TestCase
{
    /**
     * @dataProvider tokenExpiredDataProvider
     */
    public function testIsExpired(bool $input, bool $expected): void
    {
        $token = $this->getTokenMock();
        $token->expects($this->once())
            ->method('isExpired')
            ->willReturn($input);

        $method = new TokenMethod($token);
        self::assertEquals($expected, $method->isExpired());
    }

    public function testGetToken(): void
    {
        $token = $this->getTokenMock();
        $method = new TokenMethod($token);
        self::assertEquals($token, $method->getToken());
    }

    public function testSetToken(): void
    {
        $token = $this->getTokenMock();
        $method = new TokenMethod($token);
        self::assertEquals($token, $method->getToken());
        $newToken = $this->getTokenMock();
        $method->setToken($newToken);
        self::assertInstanceOf(TokenInterface::class, $method->getToken());
        self::assertNotSame($token, $method->getToken());
        self::assertEquals($newToken, $method->getToken());
    }

    public function testUpdateRequest(): void
    {
        $request = $this->getMockBuilder(RequestInterface::class)
            ->setMethods(['withHeader', 'getRequestTarget', 'withRequestTarget', 'getMethod', 'withMethod', 'getUri', 'withUri', 'getProtocolVersion', 'withProtocolVersion', 'getHeaders', 'hasHeader', 'getHeader', 'getHeaderLine', 'withAddedHeader', 'withoutHeader', 'getBody', 'withBody'])
            ->getMock();

        $request->expects($this->once())
            ->method('withHeader')
            ->with($this->equalTo('Authorization'), $this->equalTo('Bearer TEST'))
            ->willReturn($request);

        $token = $this->getTokenMock();
        $token->expects($this->once())
            ->method('toString')
            ->willReturn('TEST');

        $method = new TokenMethod($token);
        $method->updateRequest($request);
    }

    public static function tokenExpiredDataProvider(): array
    {
        return [
            'true' => [true, true],
            'false' => [false, false],
        ];
    }

    private function getTokenMock(): MockObject|TokenInterface
    {
        return $this->getMockBuilder(TokenInterface::class)
            ->setMethods(['headers', 'isPermittedFor', 'isIdentifiedBy', 'isRelatedTo', 'hasBeenIssuedBy', 'hasBeenIssuedBefore', 'isMinimumTimeBefore', 'isExpired', 'toString'])
            ->getMock();
    }
}
