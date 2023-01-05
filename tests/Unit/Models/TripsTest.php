<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Unit\Models;

use Xvilo\OVpayApi\Models\Trip;
use Xvilo\OVpayApi\Models\Trips;
use Xvilo\OVpayApi\Models\Trips\TripsItem;
use Xvilo\OVpayApi\Tests\Unit\TestCase;
use DateTimeImmutable;

final class TripsTest extends TestCase
{
    public function testTripsNoItems(): void
    {
        $trips = new Trips(1, true, []);
        self::assertEquals([], $trips->getItems());
    }

    public function testTripsNoItemsAddItem(): void
    {
        $trips = new Trips(1, true, []);
        self::assertEquals([], $trips->getItems());
    }

    public function testTripsWithItem(): void
    {
        $item = new TripsItem(
            new Trip(
                '13fa524a-14e3-4fb4-ba28-cbc56bac45ea',
                1,
                1,
                'RAIL',
                'COMPLETE',
                'Utrecht CS',
                new DateTimeImmutable(),
                'Leiden CS',
                new DateTimeImmutable(),
                'EUR',
                0,
                'phpunit',
                false
            ),
            new DateTimeImmutable(), null
        );
        $trips = new Trips(1, true, [$item]);
        self::assertCount(1, $trips->getItems());
        self::assertEquals($item, $trips->getItems()[0]);
    }

    public function testTripsWithItemAddItem(): void
    {
        $item = new TripsItem(
            new Trip(
                '13fa524a-14e3-4fb4-ba28-cbc56bac45ea',
                1,
                1,
                'RAIL',
                'COMPLETE',
                'Utrecht CS',
                new DateTimeImmutable(),
                'Leiden CS',
                new DateTimeImmutable(),
                'EUR',
                0,
                'phpunit',
                false
            ),
            new DateTimeImmutable(), null
        );
        $trips = new Trips(1, true, [$item]);

        $itemTwo = new TripsItem(
            new Trip(
                '1234567-14e3-4fb4-ba28-cbc56bac45ea',
                2,
                1,
                'RAIL',
                'COMPLETE',
                'Utrecht CS',
                new DateTimeImmutable(),
                'Leiden CS',
                new DateTimeImmutable(),
                'EUR',
                0,
                'phpunit',
                false
            ),
            new DateTimeImmutable(), null
        );
        $trips->addItem($itemTwo);

        self::assertCount(2, $trips->getItems());
        self::assertEquals($item, $trips->getItems()[0]);
        self::assertEquals($itemTwo, $trips->getItems()[1]);
    }
}
