<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Models;

use Xvilo\OVpayApi\Models\Notices\PrivacyStatement;
use Xvilo\OVpayApi\Models\Notices\TermsAndConditions;

final class Notices
{
    public function __construct(
        private readonly array $serviceWebsiteDisruptions,
        private readonly array $ovPayAppDisruptions,
        private readonly TermsAndConditions $termsAndConditions,
        private readonly PrivacyStatement $privacyStatement
    ) {
    }

    /**
     * @return array<int|string, mixed>
     */
    public function getServiceWebsiteDisruptions(): array
    {
        return $this->serviceWebsiteDisruptions;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function getOvPayAppDisruptions(): array
    {
        return $this->ovPayAppDisruptions;
    }

    /**
     * @return TermsAndConditions
     */
    public function getTermsAndConditions(): TermsAndConditions
    {
        return $this->termsAndConditions;
    }

    /**
     * @return PrivacyStatement
     */
    public function getPrivacyStatement(): PrivacyStatement
    {
        return $this->privacyStatement;
    }
}
