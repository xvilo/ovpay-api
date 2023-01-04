<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Authentication;

use InvalidArgumentException;
use Lcobucci\JWT\Token as TokenInterface;

final class TokenRefresher implements Refresher
{
    public function refresh(mixed $token): TokenInterface
    {
        if (!$token instanceof TokenInterface) {
            throw new InvalidArgumentException(sprintf('$token is not of type %s, got %s instead.', TokenInterface::class, get_debug_type($token)));
        }

        // TODO: Implement refresher logic

        return $token;
    }
}
