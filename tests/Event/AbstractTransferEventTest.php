<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Tests\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\Request;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\AbstractTransferEvent
 */
class AbstractTransferEventTest extends \PHPUnit_Framework_TestCase
{
    public function testHasStats()
    {
        $s = ['foo' => 'bar'];
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $e = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\AbstractTransferEvent')
            ->setConstructorArgs([$t, $s])
            ->getMockForAbstractClass();
        $this->assertNull($e->getTransferInfo('baz'));
        $this->assertEquals('bar', $e->getTransferInfo('foo'));
        $this->assertEquals($s, $e->getTransferInfo());
    }
}
