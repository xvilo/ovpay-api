<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\HeaderAppendPlugin;
use Http\Client\Common\Plugin\RedirectPlugin;
use Http\Discovery\Psr17FactoryDiscovery;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Xvilo\OVpayApi\Api\AnonymousApi;
use Xvilo\OVpayApi\Api\PaymentApi;
use Xvilo\OVpayApi\Api\TokensApi;
use Xvilo\OVpayApi\Api\TripsApi;
use Xvilo\OVpayApi\Authentication\AuthMethod;
use Xvilo\OVpayApi\HttpClient\HttpClientBuilder;
use Xvilo\OVpayApi\HttpClient\Plugin\AuthMethodPlugin;
use Xvilo\OVpayApi\HttpClient\Plugin\ExceptionThrower;
use Xvilo\OVpayApi\Tests\Functional\Api\PaymentApiTest;

class Client
{
    /**
     * @var string
     */
    final public const VERSION = '0.3.0';

    private readonly AnonymousApi $anonymous;

    private readonly TripsApi $trips;

    private readonly TokensApi $tokens;

    private readonly PaymentApi $payment;

    public function __construct(
        private readonly HttpClientBuilder $httpClientBuilder = new HttpClientBuilder(),
        private readonly string $baseHost = 'https://api.ovpay.app',
        private ?string $userAgent = null,
        private readonly SerializerInterface $serializer = new Serializer(
            [new ArrayDenormalizer(), new DateTimeNormalizer(), new ObjectNormalizer(propertyTypeExtractor: new ReflectionExtractor())],
            [new JsonEncoder()]
        )
    ) {
        $this->setupHttpBuilder();
        $this->anonymous = new AnonymousApi($this);
        $this->trips = new TripsApi($this);
        $this->tokens = new TokensApi($this);
        $this->payment = new PaymentApi($this);
    }

    public function anonymous(): AnonymousApi
    {
        return $this->anonymous;
    }

    public function trips(): TripsApi
    {
        return $this->trips;
    }

    public function tokens(): TokensApi
    {
        return $this->tokens;
    }

    public function payment(): PaymentApi
    {
        return $this->payment;
    }

    public function Authenticate(AuthMethod $method): self
    {
        $this->getHttpClientBuilder()
            ->removePlugin(AuthMethodPlugin::class)
            ->addPlugin(new AuthMethodPlugin($method));

        return $this;
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

        $this->httpClientBuilder
            ->addPlugin(new RedirectPlugin())
            ->addPlugin(new AddHostPlugin($uri))
            ->addPlugin(new ExceptionThrower())
            ->addPlugin(new HeaderAppendPlugin([
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

    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }
}
