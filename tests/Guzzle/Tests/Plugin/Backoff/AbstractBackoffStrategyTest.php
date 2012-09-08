<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\AbstractBackoffStrategy
 */
class AbstractBackoffStrategyTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    protected function getMockStrategy()
    {
        return $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\AbstractBackoffStrategy')
            ->setMethods(array('getDelay'))
            ->getMockForAbstractClass();
    }

    public function testReturnsZeroWhenNoNextAndGotNul()
    {
        $request = new Request('GET', 'http://www.foo.com');
        $mock = $this->getMockStrategy();
        $mock->expects($this->atLeastOnce())->method('getDelay')->will($this->returnValue(null));
        $this->assertEquals(0, $mock->getBackoffPeriod(0, $request));
    }

    public function testReturnsFalse()
    {
        $request = new Request('GET', 'http://www.foo.com');
        $mock = $this->getMockStrategy();
        $mock->expects($this->atLeastOnce())->method('getDelay')->will($this->returnValue(false));
        $this->assertEquals(false, $mock->getBackoffPeriod(0, $request));
    }

    public function testReturnsNextValueWhenNullOrTrue()
    {
        $request = new Request('GET', 'http://www.foo.com');
        $mock = $this->getMockStrategy();
        $mock->expects($this->atLeastOnce())->method('getDelay')->will($this->returnValue(null));
        $mock2 = $this->getMockStrategy();
        $mock2->expects($this->atLeastOnce())->method('getDelay')->will($this->returnValue(10));
        $mock->setNext($mock2);
        $this->assertEquals(10, $mock->getBackoffPeriod(0, $request));
    }
}
