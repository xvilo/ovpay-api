<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Api;

use Xvilo\OVpayApi\Models\AddedPassengerAccount;

final class PassengerAccountsApi extends AbstractApi
{
    public function addByServiceReferenceId(string $serviceReferenceId, int $amountInCents): AddedPassengerAccount
    {
        if (str_starts_with($serviceReferenceId, 'NLOV')) {
            $serviceReferenceId = substr($serviceReferenceId, 4);
        }

        $uri = sprintf('/api/v1/PassengerAccounts?serviceReferenceId=%s&amountInCents=%s', $serviceReferenceId, $amountInCents);

        return $this->client->getSerializer()->deserialize(
            sprintf('{"paymentXbot": "%s"}', json_decode($this->postRaw($uri, ''), null, 512, JSON_THROW_ON_ERROR)),
            AddedPassengerAccount::class,
            'json'
        );
    }

    public function deletePassengerAccount(string $paymentCardXtat): bool
    {
        $this->delete(sprintf('/api/v1/PassengerAccounts/%s', $paymentCardXtat));

        return true;
    }
}
