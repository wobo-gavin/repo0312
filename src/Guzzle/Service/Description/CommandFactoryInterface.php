<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description;

/**
 * Interface for building /* Replaced /* Replaced /* Replaced Guzzle */ */ */ commands.
 */
interface CommandFactoryInterface
{
    /**
     * Build a webservice command by name based on a service description
     *
     * @param ApiCommand $command Description of the command to create
     * @param array $args (optional) Arguments to pass to the command
     *
     * @return /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface
     * @throws InvalidArgumentException if the command was not found
     */
    function createCommand(ApiCommand $command, array $args);
}