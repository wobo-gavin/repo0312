<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Inflection\InflectorInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Inflection\Inflector;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ClientInterface;

/**
 * Command factory used to create commands referencing concrete command classes
 */
class ConcreteClassFactory implements FactoryInterface
{
    /**
     * @var ClientInterface
     */
    protected $/* Replaced /* Replaced /* Replaced client */ */ */;

    /**
     * @var InflectorInterface
     */
    protected $inflector;

    /**
     * @param ClientInterface    $/* Replaced /* Replaced /* Replaced client */ */ */    Client that owns the commands
     * @param InflectorInterface $inflector Inflector used to resolve class names
     */
    public function __construct(ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */, InflectorInterface $inflector = null)
    {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->inflector = $inflector ?: Inflector::getDefault();
    }

    /**
     * {@inheritdoc}
     */
    public function factory($name, array $args = array())
    {
        // Determine the class to instantiate based on the namespace of the current /* Replaced /* Replaced /* Replaced client */ */ */ and the default directory
        $prefix = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('command.prefix');
        if (!$prefix) {
            // The prefix can be specified in a factory method and is cached
            $prefix = implode('\\', array_slice(explode('\\', get_class($this->/* Replaced /* Replaced /* Replaced client */ */ */)), 0, -1)) . '\\Command\\';
            $this->/* Replaced /* Replaced /* Replaced client */ */ */->getConfig()->set('command.prefix', $prefix);
        }

        $class = $prefix . str_replace(' ', '\\', ucwords(str_replace('.', ' ', $this->inflector->camel($name))));

        // Create the concrete command if it exists
        if (class_exists($class)) {
            return new $class($args);
        }
    }
}
