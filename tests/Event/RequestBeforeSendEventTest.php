<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\BeforeEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\BeforeEvent
 */
class BeforeEventTest extends \PHPUnit_Framework_TestCase
{
    public function testInterceptsWithEvent()
    {
        $response = new Response(200);
        $res = null;
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $t->getRequest()->getEmitter()->on('complete', function ($e) use (&$res) {
            $res = $e;
        });
        $e = new BeforeEvent($t);
        $e->intercept($response);
        $this->assertTrue($e->isPropagationStopped());
        $this->assertSame($res->getClient(), $e->getClient());
    }
}
