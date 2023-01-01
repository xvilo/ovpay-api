<?php

namespace Xvilo\OVpayApi;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\AddPathPlugin;
use Http\Client\Common\Plugin\HeaderAppendPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use Http\Discovery\Psr17FactoryDiscovery;
use Xvilo\OVpayApi\Api\AnonymousApi;
use Xvilo\OVpayApi\HttpClient\HttpClientBuilder;

class Client
{
    public const VERSION = '0.0.1';

    private readonly AnonymousApi $anonymous;

    public function __construct(
        private readonly HttpClientBuilder $httpClientBuilder = new HttpClientBuilder(),
        private readonly string $baseHost = 'https://api.ovpay.app',
        private ?string $userAgent = null
    ) {
        $this->setupHttpBuilder();
        $this->anonymous = new AnonymousApi($this);
    }

    public function anonymous(): AnonymousApi
    {
        return $this->anonymous;
    }

    public function getHttpClient(): HttpMethodsClient
    {
        return $this->getHttpClientBuilder()->getHttpClient();
    }

    protected function getHttpClientBuilder(): HttpClientBuilder
    {
        return $this->httpClientBuilder;
    }

    private function setupHttpBuilder(): void
    {
        $uri = Psr17FactoryDiscovery::findUriFactory()->createUri($this->baseHost);

        $this->httpClientBuilder->addPlugin(new RedirectPlugin());
        $this->httpClientBuilder->addPlugin(new AddHostPlugin($uri));
        $this->httpClientBuilder->addPlugin(new HeaderAppendPlugin([
            'Accept' => 'application/json',
            'Accept-Language' => 'nl-NL',
            'User-Agent' => $this->getUserAgent()
        ]));
    }

    private function getUserAgent(): string
    {
        if ($this->userAgent === null) {
            $this->userAgent = sprintf(
                'OVpayPHP/%s PHP/%s %s/%s',
                self::VERSION,
                PHP_VERSION,
                php_uname('s'),
                php_uname('r'),
            );
        }

        return $this->userAgent;
    }
}
