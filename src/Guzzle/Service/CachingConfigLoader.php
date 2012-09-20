<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Cache\CacheAdapterInterface;

/**
 * Decorator that adds caching to a service description loader
 */
class CachingConfigLoader implements ConfigLoaderInterface
{
    /**
     * @var ConfigLoaderInterface
     */
    protected $loader;

    /**
     * @var CacheAdapterInterface
     */
    protected $cache;

    /**
     * Add caching to a config loader
     *
     * @param ConfigLoaderInterface $loader Loader used to load the config when there is a cache miss
     * @param CacheAdapterInterface $cache  Object used to cache the loaded result
     */
    public function __construct(ConfigLoaderInterface $loader, CacheAdapterInterface $cache)
    {
        $this->loader = $loader;
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function load($config, array $options = array())
    {
        if (is_string($config)) {
            $key = 'loader_' . crc32($config);
            if ($result = $this->cache->fetch($key)) {
                return $result;
            }
        } else {
            $key = false;
        }

        $result = $this->loader->load($config, $options);
        if ($key) {
            $this->cache->save($key, $result);
        }

        return $result;
    }
}
