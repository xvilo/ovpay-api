<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Api;

use Xvilo\OVpayApi\Models\Token;

final class TokensApi extends AbstractApi
{
    /**
     * @return Token[]
     */
    public function getPaymentCards(): array
    {
        return $this->client->getSerializer()->deserialize(
            $this->get('/api/v1/Tokens/PaymentCards'),
            Token::class . '[]',
            'json'
        );
    }
}
