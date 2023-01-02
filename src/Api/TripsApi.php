<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Api;

final class TripsApi extends AbstractApi
{
    public function getTrips(string $userUuid, int $offset = 0): array
    {
        return $this->get(sprintf('/api/v3/Trips/%s', $userUuid), ['offset' => $offset]);
    }

    public function getTrip(string $tripXbotUuid, int $tripId): array
    {
        return $this->get(sprintf('/api/v3/Trips/%s/%d', $tripXbotUuid, $tripId));
    }
}
