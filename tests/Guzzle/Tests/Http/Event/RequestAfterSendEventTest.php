<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestAfterSendEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestAfterSendEvent
 */
class RequestAfterSendEventTest extends \PHPUnit_Framework_TestCase
{
    public function testHasValues()
    {
        $c = new Client();
        $r = new Request('GET', '/');
        $res = new Response(200);
        $t = new Transaction($c, $r);
        $e = new RequestAfterSendEvent($t);
        $e->intercept($res);
        $this->assertTrue($e->isPropagationStopped());
        $this->assertSame($res, $e->getResponse());
    }
}
