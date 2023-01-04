<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Authentication;

use DateTimeImmutable;
use DateTimeInterface;
use Psr\Http\Message\RequestInterface;

interface AuthMethod
{
    public function isExpired(DateTimeInterface $now = new DateTimeImmutable()): bool;

    public function updateRequest(RequestInterface $request): RequestInterface;

    public function getToken(): mixed;

    public function setToken(mixed $token): self;
}
