<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\TruncatedBackoffStrategy;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\TruncatedBackoffStrategy
 */
class TruncatedBackoffStrategyTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testRetriesWhenLessThanMax()
    {
        $strategy = new TruncatedBackoffStrategy(2);
        $request = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request', array(), array(), '', false);
        $this->assertEquals(0, $strategy->getBackoffPeriod(0, $request));
        $this->assertEquals(0, $strategy->getBackoffPeriod(1, $request));
        $this->assertFalse($strategy->getBackoffPeriod(2, $request));
    }
}
