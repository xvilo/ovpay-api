<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Api;

use RuntimeException;

final class AnonymousApi extends AbstractApi
{
    public function getNotices(): array
    {
        return $this->get('/api/anonymous/v1/Notices');
    }

    public function isRegistrationOpen(): bool
    {
        return $this->get('/api/anonymous/v1/PassengerAccounts/RegistrationOpen') === [true];
    }
}
