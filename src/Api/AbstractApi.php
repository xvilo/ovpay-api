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
     * @param array<string, string> $requestHeaders
     *
     * @throws JsonException
     * @throws Exception
     */
    protected function get(string $path, array $parameters = [], array $requestHeaders = []): string
    {
        if ($parameters !== []) {
            $path .= '?'.http_build_query($parameters);
        }

        return $this->client->getHttpClient()
            ->get($path, $requestHeaders)
            ->getBody()
            ->getContents();
    }
}
