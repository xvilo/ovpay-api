<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Models\Token;

final class TokenPersonalization
{
    public function __construct(
        private readonly string $name,
        private readonly string $color,
        private readonly string $medium
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getMedium(): string
    {
        return $this->medium;
    }
}
