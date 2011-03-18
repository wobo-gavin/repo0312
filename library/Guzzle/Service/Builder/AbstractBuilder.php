<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Inspector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\CacheAdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException;

/**
 * Abstract service /* Replaced /* Replaced /* Replaced client */ */ */ builder
 *
 * @author  michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org
 */
abstract class AbstractBuilder
{
    /**
     * @var string Name of the builder
     */
    protected $name;

    /**
     * @var Collection Configuration object that should hold all config settings
     */
    protected $config;

    /**
     * @var CacheAdapterInterface
     */
    protected $cache;

    /**
     * @var int Cache entry TTL
     */
    protected $cacheTtl;

    /**
     * Construct the builder
     *
     * @param array $config (optional) Configuration values to apply.  A
     *      'base_url' array key is required, specifying the base URL of the
     *      web service.
     * @param string $name (optional) Name of the builder
     *
     * @throws ServiceException if a base_url is not specified
     */
    public function __construct(array $config = null, $name = '')
    {
        $this->name = $name;
        $this->config = new Collection($config);

        // Add default arguments to the config
        Inspector::getInstance()->validateClass(get_class($this), $this->config, false);

        // Make sure that the service has a base_url specified
        if (!$this->config->get('base_url')) {
            throw new ServiceException(
                'No base_url is set in the builder config or class docblock'
            );
        }
    }

    /**
     * Get the XML representation of the builder
     *
     * @return string
     */
    public function __toString()
    {
        $xml = '</* Replaced /* Replaced /* Replaced client */ */ */ name="' . htmlspecialchars($this->getName()) . '" class="' . htmlspecialchars(str_replace('\\', '.', $this->getClass())) . '">' . "\n";
        foreach ($this->config as $key => $value) {
            $xml .= '    <param name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '" />' . "\n";
        }

        return $xml . '<//* Replaced /* Replaced /* Replaced client */ */ */>';
    }

    /**
     * Build the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return Client
     */
    abstract public function build();

    /**
     * Get the class name of the /* Replaced /* Replaced /* Replaced client */ */ */ that will be built by the builder
     *
     * @return string
     */
    abstract public function getClass();

    /**
     * Validate that the builder has all of the required parameters and that the
     * configuration values set on the builder meet the requirements of the
     * docblock of the builder.
     *
     * @throws ServiceException If the config value set on the builder do not
     *      meet the requirements set in the docblock of the builder.
     */
    public final function validate()
    {
        Inspector::getInstance()->validateClass(get_class($this), $this->config, true);
    }

    /**
     * Set the CacheAdapter to use for the builder
     *
     * @param CacheAdapterInterface $cacheAdapter (optional) Pass a cache
     *      adapter to cache the service configuration settings loaded from the
     *      XML and to cache dynamically built services.
     * @param int $cacheTtl (optional) How long to cache items in the cache
     *      adapter (defaults to 24 hours).
     *
     * @return AbstractBuilder
     */
    public function setCache(CacheAdapterInterface $cacheAdapter, $cacheTtl = 86400)
    {
        $this->cache = $cacheAdapter;
        $this->cacheTtl = $cacheTtl ?: 86400;

        return $this;
    }

    /**
     * Get the name of the builder
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the name of the builder
     *
     * @param string $name Name of the builder
     *
     * @return AbstractBuilder
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}