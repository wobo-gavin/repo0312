<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\ExponentialBackoffStrategy;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\ExponentialBackoffStrategy
 */
class ExponentialBackoffStrategyTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testRetriesWithExponentialDelay()
    {
        $strategy = new ExponentialBackoffStrategy();
        $request = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request', array(), array(), '', false);
        $this->assertEquals(1, $strategy->getBackoffPeriod(0, $request));
        $this->assertEquals(2, $strategy->getBackoffPeriod(1, $request));
        $this->assertEquals(4, $strategy->getBackoffPeriod(2, $request));
        $this->assertEquals(8, $strategy->getBackoffPeriod(3, $request));
        $this->assertEquals(16, $strategy->getBackoffPeriod(4, $request));
    }
}
