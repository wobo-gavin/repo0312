<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Transaction;
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
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $t->exception = new \Exception('foo');
        $e = new BeforeEvent($t);
        $response = new Response(200);
        $e->intercept($response);
        $this->assertTrue($e->isPropagationStopped());
        $this->assertSame($t->response, $response);
        $this->assertNull($t->exception);
    }
}
