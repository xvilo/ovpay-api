<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Api;

use Http\Client\Exception;
use JsonException;

final class AnonymousApi extends AbstractApi
{
    /**
     * @return array<string, array<string, mixed>>
     * @throws Exception
     * @throws JsonException
     */
    public function getNotices(): array
    {
        return $this->get('/api/anonymous/v1/Notices');
    }

    public function isRegistrationOpen(): bool
    {
        return $this->get('/api/anonymous/v1/PassengerAccounts/RegistrationOpen') === [true];
    }
}
