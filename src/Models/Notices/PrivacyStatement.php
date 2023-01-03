<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Models\Notices;

use DateTimeImmutable;

final class PrivacyStatement
{
    /**
     * @param array<int|string, mixed> $highlights
     */
    public function __construct(
        private readonly DateTimeImmutable $lastModified,
        private readonly array $highlights
    ) {
    }

    public function getLastModified(): DateTimeImmutable
    {
        return $this->lastModified;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function getHighlights(): array
    {
        return $this->highlights;
    }
}
