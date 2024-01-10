<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Models;

use Xvilo\OVpayApi\Models\Token\TokenPersonalization;

final class Token
{
    public function __construct(
        private readonly string $mediumType,
        private readonly string $xbot,
        private readonly string $status,
        private readonly TokenPersonalization $personalization,
        private readonly ?string $externalTransitAccountToken = null,
    ) {
    }

    public function getMediumType(): string
    {
        return $this->mediumType;
    }

    public function getXbot(): string
    {
        return $this->xbot;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPersonalization(): TokenPersonalization
    {
        return $this->personalization;
    }

    public function getExternalTransitAccountToken(): ?string
    {
        return $this->externalTransitAccountToken;
    }
}
