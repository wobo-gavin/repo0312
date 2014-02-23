<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;

/**
 * Event emitted when an error occurs while transferring a request for a
 * command.
 *
 * Event listeners can inject a result onto the event to intercept the
 * exception with a successful result.
 */
class CommandErrorEvent extends AbstractCommandEvent
{
    /** @var ErrorEvent */
    private $event;

    /**
     * @param CommandInterface       $command Command of the event
     * @param ServiceClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */  Client that sent the command
     * @param ErrorEvent             $e       Error event that was encountered
     */
    public function __construct(
        CommandInterface $command,
        ServiceClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        ErrorEvent $e
    ) {
        $this->command = $command;
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->error = $e;
    }

    /**
     * Get the request error event that occurred
     *
     * @return ErrorEvent
     */
    public function getRequestErrorEvent()
    {
        return $this->event;
    }

    /**
     * Intercept the error and inject a result
     *
     * @param mixed $result Result to associate with the command
     */
    public function setResult($result)
    {
        $this->result = $result;
        $this->stopPropagation();
    }
}
