<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\MockAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestAfterSendEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestEvents;
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

    public function testHandlesErrors()
    {
        $m = new MockAdapter();
        $m->setResponse(new Response(404));
        $request = new Request('GET', '/');
        $c = false;
        $l = function (RequestAfterSendEvent $e) use (&$c, &$l) {
            $c = true;
            $e->getDispatcher()->removeListener(RequestEvents::AFTER_SEND, $l);
            throw new RequestException('foo', $e->getRequest());
        };
        $request->getEventDispatcher()->addListener(RequestEvents::AFTER_SEND, $l);
        $request->getEventDispatcher()->addListener(RequestEvents::ERROR, function (RequestErrorEvent $e) {
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
        $l = function (RequestAfterSendEvent $e) use (&$l) {
            $e->getDispatcher()->removeListener(RequestEvents::AFTER_SEND, $l);
            throw new RequestException('foo', $e->getRequest());
        };
        $request->getEventDispatcher()->addListener(RequestEvents::AFTER_SEND, $l);
        $m->send(new Transaction(new Client(), $request));
    }
}
