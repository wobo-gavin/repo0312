<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\ConstantBackoffStrategy;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\ConstantBackoffStrategy
 */
class ConstantBackoffStrategyTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testRetriesWithConstantDelay()
    {
        $strategy = new ConstantBackoffStrategy(3.5);
        $request = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request', array(), array(), '', false);
        $this->assertEquals(3.5, $strategy->getBackoffPeriod(0, $request));
        $this->assertEquals(3.5, $strategy->getBackoffPeriod(1, $request));
    }
}
