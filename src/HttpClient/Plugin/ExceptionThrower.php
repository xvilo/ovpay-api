<?php

namespace Xvilo\OVpayApi\HttpClient\Plugin;

use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use JsonException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Xvilo\OVpayApi\Exception\ApiException;
use Xvilo\OVpayApi\Exception\ApiForbiddenException;
use Xvilo\OVpayApi\Exception\ApiResourceNotFound;
use Xvilo\OVpayApi\Exception\OVPayApiException;
use Xvilo\OVpayApi\Exception\UnauthorizedException;

final class ExceptionThrower implements Plugin
{
    /**
     * @throws OVPayApiException
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        return $next($request)->then(static function (ResponseInterface $response): ResponseInterface {
            if ($response->getStatusCode() < 400 || $response->getStatusCode() > 600) {
                return $response;
            }

            try {
                $content = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                $content = $response->getBody()->getContents();
            }

            if ($response->getStatusCode() === 401) {
                throw new UnauthorizedException('Unauthorized. Either no credentials where provided, or the credentials have expired.');
            }

            if ($response->getStatusCode() === 403) {
                if (isset($content['title']) || isset($content['detail'])) {
                    throw new ApiForbiddenException(sprintf('%s %s', $content['title'] ?? '', $content['detail'] ?? ''));
                }

                throw new ApiForbiddenException();
            }

            if (($content['status'] ?? null) === 404 && isset($content['title'])) {
                throw new ApiResourceNotFound($content['title']);
            }

            throw new ApiException('Something unexpected happend.', $response->getStatusCode());
        });
    }
}
