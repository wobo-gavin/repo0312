<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\CacheAdapterInterface;

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
    protected $builderConfig = array();

    /**
     * @var array Instantiated /* Replaced /* Replaced /* Replaced client */ */ */ objects
     */
    protected $/* Replaced /* Replaced /* Replaced client */ */ */s = array();

    /**
     * Create a new ServiceBuilder using an XML configuration file to configure
     * the registered ServiceBuilder builder objects
     *
     * @param string $filename Full path to the XML configuration file
     * @param CacheAdapterInterface $cacheAdapter (optional) Pass a cache
     *      adapter to cache the XML service configuration settings
     * @param int $ttl (optional) How long to cache the parsed XML data
     *
     * @return ServiceBuilder
     * @throws RuntimeException if the file cannot be openend
     * @throws LogicException when trying to extend a missing /* Replaced /* Replaced /* Replaced client */ */ */
     */
    public static function factory($filename, CacheAdapterInterface $cacheAdapter = null, $ttl = 86400)
    {
        // Compute the cache key for this service and check if it exists in cache
        $key = 'guz_service_' . md5($filename);
        $cached = $cacheAdapter ? $cacheAdapter->fetch($key) : false;

        if ($cached) {

            // Load the config from cache
            $config = unserialize($cached);

        } else {

            // Build the service config from the XML file if the file exists
            if (!is_file($filename)) {
                throw new \RuntimeException('Unable to open service configuration file ' . $filename);
            }

            $config = array();
            $xml = new \SimpleXMLElement($filename, null, true);

            // Create a /* Replaced /* Replaced /* Replaced client */ */ */ entry for each /* Replaced /* Replaced /* Replaced client */ */ */ in the XML file
            foreach ($xml->/* Replaced /* Replaced /* Replaced client */ */ */s->/* Replaced /* Replaced /* Replaced client */ */ */ as $/* Replaced /* Replaced /* Replaced client */ */ */) {

                $row = array();
                $name = (string) $/* Replaced /* Replaced /* Replaced client */ */ */->attributes()->name;
                $class = (string) $/* Replaced /* Replaced /* Replaced client */ */ */->attributes()->class;

                // Check if this /* Replaced /* Replaced /* Replaced client */ */ */ builder extends another /* Replaced /* Replaced /* Replaced client */ */ */
                if ($extends = (string) $/* Replaced /* Replaced /* Replaced client */ */ */->attributes()->extends) {
                    // Make sure that the service it's extending has been defined
                    if (!isset($config[$extends])) {
                        throw new \LogicException($name . ' is trying to extend a non-existent or not yet defined service: ' . $extends);
                    }

                    $class = $class ?: $config[$extends]['class'];
                    $row = $config[$extends]['params'];
                }

                // Add attributes to the row's parameters
                foreach ($/* Replaced /* Replaced /* Replaced client */ */ */->param as $param) {
                    $row[(string) $param->attributes()->name] = (string) $param->attributes()->value;
                }

                // Add this /* Replaced /* Replaced /* Replaced client */ */ */ builder
                $config[$name] = array(
                    'class' => str_replace('.', '\\', $class),
                    'params' => $row
                );
            }

            if ($cacheAdapter) {
                $cacheAdapter->save($key, serialize($config), $ttl);
            }
        }

        $builder = new self($config);

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
        $this->builderConfig = $serviceBuilderConfig;
    }

    /**
     * Get a /* Replaced /* Replaced /* Replaced client */ */ */ using a registered builder
     *
     * @param $name Name of the registered /* Replaced /* Replaced /* Replaced client */ */ */ to retrieve
     * @param bool $throwAway (optional) Set to TRUE to not store the /* Replaced /* Replaced /* Replaced client */ */ */
     *     for later retrieval from the ServiceBuilder
     *
     * @return Client
     * @throws InvalidArgumentException when a /* Replaced /* Replaced /* Replaced client */ */ */ cannot be found by name
     */
    public function get($name, $throwAway = false)
    {
        if (!isset($this->builderConfig[$name])) {
            throw new \InvalidArgumentException('No /* Replaced /* Replaced /* Replaced client */ */ */ is registered as ' . $name);
        }

        if (!$throwAway && isset($this->/* Replaced /* Replaced /* Replaced client */ */ */s[$name])) {
            return $this->/* Replaced /* Replaced /* Replaced client */ */ */s[$name];
        }

        $/* Replaced /* Replaced /* Replaced client */ */ */ = call_user_func(
            array($this->builderConfig[$name]['class'], 'factory'),
            $this->builderConfig[$name]['params']
        );

        if (!$throwAway) {
            $this->/* Replaced /* Replaced /* Replaced client */ */ */s[$name] = $/* Replaced /* Replaced /* Replaced client */ */ */;
        }

        return $/* Replaced /* Replaced /* Replaced client */ */ */;
    }
}