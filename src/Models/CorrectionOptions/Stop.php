<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Models\CorrectionOptions;

final class Stop
{
    public function __construct(
        private readonly string $privateCode,
        /** @var LocalizedName[] $localizedNames */
        private array $localizedNames,
    ) {
    }

    public function getPrivateCode(): string
    {
        return $this->privateCode;
    }


    /** @return LocalizedName[] */
    public function getLocalizedNames(): array
    {
        return $this->localizedNames;
    }

    public function addLocalizedName(LocalizedName $localizedName): void
    {
        $this->localizedNames[] = $localizedName;
    }
}
