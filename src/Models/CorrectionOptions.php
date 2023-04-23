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

    /**
     * @return string
     */
    public function getCorrectableStatus(): string
    {
        return $this->correctableStatus;
    }

    public function getStops(): array
    {
        return $this->stops;
    }

    public function addStop(Stop $stop): void
    {
        $this->stops[] = $stop;
    }

    /**
     * @return bool
     */
    public function isOnboardValidation(): bool
    {
        return $this->onboardValidation;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCorrectionWindowStart(): DateTimeImmutable
    {
        return $this->correctionWindowStart;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCorrectionWindowEnd(): DateTimeImmutable
    {
        return $this->correctionWindowEnd;
    }
}
