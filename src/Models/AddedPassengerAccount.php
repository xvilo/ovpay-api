<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Models;

final class AddedPassengerAccount
{
    public function __construct(
        private readonly string $paymentXbot
    ) {
    }

    public function getPaymentExternalBackOfficeToken(): string
    {
        return $this->paymentXbot;
    }
}
