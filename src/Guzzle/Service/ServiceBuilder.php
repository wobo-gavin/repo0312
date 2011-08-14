<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Cache\CacheAdapterInterface;

/**
 * Service builder to generate service builders and service /* Replaced /* Replaced /* Replaced client */ */ */s from
 * configuration settings
 *
 * @author  michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org
 */
class ServiceBuilder implements \ArrayAccess
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
     * Create a new ServiceBuilder using configuration data sourced from an
     * array, .json|.js file, SimpleXMLElement, or .xml file.
     *
     * @param array|string|SimpleXMLElement $data An instantiated
     *      SimpleXMLElement containing configuration data, the full path to an
     *      .xml or .js|.json file, or an associative array of data
     * @param CacheAdapterInterface $cacheAdapter (optional) Pass a cache
     *      adapter to cache the computed service configuration settings
     * @param int $ttl (optional) How long to cache the parsed service data
     * @param string $extension (optional) When passing a string of data to load
     *      from a file, you can set $extension to specify the file type if the
     *      extension is not the standard extension for the file name (e.g. xml,
     *      js, json)
     *
     * @return ServiceBuilder
     * @throws RuntimeException if a file cannot be openend
     * @throws LogicException when trying to extend a missing /* Replaced /* Replaced /* Replaced client */ */ */
     */
    public static function factory($data, CacheAdapterInterface $cacheAdapter = null, $ttl = 86400, $extension = null)
    {
        $config = array();
        switch (gettype($data)) {
            case 'array':
                $config = $data;
                break;
            case 'object':
                if (!($data instanceof \SimpleXMLElement)) {
                    throw new \InvalidArgumentException('$data must be an instance of SimpleXMLElement');
                }
                break;
            case 'string':
                if ($cacheAdapter) {
                    // Compute the cache key for this service and check if it exists in cache
                    $key = str_replace('__', '_', 'guz_' . preg_replace('~[^\\pL\d]+~u', '_', strtolower(realpath($data))));
                    if ($cached = $cacheAdapter->fetch($key)) {
                        return new self(unserialize($cached));
                    }
                }
                if (!is_file($data)) {
                    throw new \RuntimeException('Unable to open service configuration file ' . $data);
                }
                $extension = $extension ?: pathinfo($data, PATHINFO_EXTENSION);
                switch ($extension) {
                    case 'xml':
                        $data = new \SimpleXMLElement($data, null, true);
                        break;
                    case 'js': case 'json':
                        $config = json_decode(file_get_contents($data), true);
                        break;
                    default:
                        throw new \RuntimeException('Unknown file type ' . $extension);
                }
                break;
        }

        if ($data instanceof \SimpleXMLElement) {
            $config = array();
            foreach ($data->/* Replaced /* Replaced /* Replaced client */ */ */s->/* Replaced /* Replaced /* Replaced client */ */ */ as $/* Replaced /* Replaced /* Replaced client */ */ */) {
                $row = array();
                $name = (string) $/* Replaced /* Replaced /* Replaced client */ */ */->attributes()->name;
                $class = (string) $/* Replaced /* Replaced /* Replaced client */ */ */->attributes()->class;
                foreach ($/* Replaced /* Replaced /* Replaced client */ */ */->param as $param) {
                    $row[(string) $param->attributes()->name] = (string) $param->attributes()->value;
                }
                $config[$name] = array(
                    'class'   => str_replace('.', '\\', $class),
                    'extends' => (string) $/* Replaced /* Replaced /* Replaced client */ */ */->attributes()->extends,
                    'params'  => $row
                );
            }
        }

        // Validate the configuration and handle extensions
        foreach ($config as $name => &$/* Replaced /* Replaced /* Replaced client */ */ */) {
            // Check if this /* Replaced /* Replaced /* Replaced client */ */ */ builder extends another /* Replaced /* Replaced /* Replaced client */ */ */
            if (isset($/* Replaced /* Replaced /* Replaced client */ */ */['extends']) && trim($/* Replaced /* Replaced /* Replaced client */ */ */['extends'])) {
                // Make sure that the service it's extending has been defined
                if (!isset($config[$/* Replaced /* Replaced /* Replaced client */ */ */['extends']])) {
                    throw new \LogicException($name . ' is trying to extend a non-existent service: ' . $/* Replaced /* Replaced /* Replaced client */ */ */['extends']);
                }
                if (!isset($/* Replaced /* Replaced /* Replaced client */ */ */['class']) || !$/* Replaced /* Replaced /* Replaced client */ */ */['class']) {
                    $/* Replaced /* Replaced /* Replaced client */ */ */['class'] = $config[$/* Replaced /* Replaced /* Replaced client */ */ */['extends']]['class'];
                }
                $/* Replaced /* Replaced /* Replaced client */ */ */['params'] = array_merge($config[$/* Replaced /* Replaced /* Replaced client */ */ */['extends']]['params'], $/* Replaced /* Replaced /* Replaced client */ */ */['params']);
            }
        }

        if ($cacheAdapter) {
            $cacheAdapter->save($key, serialize($config), $ttl);
        }

        return new self($config);
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
     * @return ClientInterface
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

    /**
     * Register a /* Replaced /* Replaced /* Replaced client */ */ */ by name with the service builder
     *
     * @param string $offset Name of the /* Replaced /* Replaced /* Replaced client */ */ */ to register
     * @param ClientInterface $value Client to register
     *
     * @return ServiceBuilder
     */
    public function offsetSet($offset, $value)
    {
        $this->builderConfig[$offset] = $value;

        return $this;
    }

    /**
     * Remove a registered /* Replaced /* Replaced /* Replaced client */ */ */ by name
     *
     * @param string $offset Client to remove by name
     *
     * @return ServiceBuilder
     */
    public function offsetUnset($offset)
    {
        if (isset($this->builderConfig[$offset])) {
            unset($this->builderConfig[$offset]);
        }

        return $this;
    }

    /**
     * Check if a /* Replaced /* Replaced /* Replaced client */ */ */ is registered with the service builder by name
     *
     * @param string $offset Name to check to see if a /* Replaced /* Replaced /* Replaced client */ */ */ exists
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->builderConfig[$offset]);
    }

    /**
     * Get a registered /* Replaced /* Replaced /* Replaced client */ */ */ by name
     *
     * @param string $offset Registered /* Replaced /* Replaced /* Replaced client */ */ */ name to retrieve
     *
     * @return ClientInterface
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }
}