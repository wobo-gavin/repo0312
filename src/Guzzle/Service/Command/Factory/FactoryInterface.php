<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\Factory;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;

/**
 * Interface for creating commands by name
 */
interface FactoryInterface
{
    /**
     * Create a command by name
     *
     * @param string $name Command to create
     * @param array  $args (optional) Command arguments
     *
     * @return null|CommandInterface
     */
    function factory($name, array $args = array());
}