<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\AbstractHasDispatcher;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceBuilderException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ClientNotFoundException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ServiceNotFoundException;

/**
 * Service builder to generate service builders and service /* Replaced /* Replaced /* Replaced client */ */ */s from
 * configuration settings
 */
class ServiceBuilder extends AbstractHasDispatcher implements \ArrayAccess, \Serializable
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
     * @param array|string|\SimpleXMLElement $data An instantiated
     *      SimpleXMLElement containing configuration data, the full path to an
     *      .xml or .js|.json file, or an associative array of data
     * @param string $extension (optional) When passing a string of data to load
     *      from a file, you can set $extension to specify the file type if the
     *      extension is not the standard extension for the file name (e.g. xml,
     *      js, json)
     *
     * @return ServiceBuilder
     * @throws ServiceBuilderException if a file cannot be openend
     * @throws ServiceBuilderException when trying to extend a missing /* Replaced /* Replaced /* Replaced client */ */ */
     */
    public static function factory($data, $extension = null)
    {
        $config = array();
        if (is_string($data)) {
            if (!is_readable($data)) {
                throw new ServiceBuilderException('Unable to open ' . $data);
            }
            $extension = $extension ?: pathinfo($data, PATHINFO_EXTENSION);
            if ($extension == 'xml') {
                $data = new \SimpleXMLElement($data, null, true);
            } elseif ($extension == 'js' || $extension == 'json') {
                $config = json_decode(file_get_contents($data), true);
            } else {
                throw new ServiceBuilderException('Unknown file type ' . $extension);
            }
        } elseif (is_array($data)) {
            $config = $data;
        } elseif (!($data instanceof \SimpleXMLElement)) {
            throw new ServiceBuilderException('Must pass a file name, array, or SimpleXMLElement');
        }

        if ($data instanceof \SimpleXMLElement) {
            foreach ($data->/* Replaced /* Replaced /* Replaced client */ */ */s->/* Replaced /* Replaced /* Replaced client */ */ */ as $/* Replaced /* Replaced /* Replaced client */ */ */) {
                $row = array();
                foreach ($/* Replaced /* Replaced /* Replaced client */ */ */->param as $param) {
                    $row[(string) $param->attributes()->name] = (string) $param->attributes()->value;
                }
                $config[(string) $/* Replaced /* Replaced /* Replaced client */ */ */->attributes()->name] = array(
                    'class'   => (string) $/* Replaced /* Replaced /* Replaced client */ */ */->attributes()->class,
                    'extends' => (string) $/* Replaced /* Replaced /* Replaced client */ */ */->attributes()->extends,
                    'params'  => $row
                );
            }
        }

        // Validate the configuration and handle extensions
        foreach ($config as $name => &$/* Replaced /* Replaced /* Replaced client */ */ */) {
            $/* Replaced /* Replaced /* Replaced client */ */ */['params'] = isset($/* Replaced /* Replaced /* Replaced client */ */ */['params']) ? $/* Replaced /* Replaced /* Replaced client */ */ */['params'] : array();
            // Check if this /* Replaced /* Replaced /* Replaced client */ */ */ builder extends another /* Replaced /* Replaced /* Replaced client */ */ */
            if (!empty($/* Replaced /* Replaced /* Replaced client */ */ */['extends'])) {
                // Make sure that the service it's extending has been defined
                if (!isset($config[$/* Replaced /* Replaced /* Replaced client */ */ */['extends']])) {
                    throw new ServiceNotFoundException($name . ' is trying to extend a non-existent service: ' . $/* Replaced /* Replaced /* Replaced client */ */ */['extends']);
                }
                $/* Replaced /* Replaced /* Replaced client */ */ */['class'] = empty($/* Replaced /* Replaced /* Replaced client */ */ */['class'])
                    ? $config[$/* Replaced /* Replaced /* Replaced client */ */ */['extends']]['class'] : $/* Replaced /* Replaced /* Replaced client */ */ */['class'];
                $/* Replaced /* Replaced /* Replaced client */ */ */['params'] = array_merge($config[$/* Replaced /* Replaced /* Replaced client */ */ */['extends']]['params'], $/* Replaced /* Replaced /* Replaced client */ */ */['params']);
            }
            $/* Replaced /* Replaced /* Replaced client */ */ */['class'] = !isset($/* Replaced /* Replaced /* Replaced client */ */ */['class']) ? '' : str_replace('.', '\\', $/* Replaced /* Replaced /* Replaced client */ */ */['class']);
        }

        return new static($config);
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
     * @param string $name Name of the registered /* Replaced /* Replaced /* Replaced client */ */ */ to retrieve
     * @param bool $throwAway (optional) Set to TRUE to not store the /* Replaced /* Replaced /* Replaced client */ */ */
     *     for later retrieval from the ServiceBuilder
     *
     * @return ClientInterface
     * @throws ClientNotFoundException when a /* Replaced /* Replaced /* Replaced client */ */ */ cannot be found by name
     */
    public function get($name, $throwAway = false)
    {
        if (!isset($this->builderConfig[$name])) {
            throw new ClientNotFoundException('No /* Replaced /* Replaced /* Replaced client */ */ */ is registered as ' . $name);
        }

        if (!$throwAway && isset($this->/* Replaced /* Replaced /* Replaced client */ */ */s[$name])) {
            return $this->/* Replaced /* Replaced /* Replaced client */ */ */s[$name];
        }

        // Convert references to the actual /* Replaced /* Replaced /* Replaced client */ */ */
        foreach ($this->builderConfig[$name]['params'] as $k => &$v) {
            if (0 === strpos($v, '{') && strlen($v) - 1 == strrpos($v, '}')) {
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
     * @param string $offset Name of the /* Replaced /* Replaced /* Replaced client */ */ */ to register
     * @param ClientInterface $value Client to register
     */
    public function offsetSet($offset, $value)
    {
        $this->builderConfig[$offset] = $value;
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
