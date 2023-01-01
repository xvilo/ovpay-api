<?php

namespace Xvilo\OVpayApi;

use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\AddPathPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use Http\Discovery\Psr17FactoryDiscovery;
use Xvilo\OVpayApi\HttpClient\HttpClientBuilder;

class Client
{
    public function __construct(
        private readonly HttpClientBuilder $httpClientBuilder = new HttpClientBuilder(),
        private readonly string $baseUri = 'https://api.ovpay.app/api',
    ) {
        $this->setupHttpBuilder();
    }

    private function setupHttpBuilder(): void
    {
        $uri = Psr17FactoryDiscovery::findUriFactory()->createUri($this->baseUri);

        $this->httpClientBuilder->addPlugin(new RedirectPlugin());
        $this->httpClientBuilder->addPlugin(new AddHostPlugin($uri));
        $this->httpClientBuilder->addPlugin(new AddPathPlugin($uri));
    }
}
