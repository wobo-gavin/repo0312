<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter;
use Doctrine\Common\Cache\ArrayCache;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter
 */
class CacheAdapterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var ArrayCache
     */
    private $cache;

    /**
     * @var DoctrineCacheAdapter
     */
    private $adapter;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->cache = new ArrayCache();
        $this->adapter = new DoctrineCacheAdapter($this->cache);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->adapter = null;
        $this->cache = null;
        parent::tearDown();
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\AbstractCacheAdapter::getCacheObject
     */
    public function testGetCacheObject()
    {
        $this->assertEquals($this->cache, $this->adapter->getCacheObject());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter::save
     */
    public function testSave()
    {
        $this->assertTrue($this->adapter->save('test', 'data', 1000));
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter::fetch
     */
    public function testFetch()
    {
        $this->assertTrue($this->adapter->save('test', 'data', 1000));
        $this->assertEquals('data', $this->adapter->fetch('test'));
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter::contains
     */
    public function testContains()
    {
        $this->assertTrue($this->adapter->save('test', 'data', 1000));
        $this->assertTrue($this->adapter->contains('test'));
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter::delete
     */
    public function testDelete()
    {
        $this->assertTrue($this->adapter->save('test', 'data', 1000));
        $this->assertTrue($this->adapter->delete('test'));
        $this->assertFalse($this->adapter->contains('test'));
    }
}
