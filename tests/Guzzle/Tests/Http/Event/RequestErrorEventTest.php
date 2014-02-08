<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestEvents;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestErrorEvent
 */
class RequestErrorEventTest extends \PHPUnit_Framework_TestCase
{
    public function testInterceptsWithEvent()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = new Request('GET', '/');
        $response = new Response(404);
        $transaction = new Transaction($/* Replaced /* Replaced /* Replaced client */ */ */, $request);
        $except = new RequestException('foo', $request, $response);
        $event = new RequestErrorEvent($transaction, $except);

        $this->assertSame($except, $event->getException());
        $this->assertSame($response, $event->getResponse());
        $this->assertSame($request, $event->getRequest());

        $res = null;
        $request->getEmitter()->on(RequestEvents::AFTER_SEND, function ($e) use (&$res) {
            $res = $e;
        });

        $good = new Response(200);
        $event->intercept($good);
        $this->assertTrue($event->isPropagationStopped());
        $this->assertSame($res->getClient(), $event->getClient());
        $this->assertSame($good, $res->getResponse());
    }
}
