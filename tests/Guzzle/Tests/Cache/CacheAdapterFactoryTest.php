<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Cache;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\DoctrineCacheAdapter;
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
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterFactory::factory
     * @expectedException InvalidArgumentException
     */
    public function testEnsuresConfigIsArray()
    {
        CacheAdapterFactory::factory(new \stdClass());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterFactory::factory
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage cache.provider is a required CacheAdapterFactory option
     */
    public function testEnsuresRequiredProviderOption()
    {
        CacheAdapterFactory::factory(array(
            'cache.adapter' => $this->adapter
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterFactory::factory
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     * @expectedExceptionMessage cache.adapter is a required CacheAdapterFactory option
     */
    public function testEnsuresRequiredAdapterOption()
    {
        CacheAdapterFactory::factory(array(
            'cache.provider' => $this->cache
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterFactory::factory
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage foo is not a valid class for cache.adapter
     */
    public function testEnsuresClassesExist()
    {
        CacheAdapterFactory::factory(array(
            'cache.provider' => 'abc',
            'cache.adapter'  => 'foo'
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterFactory::factory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterFactory::createObject
     */
    public function testCreatesProviderFromConfig()
    {
        $cache = CacheAdapterFactory::factory(array(
            'cache.provider' => 'Doctrine\Common\Cache\ApcCache',
            'cache.adapter'  => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\DoctrineCacheAdapter'
        ));

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\DoctrineCacheAdapter', $cache);
        $this->assertInstanceOf('Doctrine\Common\Cache\ApcCache', $cache->getCacheObject());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterFactory::factory
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterFactory::createObject
     */
    public function testCreatesProviderFromConfigWithArguments()
    {
        $cache = CacheAdapterFactory::factory(array(
            'cache.provider'      => 'Doctrine\Common\Cache\ApcCache',
            'cache.provider.args' => array(),
            'cache.adapter'       => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\DoctrineCacheAdapter',
            'cache.adapter.args'  => array()
        ));

        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\DoctrineCacheAdapter', $cache);
        $this->assertInstanceOf('Doctrine\Common\Cache\ApcCache', $cache->getCacheObject());
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterFactory
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\RuntimeException
     */
    public function testWrapsExceptionsOnObjectCreation()
    {
        CacheAdapterFactory::factory(array(
            'cache.provider' => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Mock\ExceptionMock',
            'cache.adapter'  => '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Mock\ExceptionMock'
        ));
    }

    /**
     * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterFactory
     */
    public function testCreatesNullCacheAdapterByDefault()
    {
        $adapter = CacheAdapterFactory::factory(array());
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\NullCacheAdapter', $adapter);
    }
}