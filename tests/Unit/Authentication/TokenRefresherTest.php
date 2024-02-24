<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Unit\Authentication;

use DateTimeImmutable;
use InvalidArgumentException;
use Lcobucci\JWT\Token\Plain;
use Xvilo\OVpayApi\Authentication\TokenRefresher;
use Xvilo\OVpayApi\Tests\Unit\TestCase;
use Lcobucci\JWT\Token as TokenInterface;
use Iterator;

final class TokenRefresherTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\DataProvider('inputDataProvider')]
    public function testTokenInterfaceCheck(mixed $input, bool $throws): void
    {
        if ($throws) {
            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage(sprintf('$token is not of type %s, got %s instead.', TokenInterface::class, get_debug_type($input)));
        }

        $item = (new TokenRefresher())->refresh($input);
        $this->assertNotEmpty($item);
    }

    public static function inputDataProvider(): Iterator
    {
        yield 'With token interface' => ['input' => new Plain(new TokenInterface\DataSet([], 'empty'), new TokenInterface\DataSet([], 'empty'), new TokenInterface\Signature('aa', 'bb')), 'throws' => false];
        yield 'With string' => ['input' => 'baz', 'throws' => true];
        yield 'With array' => ['input' => ['foo' => 'bar'], 'throws' => true];
        yield 'With null' => ['input' => null, 'throws' => true];
        yield 'With bool - false' => ['input' => false, 'throws' => true];
        yield 'With bool - true' => ['input' => true, 'throws' => true];
        yield 'With int' => ['input' => 123, 'throws' => true];
        yield 'With float' => ['input' => 0.123, 'throws' => true];
        yield 'With object' => ['input' => new DateTimeImmutable(), 'throws' => true];
    }
}
