<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\CommandInterface;

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
     * @param CommandInterface $command Command of the event
     * @param ErrorEvent       $e       Error event that was encountered
     */
    public function __construct(CommandInterface $command, ErrorEvent $e)
    {
        $this->command = $command;
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
