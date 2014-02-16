<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\MockAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\TransactionInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\CompleteEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\MockAdapter
 */
class MockAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function testYieldsMockResponse()
    {
        $response = new Response(200);
        $m = new MockAdapter();
        $m->setResponse($response);
        $this->assertSame($response, $m->send(new Transaction(new Client(), new Request('GET', '/'))));
    }

    public function testMocksWithCallable()
    {
        $response = new Response(200);
        $r = function (TransactionInterface $trans) use ($response) {
            return $response;
        };
        $m = new MockAdapter($r);
        $this->assertSame($response, $m->send(new Transaction(new Client(), new Request('GET', '/'))));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testValidatesResponses()
    {
        $m = new MockAdapter();
        $m->setResponse('foo');
        $m->send(new Transaction(new Client(), new Request('GET', '/')));
    }

    public function testHandlesErrors()
    {
        $m = new MockAdapter();
        $m->setResponse(new Response(404));
        $request = new Request('GET', '/');
        $c = false;
        $request->getEmitter()->once('complete', function (CompleteEvent $e) use (&$c) {
            $c = true;
            throw new RequestException('foo', $e->getRequest());
        });
        $request->getEmitter()->on('error', function (ErrorEvent $e) {
            $e->intercept(new Response(201));
        });
        $r = $m->send(new Transaction(new Client(), $request));
        $this->assertTrue($c);
        $this->assertEquals(201, $r->getStatusCode());
    }

    /**
     * @expectedException \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException
     */
    public function testThrowsUnhandledErrors()
    {
        $m = new MockAdapter();
        $m->setResponse(new Response(404));
        $request = new Request('GET', '/');
        $request->getEmitter()->once('complete', function (CompleteEvent $e) {
            throw new RequestException('foo', $e->getRequest());
        });
        $m->send(new Transaction(new Client(), $request));
    }
}
