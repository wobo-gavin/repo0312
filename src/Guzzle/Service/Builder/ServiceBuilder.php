<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Builder\ServiceBuilderAbstractFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceBuilderException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceNotFoundException;

/**
 * Service builder to generate service builders and service /* Replaced /* Replaced /* Replaced client */ */ */s from
 * configuration settings
 */
class ServiceBuilder extends AbstractHasDispatcher implements ServiceBuilderInterface, \ArrayAccess, \Serializable
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
     * @var ServiceBuilderAbstractFactory Cached instance of the abstract factory
     */
    protected static $cachedFactory;

    /**
     * Create a new ServiceBuilder using configuration data sourced from an
     * array, .json|.js file, SimpleXMLElement, or .xml file.
     *
     * @param array|string|\SimpleXMLElement $data An instantiated
     *     SimpleXMLElement containing configuration data, the full path to an
     *     .xml or .js|.json file, or an associative array of data
     * @param array $globalParameters Array of global parameters to
     *     pass to every service as it is instantiated.
     *
     * @return ServiceBuilderInterface
     * @throws ServiceBuilderException if a file cannot be opened
     * @throws ServiceNotFoundException when trying to extend a missing /* Replaced /* Replaced /* Replaced client */ */ */
     */
    public static function factory($config = null, array $globalParameters = null)
    {
        // @codeCoverageIgnoreStart
        if (!static::$cachedFactory) {
            static::$cachedFactory = new ServiceBuilderAbstractFactory();
        }
        // @codeCoverageIgnoreEnd

        return self::$cachedFactory->build($config, $globalParameters);
    }

    /**
     * Construct a new service builder
     *
     * @param array $serviceBuilderConfig Service configuration settings:
     *     - name: Name of the service
     *     - class: Client class to instantiate using a factory method
     *     - params: array of key value pair configuration settings for the builder
     */
    public function __construct(array $serviceBuilderConfig)
    {
        $this->builderConfig = $serviceBuilderConfig;
    }

    /**
     * {@inheritdoc}
     */
    public static function getAllEvents()
    {
        return array('service_builder.create_/* Replaced /* Replaced /* Replaced client */ */ */');
    }

    /**
     * Restores the service builder from JSON
     *
     * @param string $serialized JSON data to restore from
     */
    public function unserialize($serialized)
    {
        $this->builderConfig = json_decode($serialized, true);
    }

    /**
     * Represents the service builder as a string
     *
     * @return array
     */
    public function serialize()
    {
        return json_encode($this->builderConfig);
    }

    /**
     * Get a /* Replaced /* Replaced /* Replaced client */ */ */ using a registered builder
     *
     * @param string $name      Name of the registered /* Replaced /* Replaced /* Replaced client */ */ */ to retrieve
     * @param bool   $throwAway Set to TRUE to not store the /* Replaced /* Replaced /* Replaced client */ */ */ for later retrieval from the ServiceBuilder
     *
     * @return ClientInterface
     * @throws ServiceNotFoundException when a /* Replaced /* Replaced /* Replaced client */ */ */ cannot be found by name
     */
    public function get($name, $throwAway = false)
    {
        if (!isset($this->builderConfig[$name])) {
            throw new ServiceNotFoundException('No service is registered as ' . $name);
        }

        if (!$throwAway && isset($this->/* Replaced /* Replaced /* Replaced client */ */ */s[$name])) {
            return $this->/* Replaced /* Replaced /* Replaced client */ */ */s[$name];
        }

        // Convert references to the actual /* Replaced /* Replaced /* Replaced client */ */ */
        foreach ($this->builderConfig[$name]['params'] as &$v) {
            if (is_string($v) && 0 === strpos($v, '{') && strlen($v) - 1 == strrpos($v, '}')) {
                $v = $this->get(trim(str_replace(array('{', '}'), '', $v)));
            }
        }

        $/* Replaced /* Replaced /* Replaced client */ */ */ = call_user_func(
            array($this->builderConfig[$name]['class'], 'factory'),
            $this->builderConfig[$name]['params']
        );

        if (!$throwAway) {
            $this->/* Replaced /* Replaced /* Replaced client */ */ */s[$name] = $/* Replaced /* Replaced /* Replaced client */ */ */;
        }

        // Dispatch an event letting listeners know a /* Replaced /* Replaced /* Replaced client */ */ */ was created
        $this->dispatch('service_builder.create_/* Replaced /* Replaced /* Replaced client */ */ */', array(
            '/* Replaced /* Replaced /* Replaced client */ */ */' => $/* Replaced /* Replaced /* Replaced client */ */ */
        ));

        return $/* Replaced /* Replaced /* Replaced client */ */ */;
    }

    /**
     * Register a /* Replaced /* Replaced /* Replaced client */ */ */ by name with the service builder
     *
     * @param string $name  Name of the /* Replaced /* Replaced /* Replaced client */ */ */ to register
     * @param mixed  $value Service to register
     *
     * @return ServiceBuilderInterface
     */
    public function set($key, $service)
    {
        $this->builderConfig[$key] = $service;

        return $this;
    }

    /**
     * Register a /* Replaced /* Replaced /* Replaced client */ */ */ by name with the service builder
     *
     * @param string          $offset Name of the /* Replaced /* Replaced /* Replaced client */ */ */ to register
     * @param ClientInterface $value  Client to register
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Remove a registered /* Replaced /* Replaced /* Replaced client */ */ */ by name
     *
     * @param string $offset Client to remove by name
     */
    public function offsetUnset($offset)
    {
        unset($this->builderConfig[$offset]);
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
