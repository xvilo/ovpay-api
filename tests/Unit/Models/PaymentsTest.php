<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Unit\Models;

use Ramsey\Uuid\Uuid;
use Xvilo\OVpayApi\Models\Payment;
use Xvilo\OVpayApi\Models\Payments;
use Xvilo\OVpayApi\Tests\Unit\TestCase;
use DateTimeImmutable;

final class PaymentsTest extends TestCase
{
    public function testAddItem(): void
    {
        $payments = new Payments(
            30,
            true,
            []
        );

        $this->assertCount(0, $payments->getItems());

        $uuid = Uuid::uuid4()->toString();
        $time = new DateTimeImmutable();
        $payments->addItem(new Payment(
            '12345',
            'PAYED',
            $time,
            'type',
            1235,
            0,
            'EUR',
            'EMV',
            '',
            false,
            'ABCDEFG1234567',
            $uuid,
        ));
        $this->assertCount(1, $payments->getItems());
        $this->assertSame($uuid, $payments->getItems()[0]->getExternalBackOfficeToken());
        $this->assertEquals($time, $payments->getItems()[0]->getTransactionTimestamp());
    }
}
