<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HeadersEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Response;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HeadersEvent
 */
class HeadersEventTest extends \PHPUnit_Framework_TestCase
{
    public function testHasValues()
    {
        $c = new Client();
        $r = new Request('GET', '/');
        $t = new Transaction($c, $r);
        $response = new Response(200);
        $t->setResponse($response);
        $e = new HeadersEvent($t);
        $this->assertSame($c, $e->getClient());
        $this->assertSame($r, $e->getRequest());
        $this->assertSame($response, $e->getResponse());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testEnsuresResponseIsSet()
    {
        $c = new Client();
        $r = new Request('GET', '/');
        $t = new Transaction($c, $r);
        new HeadersEvent($t);
    }
}
