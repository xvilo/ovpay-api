<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Models;

use Xvilo\OVpayApi\Models\Trips\TripsItem;

final class Trips
{
    /**
     * @param TripsItem[] $items
     */
    public function __construct(
        private readonly int $offset,
        private readonly bool $endOfListReached,
        private array $items
    ) {
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function isEndOfListReached(): bool
    {
        return $this->endOfListReached;
    }

    /**
     * @return TripsItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(TripsItem $item): void
    {
        $this->items[] = $item;
    }
}
