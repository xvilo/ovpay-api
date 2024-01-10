<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Api;

use Xvilo\OVpayApi\Models\Payments;
use Xvilo\OVpayApi\Models\Receipt;

final class PaymentApi extends AbstractApi
{
    public function getPayments(string $cardExternalTransitAccountToken): Payments
    {
        return $this->client->getSerializer()->deserialize(
            $this->get(sprintf('/api/v1/Payments/%s', $cardExternalTransitAccountToken), ['offset' => 0]),
            Payments::class,
            'json'
        );
    }

    public function getReceipt(string $paymentExternalBackOfficeToken, string $paymentId): Receipt
    {
        return $this->client->getSerializer()->deserialize(
            $this->get(sprintf('/api/v1/Payments/receipt/%s/%s', $paymentExternalBackOfficeToken, $paymentId)),
            Receipt::class,
            'json'
        );
    }
}
