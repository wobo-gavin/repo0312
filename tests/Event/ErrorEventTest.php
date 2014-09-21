<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent
 */
class ErrorEventTest extends \PHPUnit_Framework_TestCase
{
    public function testInterceptsWithEvent()
    {
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $except = new RequestException('foo', $t->request);
        $t->exception = $except;
        $e = new ErrorEvent($t);
        $this->assertSame($e->getException(), $t->exception);
    }
}
