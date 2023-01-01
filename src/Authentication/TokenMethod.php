<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Authentication;

use Lcobucci\JWT\Token as TokenInterface;
use DateTimeImmutable;
use DateTimeInterface;
use Psr\Http\Message\RequestInterface;

final class TokenMethod implements AuthMethod
{
    public function __construct(
        private readonly TokenInterface $token
    ) {
    }

    public function isExpired(DateTimeInterface $now = new DateTimeImmutable()): bool
    {
        return $this->token->isExpired($now);
    }

    public function updateRequest(RequestInterface $request): RequestInterface
    {
        return $request->withHeader('Authorization', sprintf('Bearer %s', $this->token->toString()));
    }
}
