<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Plugin\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CallbackCacheKeyProvider;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\Cache\CallbackCacheKeyProvider
 */
class CallbackCacheKeyProviderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testConstructorEnsuresCallbackIsCallable()
    {
        $p = new CallbackCacheKeyProvider(new \stdClass());
    }

    public function testUsesCallback()
    {
        $p = new CallbackCacheKeyProvider(function ($request) { return 'foo'; });
        $this->assertEquals('foo', $p->getCacheKey(new Request('GET', 'http://www.foo.com')));
    }
}
