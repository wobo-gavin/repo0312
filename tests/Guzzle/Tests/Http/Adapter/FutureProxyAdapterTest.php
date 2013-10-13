<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\FutureProxyAdapter;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\FutureProxyAdapter
 */
class FutureProxyAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testSendsWithDefaultAdapterIfNoFutureAttribute()
    {
        $response = new Response(200);
        $mock = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\AdapterInterface')
            ->setMethods(['send'])
            ->getMockForAbstractClass();
        $mock->expects($this->once())
            ->method('send')
            ->will($this->returnValue($response));

        $f = new FutureProxyAdapter($mock);
        $this->assertSame($response, $f->send(new Transaction(new Client(), new Request('GET', '/'))));
    }

    public function testInterceptsWithFutureAdapter()
    {
        $mock = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\AdapterInterface')
            ->setMethods(['send'])
            ->getMockForAbstractClass();
        $mock->expects($this->never())
            ->method('send');

        $f = new FutureProxyAdapter($mock);
        $request = new Request('GET', '/');
        $request->getConfig()->set('future', true);
        $response = $f->send(new Transaction(new Client(), $request));
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\FutureResponseInterface', $response);
        $this->assertSame($mock, $response->getAdapter());
        $this->assertSame($request, $response->getTransaction()->getRequest());
    }
}
