<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ClientInterface;

/**
 * Command factory used when you need to provide aliases to commands
 */
class AliasFactory implements FactoryInterface
{
    /**
     * @var array Associative array mapping command aliases to the aliased command
     */
    protected $aliases;

    /**
     * @var ClientInterface Client used to retry using aliases
     */
    protected $/* Replaced /* Replaced /* Replaced client */ */ */;

    /**
     * @param ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */  Client used to retry with the alias
     * @param array           $aliases Associative array mapping aliases to the alias
     */
    public function __construct(ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */, array $aliases)
    {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->aliases = $aliases;
    }

    /**
     * {@inheritdoc}
     */
    public function factory($name, array $args = array())
    {
        if (isset($this->aliases[$name])) {
            try {
                return $this->/* Replaced /* Replaced /* Replaced client */ */ */->getCommand($this->aliases[$name], $args);
            } catch (InvalidArgumentException $e) {
                return null;
            }
        }
    }
}
