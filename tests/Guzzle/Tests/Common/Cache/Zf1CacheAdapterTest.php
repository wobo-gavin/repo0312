<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\Zf1CacheAdapter;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\Zf1CacheAdapter
 */
class Zf1CacheAdapterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testAll()
    {
        $cache = new \Zend_Cache_Backend_Test();
        $adapter = new Zf1CacheAdapter($cache);
        $this->assertTrue($adapter->save('id', 'data'));
        $this->assertTrue($adapter->delete('id'));
        $this->assertEquals('foo', $adapter->fetch('id'));
        $this->assertEquals('123456', $adapter->contains('id'));
    }
}
