<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\TruncatedBackoffStrategy;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\HttpBackoffStrategy;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\ConstantBackoffStrategy;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\TruncatedBackoffStrategy
 */
class TruncatedBackoffStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testRetriesWhenLessThanMax()
    {
        $strategy = new TruncatedBackoffStrategy(2);
        $this->assertTrue($strategy->makesDecision());
        $request = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request', array(), array(), '', false);
        $this->assertFalse($strategy->getBackoffPeriod(0, $request));
        $this->assertFalse($strategy->getBackoffPeriod(1, $request));
        $this->assertFalse($strategy->getBackoffPeriod(2, $request));

        $response = new Response(500);
        $strategy->setNext(new HttpBackoffStrategy(null, new ConstantBackoffStrategy(10)));
        $this->assertEquals(10, $strategy->getBackoffPeriod(0, $request, $response));
        $this->assertEquals(10, $strategy->getBackoffPeriod(1, $request, $response));
        $this->assertFalse($strategy->getBackoffPeriod(2, $request, $response));
    }
}
