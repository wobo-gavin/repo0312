<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent
 */
class ErrorEventTest extends \PHPUnit_Framework_TestCase
{
    public function testInterceptsWithEvent()
    {
        $/* Replaced /* Replaced /* Replaced client */ */ */ = new Client();
        $request = new Request('GET', '/');
        $response = new Response(404);
        $transaction = new Transaction($/* Replaced /* Replaced /* Replaced client */ */ */, $request);
        $except = new RequestException('foo', $request, $response);
        $event = new ErrorEvent($transaction, $except);

        $this->assertSame($except, $event->getException());
        $this->assertSame($response, $event->getResponse());
        $this->assertSame($request, $event->getRequest());

        $res = null;
        $request->getEmitter()->on('complete', function ($e) use (&$res) {
            $res = $e;
        });

        $good = new Response(200);
        $event->intercept($good);
        $this->assertTrue($event->isPropagationStopped());
        $this->assertSame($res->getClient(), $event->getClient());
        $this->assertSame($good, $res->getResponse());
    }
}