<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Models;

use DateTimeImmutable;

final class Trip
{
    public function __construct(
        private readonly string $xbot,
        private readonly int $id,
        private readonly int $version,
        private readonly string $transport,
        private readonly string $status,
        private readonly string $checkInLocation,
        private readonly DateTimeImmutable $checkInTimestamp,
        private readonly ?string $checkOutLocation,
        private readonly ?DateTimeImmutable $checkOutTimestamp,
        private readonly string $currency,
        private readonly int $fare,
        private readonly string $organisationName,
        private readonly bool $loyaltyOrDiscount,
    ) {
    }

    public function getXbot(): string
    {
        return $this->xbot;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function getTransport(): string
    {
        return $this->transport;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCheckInLocation(): string
    {
        return $this->checkInLocation;
    }

    public function getCheckInTimestamp(): DateTimeImmutable
    {
        return $this->checkInTimestamp;
    }

    public function getCheckOutLocation(): ?string
    {
        return $this->checkOutLocation;
    }

    public function getCheckOutTimestamp(): ?DateTimeImmutable
    {
        return $this->checkOutTimestamp;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getFare(): int
    {
        return $this->fare;
    }

    public function getOrganisationName(): string
    {
        return $this->organisationName;
    }

    public function isLoyaltyOrDiscount(): bool
    {
        return $this->loyaltyOrDiscount;
    }
}
