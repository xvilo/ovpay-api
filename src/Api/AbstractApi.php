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
     * @param array<string, string> $parameters
     * @param array<string, string> $requestHeaders
     *
     * @return array|string
     * @throws JsonException
     *
     * @throws Exception
     */
    protected function get(string $path, array $parameters = [], array $requestHeaders = [])
    {
        if ($parameters !== []) {
            $path .= '?'.http_build_query($parameters);
        }

        $response = $this->client->getHttpClient()->get($path, $requestHeaders);
        try {
            return (array) json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return $response->getBody()->getContents();
        }
    }
}
