<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Api;

use Http\Client\Exception;
use JsonException;
use Xvilo\OVpayApi\Client;

abstract class AbstractApi
{
    public function __construct(
        protected Client $client
    ) {
    }

    /**
     * @param array<string, string|int> $parameters
     * @param array<string, string>     $requestHeaders
     *
     * @throws JsonException
     * @throws Exception
     */
    protected function get(string $path, array $parameters = [], array $requestHeaders = []): string
    {
        if ($parameters !== []) {
            $path .= '?' . http_build_query($parameters);
        }

        return $this->client->getHttpClient()
            ->get($path, $requestHeaders)
            ->getBody()
            ->getContents();
    }

    /**
     * Send a POST request with JSON-encoded parameters.
     *
     * @param string $path           request path
     * @param array  $parameters     POST parameters to be JSON encoded
     * @param array  $requestHeaders request headers
     *
     * @return array|string
     */
    protected function post(string $path, array $parameters = [], array $requestHeaders = [])
    {
        return $this->postRaw(
            $path,
            $this->createJsonBody($parameters),
            $requestHeaders
        );
    }

    /**
     * Send a POST request with raw data.
     *
     * @param string $path           request path
     * @param string $body           request body
     * @param array  $requestHeaders request headers
     */
    protected function postRaw(string $path, string $body, array $requestHeaders = []): string
    {
        return $this->client->getHttpClient()
            ->post($path, $requestHeaders, $body)
            ->getBody()
            ->getContents();
    }

    /**
     * Send a DELETE request with JSON-encoded parameters.
     *
     * @param string $path           request path
     * @param array  $parameters     POST parameters to be JSON encoded
     * @param array  $requestHeaders request headers
     */
    protected function delete(string $path, array $parameters = [], array $requestHeaders = []): string
    {
        return $this->client->getHttpClient()
            ->delete($path, $requestHeaders, $this->createJsonBody($parameters))
            ->getBody()
            ->getContents();
    }

    /**
     * Create a JSON encoded version of an array of parameters.
     *
     * @param array $parameters Request parameters
     */
    protected function createJsonBody(array $parameters): ?string
    {
        return ($parameters === []) ? null : json_encode($parameters, empty($parameters) ? JSON_FORCE_OBJECT : 0);
    }
}
