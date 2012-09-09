<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\CallbackBackoffStrategy;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\CallbackBackoffStrategy
 */
class CallbackBackoffStrategyTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testEnsuresIsCallable()
    {
        $strategy = new CallbackBackoffStrategy(new \stdClass());
    }

    public function testRetriesWithCallable()
    {
        $strategy = new CallbackBackoffStrategy(function () {
            return 10;
        });
        $request = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request', array(), array(), '', false);
        $this->assertEquals(10, $strategy->getBackoffPeriod(0, $request));
    }
}
