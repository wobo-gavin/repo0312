<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\ClientCreateRequestEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\ClientCreateRequestEvent
 */
class ClientCreateRequestEventTest extends \PHPUnit_Framework_TestCase
{
    public function testHasValues()
    {
        $c = new Client();
        $r = new Request('GET', '/');
        $o = ['foo' => 'bar'];
        $e = new ClientCreateRequestEvent($c, $r, $o);
        $this->assertSame($c, $e->getClient());
        $this->assertSame($r, $e->getRequest());
        $this->assertSame($o, $e->getRequestOptions());
    }
}
