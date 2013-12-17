<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestEvents;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestEventsTest
 */
class RequestEventsTest extends \PHPUnit_Framework_TestCase
{
    public function testEmitsAfterSendEvent()
    {
        $res = null;
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $t->setResponse(new Response(200));
        $t->getRequest()->getEventDispatcher()->addListener(RequestEvents::AFTER_SEND, function ($e) use (&$res) {
            $res = $e;
        });
        RequestEvents::emitAfterSendEvent($t);
        $this->assertSame($res->getClient(), $t->getClient());
        $this->assertSame($res->getRequest(), $t->getRequest());
        $this->assertEquals('/', $t->getResponse()->getEffectiveUrl());
    }

    public function testEmitsAfterSendEventAndEmitsErrorIfNeeded()
    {
        $ex2 = $res = null;
        $request = new Request('GET', '/');
        $t = new Transaction(new Client(), $request);
        $t->setResponse(new Response(200));
        $ex = new RequestException('foo', $request);
        $t->getRequest()->getEventDispatcher()->addListener(RequestEvents::AFTER_SEND, function ($e) use ($ex) {
            $ex->e = $e;
            throw $ex;
        });
        $t->getRequest()->getEventDispatcher()->addListener(RequestEvents::ERROR, function ($e) use (&$ex2) {
            $ex2 = $e->getException();
            $e->stopPropagation();
        });
        RequestEvents::emitAfterSendEvent($t);
        $this->assertSame($ex, $ex2);
    }
}
