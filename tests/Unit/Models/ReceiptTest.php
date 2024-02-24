<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Unit\Models;

use Ramsey\Uuid\Uuid;
use Xvilo\OVpayApi\Models\Payment;
use Xvilo\OVpayApi\Models\Receipt;
use Xvilo\OVpayApi\Models\Token;
use Xvilo\OVpayApi\Models\Trip;
use Xvilo\OVpayApi\Tests\Unit\TestCase;
use DateTimeImmutable;

final class ReceiptTest extends TestCase
{
    public function testAddRelatedPayment(): void
    {
        $receipt = new Receipt([], [], [], new Token('Emv', Uuid::uuid4()->toString(), 'Active', new Token\TokenPersonalization('Card', 'Pink', '')));
        $this->assertEmpty($receipt->getRelatedPayments());
        $this->assertEmpty($receipt->getRelatedBalances());
        $this->assertEmpty($receipt->getRelatedTrips());

        $paymentXbot = Uuid::uuid4()->toString();
        $dateTime = new DateTimeImmutable();
        $receipt->addRelatedPayment(new Payment(
            '1234',
            'COMPLETED',
            $dateTime,
            'type',
            130,
            0,
            'EUR',
            'Emv',
            null,
            false,
            'ABCDEFG1234567',
            $paymentXbot,
        ));
        $this->assertCount(1, $receipt->getRelatedPayments());
        $this->assertSame('ABCDEFG1234567', $receipt->getRelatedPayments()[0]->getServiceReferenceId());
        $this->assertSame($paymentXbot, $receipt->getRelatedPayments()[0]->getExternalBackOfficeToken());
        $this->assertSame('1234', $receipt->getRelatedPayments()[0]->getId());
        $this->assertSame('COMPLETED', $receipt->getRelatedPayments()[0]->getStatus());
        $this->assertEquals($dateTime, $receipt->getRelatedPayments()[0]->getTransactionTimestamp());
        $this->assertSame('type', $receipt->getRelatedPayments()[0]->getTransactionType());
        $this->assertSame(130, $receipt->getRelatedPayments()[0]->getAmount());
        $this->assertSame(0, $receipt->getRelatedPayments()[0]->getAmountDue());
        $this->assertSame('EUR', $receipt->getRelatedPayments()[0]->getCurrency());
        $this->assertSame('Emv', $receipt->getRelatedPayments()[0]->getPaymentMethod());
        $this->assertEquals(null, $receipt->getRelatedPayments()[0]->getRejectionReason());
        $this->assertEquals(false, $receipt->getRelatedPayments()[0]->isLoyaltyOrDiscount());
    }

    public function testAddRelatedPTrip(): void
    {
        $receipt = new Receipt([], [], [], new Token('Emv', Uuid::uuid4()->toString(), 'Active', new Token\TokenPersonalization('Card', 'Pink', '')));
        $this->assertEmpty($receipt->getRelatedPayments());
        $this->assertEmpty($receipt->getRelatedBalances());
        $this->assertEmpty($receipt->getRelatedTrips());

        $tripXbot = Uuid::uuid4()->toString();
        $receipt->addRelatedTrip(new Receipt\ReceiptTrip(
            null,
            new Trip(
                1234,
                1,
                'RAIL',
                'COMPLETE',
                'Utrecht CS',
                new DateTimeImmutable(),
                'Utrecht CS',
                new DateTimeImmutable(),
                'EUR',
                10,
                'NS',
                true,
                $tripXbot,
            ),
            null,
            null,
        ));

        $this->assertCount(1, $receipt->getRelatedTrips());
        $this->assertInstanceOf(Receipt\ReceiptTrip::class, $receipt->getRelatedTrips()[0]);
    }
}
