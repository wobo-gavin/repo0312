<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\CacheAdapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\DoctrineCacheAdapter;
use Doctrine\Common\Cache\ArrayCache;

/**
 * CacheAdapter test case
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\AbstractCacheAdapter::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\AbstractCacheAdapter
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\CacheAdapterInterface
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\DoctrineCacheAdapter::__construct
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\DoctrineCacheAdapter
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\CacheAdapterException
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\CacheAdapterException
     */
    public function test__construct()
    {
        $adapter = new DoctrineCacheAdapter(new \stdClass());        
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\AbstractCacheAdapter::__call
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\DoctrineCacheAdapter::__call
     * @expectedException \BadMethodCallException
     */
    public function test__callException()
    {
        $this->adapter->testContains();
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\AbstractCacheAdapter::__call
     */
    public function test__call()
    {
        $this->assertEquals(array(), $this->adapter->getIds());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\AbstractCacheAdapter::__call
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\DoctrineCacheAdapter::getCacheObject
     */
    public function testGetCacheObject()
    {
        $this->assertEquals($this->cache, $this->adapter->getCacheObject());
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\DoctrineCacheAdapter::save
     */
    public function testSave()
    {
        $this->assertTrue($this->adapter->save('test', 'data', 1000));
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\DoctrineCacheAdapter::fetch
     */
    public function testFetch()
    {
        $this->assertTrue($this->adapter->save('test', 'data', 1000));
        $this->assertEquals('data', $this->adapter->fetch('test'));
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\DoctrineCacheAdapter::contains
     */
    public function testContains()
    {
        $this->assertTrue($this->adapter->save('test', 'data', 1000));
        $this->assertTrue($this->adapter->contains('test'));
    }

    /**
     * @covers \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\DoctrineCacheAdapter::delete
     */
    public function testDelete()
    {
        $this->assertTrue($this->adapter->save('test', 'data', 1000));
        $this->assertTrue($this->adapter->delete('test'));
        $this->assertFalse($this->adapter->contains('test'));
    }
}