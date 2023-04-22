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
        self::assertEmpty($receipt->getRelatedPayments());
        self::assertEmpty($receipt->getRelatedBalances());
        self::assertEmpty($receipt->getRelatedTrips());

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
        self::assertCount(1, $receipt->getRelatedPayments());
        self::assertEquals('ABCDEFG1234567', $receipt->getRelatedPayments()[0]->getServiceReferenceId());
        self::assertEquals($paymentXbot, $receipt->getRelatedPayments()[0]->getXbot());
        self::assertEquals('1234', $receipt->getRelatedPayments()[0]->getId());
        self::assertEquals('COMPLETED', $receipt->getRelatedPayments()[0]->getStatus());
        self::assertEquals($dateTime, $receipt->getRelatedPayments()[0]->getTransactionTimestamp());
        self::assertEquals('type', $receipt->getRelatedPayments()[0]->getTransactionType());
        self::assertEquals(130, $receipt->getRelatedPayments()[0]->getAmount());
        self::assertEquals(0, $receipt->getRelatedPayments()[0]->getAmountDue());
        self::assertEquals('EUR', $receipt->getRelatedPayments()[0]->getCurrency());
        self::assertEquals('Emv', $receipt->getRelatedPayments()[0]->getPaymentMethod());
        self::assertEquals(null, $receipt->getRelatedPayments()[0]->getRejectionReason());
        self::assertEquals(false, $receipt->getRelatedPayments()[0]->isLoyaltyOrDiscount());
    }

    public function testAddRelatedPTrip(): void
    {
        $receipt = new Receipt([], [], [], new Token('Emv', Uuid::uuid4()->toString(), 'Active', new Token\TokenPersonalization('Card', 'Pink', '')));
        self::assertEmpty($receipt->getRelatedPayments());
        self::assertEmpty($receipt->getRelatedBalances());
        self::assertEmpty($receipt->getRelatedTrips());

        $tripXbot = Uuid::uuid4()->toString();
        $receipt->addRelatedTrip(new Receipt\ReceiptTrip(
            [],
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

        self::assertCount(1, $receipt->getRelatedTrips());
        self::assertInstanceOf(Receipt\ReceiptTrip::class, $receipt->getRelatedTrips()[0]);
    }
}
