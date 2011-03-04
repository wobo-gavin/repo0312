<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\CacheAdapter\CacheAdapterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException;

/**
 * Service builder to generate service builders and service /* Replaced /* Replaced /* Replaced client */ */ */s from
 * configuration settings
 *
 * @author  michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org
 */
class ServiceBuilder
{
    /**
     * @var array Service builder configuration data
     */
    protected $serviceBuilderConfig = array();

    /**
     * @var array Instantiated service builders
     */
    protected $serviceBuilders = array();

    /**
     * @var array Instantiated /* Replaced /* Replaced /* Replaced client */ */ */ objects
     */
    protected $/* Replaced /* Replaced /* Replaced client */ */ */s = array();

    /**
     * @var CacheAdapterInterface Cache adapter to use for Service caching
     */
    protected $cache;

    /**
     * @var int Cache entry TTL
     */
    protected $cacheTtl;

    /**
     * Create a new ServiceBuilder using an XML configuration file to configure
     * the registered ServiceBuilder builder objects
     *
     * @param string $filename Full path to the XML configuration file
     * @param CacheAdapterInterface $cacheAdapter (optional) Pass a cache
     *      adapter to cache the service configuration settings loaded from the
     *      XML and to cache dynamically built services.
     * @param int $cacheTtl (optional) How long to cache items in the cache
     *      adapter (defaults to 24 hours).
     *
     * @return ServiceBuilder
     * @throws ServiceException if the file cannot be openend
     */
    public static function factory($filename, CacheAdapterInterface $cacheAdapter = null, $cacheTtl = 86400)
    {
        // Compute the cache key for this service and check if it exists in cache
        $key = 'guz_service_' . md5($filename);
        $cached = ($cacheAdapter) ? $cacheAdapter->fetch($key) : false;

        if ($cached) {

            // Load the config from cache
            $config = unserialize($cached);

        } else {

            // Build the service config from the XML file if the file exists
            if (!is_file($filename)) {
                throw new ServiceException('Unable to open service configuration file ' . $filename);
            }

            $config = array();
            $xml = new \SimpleXMLElement($filename, null, true);

            // Create a /* Replaced /* Replaced /* Replaced client */ */ */ entry for each /* Replaced /* Replaced /* Replaced client */ */ */ in the XML file
            foreach ($xml->/* Replaced /* Replaced /* Replaced client */ */ */s->/* Replaced /* Replaced /* Replaced client */ */ */ as $/* Replaced /* Replaced /* Replaced client */ */ */) {

                $row = array();
                $name = (string) $/* Replaced /* Replaced /* Replaced client */ */ */->attributes()->name;
                $builder = (string) $/* Replaced /* Replaced /* Replaced client */ */ */->attributes()->builder;
                $class = (string) $/* Replaced /* Replaced /* Replaced client */ */ */->attributes()->class;

                // Check if this /* Replaced /* Replaced /* Replaced client */ */ */ builder extends another /* Replaced /* Replaced /* Replaced client */ */ */
                if ($extends = (string) $/* Replaced /* Replaced /* Replaced client */ */ */->attributes()->extends) {
                    // Make sure that the service it's extending has been defined
                    if (!isset($config[$extends])) {
                        throw new ServiceException($name . ' is trying to extend a non-existent or not yet defined service: ' . $extends);
                    }

                    $builder = $builder ?: $config[$extends]['builder'];
                    $class = $class ?: $config[$extends]['class'];
                    $row = $config[$extends]['params'];
                }

                // Add attributes to the row's parameters
                foreach ($/* Replaced /* Replaced /* Replaced client */ */ */->param as $param) {
                    $row[(string) $param->attributes()->name] = (string) $param->attributes()->value;
                }

                // Add this /* Replaced /* Replaced /* Replaced client */ */ */ builder
                $config[$name] = array(
                    'builder' => $builder,
                    'class' => $class,
                    'params' => $row
                );
            }

            if ($cacheAdapter) {
                $cacheAdapter->save($key, serialize($config), $cacheTtl);
            }
        }

        $builder = new self($config);
        if ($cacheAdapter) {
            // Always share the cache
            $builder->setCache($cacheAdapter, $cacheTtl);
        }

        return $builder;
    }

    /**
     * Construct a new service builder
     *
     * @param array $serviceBuilderConfig Service configuration settings:
     *      name => Name of the service
     *      class => Builder class used to create /* Replaced /* Replaced /* Replaced client */ */ */s using dot notation (/* Replaced /* Replaced /* Replaced Guzzle */ */ */.Service.Aws.S3builder or /* Replaced /* Replaced /* Replaced Guzzle */ */ */.Service.Builder.DefaultBuilder)
     *      params => array of key value pair configuration settings for the builder
     */
    public function __construct(array $serviceBuilderConfig)
    {
        $this->serviceBuilderConfig = $serviceBuilderConfig;
    }

    /**
     * Set the CacheAdapter to pass to generated builders which will allow the
     * builders to access the CacheAdapter.  This is helpul for speeding up
     * the process of parsing and loading dynamically generated /* Replaced /* Replaced /* Replaced client */ */ */s.
     *
     * @param CacheAdapterInterface $cacheAdapter (optional) Pass a cache
     *      adapter to cache the service configuration settings loaded from the
     *      XML and to cache dynamically built services.
     * @param int $cacheTtl (optional) How long to cache items in the cache
     *      adapter (defaults to 24 hours).
     *
     * @return ServiceBuilder
     */
    public function setCache(CacheAdapterInterface $cacheAdapter, $cacheTtl = 86400)
    {
        $this->cache = $cacheAdapter;
        $this->cacheTtl = $cacheTtl ?: 86400;

        return $this;
    }

    /**
     * Get a registered service builder by name
     *
     * @param string $name Name of the registered service builder to retrieve
     * @param bool $throwAway (optional) Set to TRUE to not store the builder
     *      for later retrieval on the ServiceBuilder
     *
     * @return AbstractBuilder
     * @throws ServiceException if no builder is registered by the requested name
     * @throws ServiceException if no /* Replaced /* Replaced /* Replaced client */ */ */ attribute is set when using a DefaultBuilder
     */
    public function getBuilder($name, $throwAway = false)
    {
        if (!$throwAway && isset($this->serviceBuilders[$name])) {
            return $this->serviceBuilders[$name];
        }

        if (!isset($this->serviceBuilderConfig[$name])) {
            throw new ServiceException('No service builder is registered as ' . $name);
        }

        // Use the DefaultBuilder if no builder was specified
        if (!isset($this->serviceBuilderConfig[$name]['builder'])) {
            $this->serviceBuilderConfig[$name]['builder'] = '/* Replaced /* Replaced /* Replaced Guzzle */ */ */.Service.Builder.DefaultBuilder';
        }

        $class = str_replace('.', '\\', $this->serviceBuilderConfig[$name]['builder']);
        $builder = new $class($this->serviceBuilderConfig[$name]['params'], $name);
        if ($this->cache) {
            $builder->setCache($this->cache, $this->cacheTtl);
        }

        if ($class == '/* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Builder\\DefaultBuilder') {
            if (!isset($this->serviceBuilderConfig[$name]['class'])) {
                throw new ServiceException('A class attribute must be present when using /* Replaced /* Replaced /* Replaced Guzzle */ */ */\\Service\\Builder\\DefaultBuilder');
            }
            $builder->setClass($this->serviceBuilderConfig[$name]['class']);
        }

        if (!$throwAway) {
            $this->serviceBuilders[$name] = $builder;
        }

        return $builder;
    }

    /**
     * Get a /* Replaced /* Replaced /* Replaced client */ */ */ using a registered builder
     *
     * @param $name Name of the registered /* Replaced /* Replaced /* Replaced client */ */ */ to retrieve
     * @param bool $throwAway (optional) Set to TRUE to not store the /* Replaced /* Replaced /* Replaced client */ */ */
     *     for later retrieval from the ServiceBuilder
     *
     * @return Client
     */
    public function getClient($name, $throwAway = false)
    {
        if (!$throwAway && isset($this->/* Replaced /* Replaced /* Replaced client */ */ */s[$name])) {
            return $this->/* Replaced /* Replaced /* Replaced client */ */ */s[$name];
        }

        $/* Replaced /* Replaced /* Replaced client */ */ */ = $this->getBuilder($name, $throwAway)->build();

        if (!$throwAway) {
            $this->/* Replaced /* Replaced /* Replaced client */ */ */s[$name] = $/* Replaced /* Replaced /* Replaced client */ */ */;
        }

        return $/* Replaced /* Replaced /* Replaced client */ */ */;
    }
}