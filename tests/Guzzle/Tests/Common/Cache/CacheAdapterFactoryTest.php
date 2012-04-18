<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\CacheAdapterFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter;
use Doctrine\Common\Cache\ArrayCache;

class CacheAdapterFactoryTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
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
    protected function setup()
    {
        parent::setUp();
        $this->cache = new ArrayCache();
        $this->adapter = new DoctrineCacheAdapter($this->cache);
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\CacheAdapterFactory::factory
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage provider is a required CacheAdapterFactory option
     */
    public function testEnsuresRequiredProviderOption()
    {
        CacheAdapterFactory::factory(array(
            'adapter' => $this->adapter
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\CacheAdapterFactory::factory
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage adapter is a required CacheAdapterFactory option
     */
    public function testEnsuresRequiredAdapterOption()
    {
        CacheAdapterFactory::factory(array(
            'provider' => $this->cache
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\CacheAdapterFactory::factory
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage foo is not a valid class for adapter
     */
    public function testEnsuresClassesExist()
    {
        CacheAdapterFactory::factory(array(
            'provider' => 'abc',
            'adapter'  => 'foo'
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\CacheAdapterFactory::factory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\CacheAdapterFactory::createObject
     */
    public function testCreatesProviderFromConfig()
    {
        $cache = CacheAdapterFactory::factory(array(
            'provider' => 'Doctrine\Common\Cache\ApcCache',
            'adapter'  => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter'
        ));

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter', $cache);
        $this->assertInstanceOf('Doctrine\Common\Cache\ApcCache', $cache->getCacheObject());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\CacheAdapterFactory::factory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\CacheAdapterFactory::createObject
     */
    public function testCreatesProviderFromConfigWithArguments()
    {
        $cache = CacheAdapterFactory::factory(array(
            'provider'      => 'Doctrine\Common\Cache\ApcCache',
            'provider.args' => array(),
            'adapter'       => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter',
            'adapter.args'  => array()
        ));

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\DoctrineCacheAdapter', $cache);
        $this->assertInstanceOf('Doctrine\Common\Cache\ApcCache', $cache->getCacheObject());
    }
}