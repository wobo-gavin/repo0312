<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\DefaultCanCacheStrategy;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\DefaultCanCacheStrategy
 */
class DefaultCanCacheStrategyTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testReturnsRequestCanCache()
    {
        $strategy = new DefaultCanCacheStrategy();
        $response = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request')
            ->disableOriginalConstructor()
            ->setMethods(array('canCache'))
            ->getMock();

        $response->expects($this->once())
            ->method('canCache')
            ->will($this->returnValue(true));

        $this->assertTrue($strategy->canCache($response));
    }
}
