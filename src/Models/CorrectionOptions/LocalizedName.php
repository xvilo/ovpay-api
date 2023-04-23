<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Models\CorrectionOptions;

final class LocalizedName
{
    public function __construct(
        private readonly string $language,
        private readonly string $text,
    ) {
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getLanguage(): string
    {
        return $this->language;
    }
}
