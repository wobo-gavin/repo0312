<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestBeforeSendEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestEvents;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestBeforeSendEvent
 */
class RequestBeforeSendEventTest extends \PHPUnit_Framework_TestCase
{
    public function testInterceptsWithEvent()
    {
        $response = new Response(200);
        $res = null;
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $t->getRequest()->getEventDispatcher()->addListener(RequestEvents::AFTER_SEND, function ($e) use (&$res) {
            $res = $e;
        });
        $e = new RequestBeforeSendEvent($t);
        $e->intercept($response);
        $this->assertTrue($e->isPropagationStopped());
        $this->assertSame($res->getClient(), $e->getClient());
    }
}
