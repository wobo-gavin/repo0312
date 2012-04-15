<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Inflector;

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
     * @param ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */ Client that owns the commands
     */
    public function __construct(ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */)
    {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
    }

    /**
     * {@inheritdoc}
     */
    public function factory($name, array $args = array())
    {
        // Determine the class to instantiate based on the namespace of the
        // current /* Replaced /* Replaced /* Replaced client */ */ */ and the default location of commands
        $prefix = $this->/* Replaced /* Replaced /* Replaced client */ */ */->getConfig('command.prefix');
        if (!$prefix) {
            // The prefix can be specified in a factory method and is cached
            $prefix = implode('\\', array_slice(explode('\\', get_class($this->/* Replaced /* Replaced /* Replaced client */ */ */)), 0, -1)) . '\\Command\\';
            $this->/* Replaced /* Replaced /* Replaced client */ */ */->getConfig()->set('command.prefix', $prefix);
        }

        $class = $prefix . str_replace(' ', '\\', ucwords(str_replace('.', ' ', Inflector::camel($name))));

        // Create the concrete command if it exists
        if (class_exists($class)) {
            return new $class($args);
        }
    }
}