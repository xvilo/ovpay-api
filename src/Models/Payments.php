<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Models;

final class Payments
{
    /**
     * @param Payment[] $items
     */
    public function __construct(
        private readonly int $offset,
        private readonly bool $endOfListReached,
        private array $items
    ) {
    }

    public function addItem(Payment $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function isEndOfListReached(): bool
    {
        return $this->endOfListReached;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @param Payment[] $items
     */
    public function setItems(array $items): self
    {
        $this->items = $items;
        return $this;
    }
}
