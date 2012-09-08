<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Backoff;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\ReasonPhraseBackoffStrategy;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\ReasonPhraseCodeBackoffStrategy
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Backoff\AbstractErrorCodeBackoffStrategy
 */
class ReasonPhraseBackoffStrategyTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testRetriesWhenCodeMatches()
    {
        $this->assertEmpty(ReasonPhraseBackoffStrategy::getDefaultFailureCodes());
        $strategy = new ReasonPhraseBackoffStrategy(array('Foo', 'Internal Server Error'));
        $request = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request', array(), array(), '', false);
        $response = new Response(200);
        $this->assertEquals(false, $strategy->getBackoffPeriod(0, $request, $response));
        $response->setStatus(200, 'Foo');
        $this->assertEquals(0, $strategy->getBackoffPeriod(0, $request, $response));
    }
}
