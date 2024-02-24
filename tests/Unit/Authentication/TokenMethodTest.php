<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Unit\Authentication;

use Lcobucci\JWT\Token as TokenInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\RequestInterface;
use Xvilo\OVpayApi\Authentication\TokenMethod;
use Xvilo\OVpayApi\Tests\Unit\TestCase;
use Iterator;

final class TokenMethodTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('tokenExpiredDataProvider')]
    public function testIsExpired(bool $input, bool $expected): void
    {
        $token = $this->getTokenMock();
        $token->expects($this->once())
            ->method('isExpired')
            ->willReturn($input);

        $method = new TokenMethod($token);
        $this->assertEquals($expected, $method->isExpired());
    }

    public function testGetToken(): void
    {
        $token = $this->getTokenMock();
        $method = new TokenMethod($token);
        $this->assertEquals($token, $method->getToken());
    }

    public function testSetToken(): void
    {
        $token = $this->getTokenMock();
        $method = new TokenMethod($token);
        $this->assertEquals($token, $method->getToken());
        $newToken = $this->getTokenMock();
        $method->setToken($newToken);
        $this->assertInstanceOf(TokenInterface::class, $method->getToken());
        $this->assertNotSame($token, $method->getToken());
        $this->assertEquals($newToken, $method->getToken());
    }

    public function testUpdateRequest(): void
    {
        $request = $this->createMock(RequestInterface::class);

        $request->expects($this->once())
            ->method('withHeader')
            ->with('Authorization', 'Bearer TEST')
            ->willReturn($request);

        $token = $this->getTokenMock();
        $token->expects($this->once())
            ->method('toString')
            ->willReturn('TEST');

        $method = new TokenMethod($token);
        $method->updateRequest($request);
    }

    public static function tokenExpiredDataProvider(): Iterator
    {
        yield 'true' => [true, true];
        yield 'false' => [false, false];
    }

    private function getTokenMock(): MockObject|TokenInterface
    {
        return $this->createMock(TokenInterface::class);
    }
}
