<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception;

/**
 * Command transfer exception when commands do not all use the same /* Replaced /* Replaced /* Replaced client */ */ */
 */
class InconsistentClientTransferException extends \RuntimeException
{
    /**
     * @var array Commands with an invalid /* Replaced /* Replaced /* Replaced client */ */ */
     */
    private $invalidCommands = array();

    /**
     * @param array $commands Invalid commands
     */
    public function __construct(array $commands)
    {
        $this->invalidCommands = $commands;
        parent::__construct(
            'Encountered commands in a batch transfer that use inconsistent /* Replaced /* Replaced /* Replaced client */ */ */s. The batching ' .
            'strategy you use with a command transfer must divide command batches by /* Replaced /* Replaced /* Replaced client */ */ */.'
        );
    }

    /**
     * Get the invalid commands
     *
     * @return array
     */
    public function getCommands()
    {
        return $this->invalidCommands;
    }
}
