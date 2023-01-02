<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Api;

final class TokensApi extends AbstractApi
{
    public function getPaymentCards(): array
    {
        return $this->get('/api/v1/Tokens/PaymentCards');
    }
}
