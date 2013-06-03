<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\DoctrineCacheAdapter;
use Doctrine\Common\Cache\ArrayCache;
use Zend\Cache\StorageFactory;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterFactory
 */
class CacheAdapterFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /** @var ArrayCache */
    private $cache;

    /** @var DoctrineCacheAdapter */
    private $adapter;

    /**
     * Prepares the environment before running a test.
     */
    protected function setup()
    {
        parent::setUp();
        $this->cache = new ArrayCache();
        $this->adapter = new DoctrineCacheAdapter($this->cache);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEnsuresConfigIsObject()
    {
        CacheAdapterFactory::fromCache(array());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEnsuresKnownType()
    {
        CacheAdapterFactory::fromCache(new \stdClass());
    }

    public function cacheProvider()
    {
        return array(
            array(new DoctrineCacheAdapter(new ArrayCache()), '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\DoctrineCacheAdapter'),
            array(new ArrayCache(), '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\DoctrineCacheAdapter'),
            array(StorageFactory::factory(array('adapter' => 'memory')), '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\Zf2CacheAdapter'),
        );
    }

    /**
     * @dataProvider cacheProvider
     */
    public function testCreatesNullCacheAdapterByDefault($cache, $type)
    {
        $adapter = CacheAdapterFactory::fromCache($cache);
        $this->assertInstanceOf($type, $adapter);
    }
}
