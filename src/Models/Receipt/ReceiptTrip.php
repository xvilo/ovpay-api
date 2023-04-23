<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Models\Receipt;

use Xvilo\OVpayApi\Models\CorrectionOptions;
use Xvilo\OVpayApi\Models\Trip;

final class ReceiptTrip
{
    public function __construct(
        private readonly ?CorrectionOptions $correctionOptions,
        private readonly Trip $trip,
        private readonly mixed $correctedFrom,
        private readonly mixed $correctedFromType,
    ) {
    }

    public function getCorrectionOptions(): ?CorrectionOptions
    {
        return $this->correctionOptions;
    }

    public function getTrip(): Trip
    {
        return $this->trip;
    }

    public function getCorrectedFrom(): mixed
    {
        return $this->correctedFrom;
    }

    public function getCorrectedFromType(): mixed
    {
        return $this->correctedFromType;
    }
}
