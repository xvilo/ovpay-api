<?php

namespace Xvilo\OVpayApi\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Xvilo\OVpayApi\Exception\OVPayApiException;
use Xvilo\OVpayApi\Exception\UnauthorizedException;

final class ExceptionThrower implements Plugin
{
    /**
     * @throws OVPayApiException
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        return $next($request)->then(function (ResponseInterface $response) use ($request) {
            if ($response->getStatusCode() < 400 || $response->getStatusCode() > 600) {
                return $response;
            }

            if ($response->getStatusCode() === 401) {
                throw new UnauthorizedException();
            }

            throw new RuntimeException('Something unexpected happend.', $response->getStatusCode());
        });
    }
}
