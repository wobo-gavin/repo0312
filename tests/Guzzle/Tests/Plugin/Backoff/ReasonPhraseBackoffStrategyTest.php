<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\ReasonPhraseBackoffStrategy;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\ReasonPhraseBackoffStrategy
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\AbstractErrorCodeBackoffStrategy
 */
class ReasonPhraseBackoffStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testRetriesWhenCodeMatches()
    {
        $this->assertEmpty(ReasonPhraseBackoffStrategy::getDefaultFailureCodes());
        $strategy = new ReasonPhraseBackoffStrategy(array('Foo', 'Internal Server Error'));
        $this->assertTrue($strategy->makesDecision());
        $request = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request', array(), array(), '', false);
        $response = new Response(200);
        $this->assertEquals(false, $strategy->getBackoffPeriod(0, $request, $response));
        $response->setStatus(200, 'Foo');
        $this->assertEquals(0, $strategy->getBackoffPeriod(0, $request, $response));
    }

    public function testIgnoresNonErrors()
    {
        $strategy = new ReasonPhraseBackoffStrategy();
        $request = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request', array(), array(), '', false);
        $this->assertEquals(false, $strategy->getBackoffPeriod(0, $request));
    }
}
