<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Models\Trips;

use DateTimeImmutable;
use Xvilo\OVpayApi\Models\Trip;

final class TripsItem
{
    public function __construct(
        private readonly Trip $trip,
        private readonly ?DateTimeImmutable $correctedFrom,
        private readonly mixed $correctedFromType,
    ) {
    }

    public function getTrip(): Trip
    {
        return $this->trip;
    }

    public function getCorrectedFrom(): ?DateTimeImmutable
    {
        return $this->correctedFrom;
    }

    public function getCorrectedFromType(): mixed
    {
        return $this->correctedFromType;
    }
}
