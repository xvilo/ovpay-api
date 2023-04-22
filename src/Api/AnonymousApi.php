<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Api;

use Http\Client\Exception;
use JsonException;
use Xvilo\OVpayApi\Models\Notices;
use Xvilo\OVpayApi\Models\Receipt;

final class AnonymousApi extends AbstractApi
{
    /**
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
        $dat = json_decode($this->get('/api/anonymous/v1/PassengerAccounts/RegistrationOpen'), true, 512, JSON_THROW_ON_ERROR);

        return $dat === true || $dat === [true];
    }

    public function getReceipt(string $serviceReferenceId, int $amountInCents): Receipt
    {
        return $this->client->getSerializer()->deserialize(
            $this->get('/api/anonymous/v1/payments/receipt', [
                'serviceReferenceId' => $serviceReferenceId,
                'amountInCents' => $amountInCents
            ]),
            Receipt::class,
            'json'
        );
    }
}
