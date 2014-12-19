<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\AbstractRetryableEvent
 */
class AbstractRetryableEventTest extends \PHPUnit_Framework_TestCase
{
    public function testCanRetry()
    {
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $t->transferInfo = ['foo' => 'bar'];
        $e = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\AbstractRetryableEvent')
            ->setConstructorArgs([$t])
            ->getMockForAbstractClass();
        $e->retry();
        $this->assertTrue($e->isPropagationStopped());
        $this->assertEquals('retry', $t->state);
    }

    public function testCanRetryAfterDelay()
    {
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $t->transferInfo = ['foo' => 'bar'];
        $e = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\AbstractRetryableEvent')
            ->setConstructorArgs([$t])
            ->getMockForAbstractClass();
        $e->retry(10);
        $this->assertTrue($e->isPropagationStopped());
        $this->assertEquals('retry', $t->state);
        $this->assertEquals(10, $t->request->getConfig()->get('delay'));
    }
}
