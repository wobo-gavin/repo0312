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
        $this->assertEquals('before', $t->state);
    }
}
