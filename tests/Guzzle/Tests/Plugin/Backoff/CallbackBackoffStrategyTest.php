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
        $strategy = new CallbackBackoffStrategy(new \stdClass(), true);
    }

    public function testRetriesWithCallable()
    {
        $request = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request', array(), array(), '', false);
        $strategy = new CallbackBackoffStrategy(function () { return 10; }, true);
        $this->assertTrue($strategy->makesDecision());
        $this->assertEquals(10, $strategy->getBackoffPeriod(0, $request));
        // Ensure it chains correctly when null is returned
        $strategy = new CallbackBackoffStrategy(function () { return null; }, false);
        $this->assertFalse($strategy->makesDecision());
        $this->assertFalse($strategy->getBackoffPeriod(0, $request));
    }
}
