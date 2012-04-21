<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception;

/**
 * Command Set exception
 */
class CommandSetException extends \RuntimeException
{
    /**
     * @var array Commands with an invalid /* Replaced /* Replaced /* Replaced client */ */ */
     */
    private $invalidCommands = array();

    /**
     * Get the invalid commands in the CommandSet
     *
     * @return array
     */
    public function getCommands()
    {
        return $this->invalidCommands;
    }

    /**
     * Set the invalid commands in the CommandSet
     *
     * @param array $commands Array of Command objects
     */
    public function setCommands(array $commands)
    {
        $this->invalidCommands = $commands;
    }
}
