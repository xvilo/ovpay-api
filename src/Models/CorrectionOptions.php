<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Models;

use DateTimeImmutable;
use Xvilo\OVpayApi\Models\CorrectionOptions\Stop;

final class CorrectionOptions
{
    public function __construct(
        private readonly string $correctableStatus,
        /** @var Stop[] $stops */
        private array $stops,
        private readonly bool $onboardValidation,
        private readonly DateTimeImmutable $correctionWindowStart,
        private readonly DateTimeImmutable $correctionWindowEnd
    ) {
    }

    public function getCorrectableStatus(): string
    {
        return $this->correctableStatus;
    }

    /** @return Stop[] */
    public function getStops(): array
    {
        return $this->stops;
    }

    public function addStop(Stop $stop): void
    {
        $this->stops[] = $stop;
    }

    public function isOnboardValidation(): bool
    {
        return $this->onboardValidation;
    }

    public function getCorrectionWindowStart(): DateTimeImmutable
    {
        return $this->correctionWindowStart;
    }

    public function getCorrectionWindowEnd(): DateTimeImmutable
    {
        return $this->correctionWindowEnd;
    }
}
