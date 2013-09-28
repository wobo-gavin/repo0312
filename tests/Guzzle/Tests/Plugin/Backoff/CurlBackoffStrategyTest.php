<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\CurlBackoffStrategy;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\CurlException;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\CurlBackoffStrategy
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\AbstractErrorCodeBackoffStrategy
 */
class CurlBackoffStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testRetriesWithExponentialDelay()
    {
        $this->assertNotEmpty(CurlBackoffStrategy::getDefaultFailureCodes());
        $strategy = new CurlBackoffStrategy();
        $this->assertTrue($strategy->makesDecision());
        $request = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request', array(), array(), '', false);
        $e = new CurlException();
        $e->setError('foo', CURLE_BAD_CALLING_ORDER);
        $this->assertEquals(false, $strategy->getBackoffPeriod(0, $request, null, $e));

        foreach (CurlBackoffStrategy::getDefaultFailureCodes() as $code) {
            $this->assertEquals(0, $strategy->getBackoffPeriod(0, $request, null, $e->setError('foo', $code)));
        }
    }

    public function testIgnoresNonErrors()
    {
        $strategy = new CurlBackoffStrategy();
        $request = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request', array(), array(), '', false);
        $this->assertEquals(false, $strategy->getBackoffPeriod(0, $request, new Response(200)));
    }
}
