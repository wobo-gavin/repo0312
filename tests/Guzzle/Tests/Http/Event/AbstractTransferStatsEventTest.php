<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\AbstractTransferStatsEvent
 */
class AbstractTransferStatsEventTest extends \PHPUnit_Framework_TestCase
{
    public function testHasStats()
    {
        $s = ['foo' => 'bar'];
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $e = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\AbstractTransferStatsEvent')
            ->setConstructorArgs([$t, $s])
            ->getMockForAbstractClass();
        $this->assertNull($e->getTransferInfo('baz'));
        $this->assertEquals('bar', $e->getTransferInfo('foo'));
        $this->assertEquals($s, $e->getTransferInfo());
    }
}
