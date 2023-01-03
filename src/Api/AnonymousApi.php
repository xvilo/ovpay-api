<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Api;

use Http\Client\Exception;
use JsonException;
use Xvilo\OVpayApi\Models\Notices;

final class AnonymousApi extends AbstractApi
{
    /**
     * @return array<string, array<string, mixed>>
     * @throws Exception
     * @throws JsonException
     */
    public function getNotices(): Notices
    {
        return $this->client->getSerializer()->deserialize(
            $this->get('/api/anonymous/v1/Notices'),
            Notices::class,
            'json'
        );
    }

    public function isRegistrationOpen(): bool
    {
        $dat = json_decode($this->get('/api/anonymous/v1/PassengerAccounts/RegistrationOpen'), true);
        return $dat === true || $dat === [true];
    }
}
