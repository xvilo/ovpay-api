<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use RuntimeException;
use Xvilo\OVpayApi\Authentication\AuthMethod;

final class AuthMethodPlugin implements Plugin
{
    public function __construct(
        private readonly AuthMethod $authMethod
    ) {
    }

    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        if ($this->authMethod->isExpired()) {
            throw new RuntimeException('Token Expired. Token refresh not yet implemented.');
        }

        return $next($this->authMethod->updateRequest($request));
    }
}
