<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\LinearBackoffStrategy;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\LinearBackoffStrategy
 */
class LinearBackoffStrategyTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testRetriesWithLinearDelay()
    {
        $strategy = new LinearBackoffStrategy(5);
        $this->assertFalse($strategy->makesDecision());
        $request = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request', array(), array(), '', false);
        $this->assertEquals(0, $strategy->getBackoffPeriod(0, $request));
        $this->assertEquals(5, $strategy->getBackoffPeriod(1, $request));
        $this->assertEquals(10, $strategy->getBackoffPeriod(2, $request));
    }
}
