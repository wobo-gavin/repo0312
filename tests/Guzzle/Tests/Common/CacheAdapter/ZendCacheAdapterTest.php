<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\CacheAdapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\ZendCacheAdapter;

/**
 * CacheAdapter test case
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ZendCacheAdapterTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var \Doctrine\Common\Cache\ArrayCache
     */
    private $cache;

    /**
     * @var ZendCacheAdapter
     */
    private $adapter;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->cache = new \Zend_Cache_Backend_Test();
        $this->adapter = new ZendCacheAdapter($this->cache);
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\ZendCacheAdapter
     */
    public function testAll()
    {
        $this->assertTrue($this->adapter->save('id', 'data'));
        $this->assertTrue($this->adapter->delete('id', 'data'));
        $this->assertEquals('foo', $this->adapter->fetch('id'));
        $this->assertEquals('123456', $this->adapter->contains('id'));
    }
}