<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\NullCacheAdapter;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\NullCacheAdapter
 */
class NullCacheAdapterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testNullCacheAdapter()
    {
        $c = new NullCacheAdapter();
        $this->assertEquals(false, $c->contains('foo'));
        $this->assertEquals(true, $c->delete('foo'));
        $this->assertEquals(false, $c->fetch('foo'));
        $this->assertEquals(true, $c->save('foo', 'bar'));
    }
}
