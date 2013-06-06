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
    public function testReturnsRequestcanCacheRequest()
    {
        $strategy = new DefaultCanCacheStrategy();
        $request = new Request('GET', 'http://foo.com');
        $this->assertTrue($strategy->canCacheRequest($request));
    }

    public function testDoesNotCacheNoStore()
    {
        $strategy = new DefaultCanCacheStrategy();
        $request = new Request('GET', 'http://foo.com', array('cache-control' => 'no-store'));
        $this->assertFalse($strategy->canCacheRequest($request));
    }

    public function testCanCacheResponse()
    {
        $response = $this->getMockBuilder('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response')
            ->setMethods(array('canCache'))
            ->setConstructorArgs(array(200))
            ->getMock();
        $response->expects($this->once())
            ->method('canCache')
            ->will($this->returnValue(true));
        $strategy = new DefaultCanCacheStrategy();
        $this->assertTrue($strategy->canCacheResponse($response));
    }
}
