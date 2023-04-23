<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Unit\Authentication;

use DateTimeImmutable;
use InvalidArgumentException;
use Lcobucci\JWT\Token\Plain;
use Xvilo\OVpayApi\Authentication\TokenRefresher;
use Xvilo\OVpayApi\Tests\Unit\TestCase;
use Lcobucci\JWT\Token as TokenInterface;

final class TokenRefresherTest extends TestCase
{
    /**
     * @dataProvider inputDataProvider
     */
    public function testTokenInterfaceCheck(mixed $input, bool $throws): void
    {
        if ($throws) {
            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage(sprintf('$token is not of type %s, got %s instead.', TokenInterface::class, get_debug_type($input)));
        }

        $item = (new TokenRefresher())->refresh($input);
        self::assertNotEmpty($item);
    }

    /**
     * @return array<string, array<string, array<string,string>|bool|DateTimeImmutable|float|int|Plain|string|null>>
     */
    public static function inputDataProvider(): array
    {
        return [
            'With token interface' => ['input' => new Plain(new TokenInterface\DataSet([], 'empty'), new TokenInterface\DataSet([], 'empty'), new TokenInterface\Signature('aa', 'bb')), 'throws' => false],
            'With string' => ['input' => 'baz', 'throws' => true],
            'With array' => ['input' => ['foo' => 'bar'], 'throws' => true],
            'With null' => ['input' => null, 'throws' => true],
            'With bool - false' => ['input' => false, 'throws' => true],
            'With bool - true' => ['input' => true, 'throws' => true],
            'With int' => ['input' => 123, 'throws' => true],
            'With float' => ['input' => 0.123, 'throws' => true],
            'With object' => ['input' => new DateTimeImmutable(), 'throws' => true],
        ];
    }
}
