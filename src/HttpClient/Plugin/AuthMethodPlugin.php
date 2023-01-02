<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use RuntimeException;
use Xvilo\OVpayApi\Authentication\AuthMethod;
use Xvilo\OVpayApi\Authentication\ExceptionRefresher;
use Xvilo\OVpayApi\Authentication\Refresher;

final class AuthMethodPlugin implements Plugin
{
    public function __construct(
        private readonly AuthMethod $authMethod,
        private readonly Refresher $refresher = new ExceptionRefresher('Token Expired. Token refresh not yet implemented.')
    ) {
    }

    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        if ($this->authMethod->isExpired()) {
            $this->authMethod->setToken(
                $this->refresher->refresh($this->authMethod->getToken())
            );
        }

        return $next($this->authMethod->updateRequest($request));
    }
}
