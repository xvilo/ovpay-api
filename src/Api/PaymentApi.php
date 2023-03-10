<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Api;

use Xvilo\OVpayApi\Models\Payments;
use Xvilo\OVpayApi\Models\Receipt;

final class PaymentApi extends AbstractApi
{
    public function getPayments(string $cardXtatUuid): Payments
    {
        return $this->client->getSerializer()->deserialize(
            $this->get(sprintf('/api/v1/Payments/%s', $cardXtatUuid), ['offset' => 0]),
            Payments::class,
            'json'
        );
    }

    public function getReceipt(string $paymentXbot, string $paymentId): Receipt
    {
        return $this->client->getSerializer()->deserialize(
            $this->get(sprintf('/api/v1/Payments/receipt/%s/%s', $paymentXbot, $paymentId)),
            Receipt::class,
            'json'
        );
    }
}
