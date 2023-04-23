<?php

declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Unit\HttpClient\Plugin;

use Http\Client\Promise\HttpFulfilledPromise;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Xvilo\OVpayApi\Authentication\AuthMethod;
use Xvilo\OVpayApi\Authentication\Refresher;
use Xvilo\OVpayApi\HttpClient\Plugin\AuthMethodPlugin;
use Xvilo\OVpayApi\Tests\Unit\TestCase;

final class AuthMethodPluginTest extends TestCase
{
    public function testDoRefresh(): void
    {
        $response = new Response(500);
        $request = new Request('GET', 'https://api.ovpay.app/v1/');
        $promise = new HttpFulfilledPromise($response);

        $authMethod = $this->createMock(AuthMethod::class);

        $authMethod->expects($this->once())
            ->method('isExpired')
            ->willReturn(true);

        $authMethod->expects($this->once())
            ->method('setToken');

        $authMethod->expects($this->once())
            ->method('getToken');

        $authMethod->expects($this->once())
            ->method('updateRequest');

        $refresher = $this->createMock(Refresher::class);

        $refresher->expects($this->once())
            ->method('refresh');

        $plugin = new AuthMethodPlugin($authMethod, $refresher);
        $result = $plugin->handleRequest(
            $request,
            static fn (RequestInterface $request): HttpFulfilledPromise => $promise,
            static fn (RequestInterface $request): HttpFulfilledPromise => $promise
        );

        $result->wait();
    }
}
