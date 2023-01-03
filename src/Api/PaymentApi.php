<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Api;

final class PaymentApi extends AbstractApi
{
    public function getPayments(string $cardXtatUuid): array
    {
        return $this->get(sprintf('/api/v1/Payments/%s', $cardXtatUuid), ['offset' => 0]);
    }

    public function getReceipt(string $paymentXbot, string $paymentId): array
    {
        return $this->get(sprintf('/api/v1/Payments/receipt/%s/%s', $paymentXbot, $paymentId));
    }
}
