<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Api;

use Xvilo\OVpayApi\Models\Receipt\ReceiptTrip;
use Xvilo\OVpayApi\Models\Trips;

final class TripsApi extends AbstractApi
{
    public function getTrips(string $cardXtatUuid, int $offset = 0): Trips
    {
        /** @var Trips $trips */
        $trips = $this->client->getSerializer()->deserialize(
            $this->get(sprintf('/api/v3/Trips/%s', $cardXtatUuid), ['offset' => $offset]),
            Trips::class,
            'json'
        );

        $trips->setItems($this->client->getSerializer()->deserialize(
            json_encode($trips->getItems(), JSON_THROW_ON_ERROR),
            Trips\TripsItem::class . '[]',
            'json'
        ));

        return $trips;
    }

    public function getTrip(string $tripXbotUuid, int $tripId): ReceiptTrip
    {
        return $this->client->getSerializer()->deserialize(
            $this->get(sprintf('/api/v3/Trips/%s/%d', $tripXbotUuid, $tripId)),
            ReceiptTrip::class,
            'json'
        );
    }
}
