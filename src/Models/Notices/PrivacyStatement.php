<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Models\Notices;

use DateTimeImmutable;

final class PrivacyStatement
{
    public function __construct(
        private readonly DateTimeImmutable $lastModified,
        private readonly array $highlights
    ) {
    }

    /**
     * @return DateTimeImmutable
     */
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
