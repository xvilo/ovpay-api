<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Authentication;

use InvalidArgumentException;
use Lcobucci\JWT\Token as TokenInterface;
use DateTimeImmutable;
use DateTimeInterface;
use Psr\Http\Message\RequestInterface;

final class HeaderMethod implements AuthMethod
{
    public function __construct(
        private readonly string $header,
        private string $value,
    ) {
    }

    public function isExpired(DateTimeInterface $now = new DateTimeImmutable()): bool
    {
        return false;
    }

    public function updateRequest(RequestInterface $request): RequestInterface
    {
        return $request->withHeader($this->header, $this->value);
    }

    public function getToken(): mixed
    {
        return $this->value;
    }

    public function setToken(mixed $token): self
    {
        if (!is_string($token)) {
            throw new InvalidArgumentException('Can only set string on ' . self::class);
        }

        $this->value = $token;
        return $this;
    }
}
