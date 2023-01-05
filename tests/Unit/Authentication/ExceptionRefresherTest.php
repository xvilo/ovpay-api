<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Unit\Authentication;

use RuntimeException;
use Xvilo\OVpayApi\Authentication\ExceptionRefresher;
use Xvilo\OVpayApi\Tests\Unit\TestCase;

final class ExceptionRefresherTest extends TestCase
{
    public function testThrowsException(): never
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Some test message');
        $this->expectExceptionCode(123);

        $refresher = new ExceptionRefresher('Some test message', 123);
        $refresher->refresh('foo');
    }
}
