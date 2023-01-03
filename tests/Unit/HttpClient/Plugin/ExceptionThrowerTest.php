<?php
declare(strict_types=1);

namespace Xvilo\OVpayApi\Tests\Unit\HttpClient\Plugin;

use Http\Client\Promise\HttpFulfilledPromise;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Xvilo\OVpayApi\Exception\OVPayApiException;
use Xvilo\OVpayApi\HttpClient\Plugin\ExceptionThrower;
use Xvilo\OVpayApi\Tests\Unit\TestCase;

final class ExceptionThrowerTest extends TestCase
{
    public function testGeneric500Error(): void
    {
        $this->expectException(OVPayApiException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage('Something unexpected happend.');

        $response = new Response(500);
        $request = new Request('GET', 'https://api.ovpay.app/v1/');
        $promise = new HttpFulfilledPromise($response);

        $plugin = new ExceptionThrower();
        $result = $plugin->handleRequest(
            $request,
            function (RequestInterface $request) use ($promise) {
                return $promise;
            },
            function (RequestInterface $request) use ($promise) {
                return $promise;
            }
        );

        $result->wait();
    }
}
