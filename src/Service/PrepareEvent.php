<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;

/**
 * Event emitted when a command is being prepared.
 *
 * Event listeners can inject a {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface}
 * object onto the event to be used as the request sent over the wire.
 */
class PrepareEvent extends AbstractCommandEvent
{
    /**
     * @param CommandInterface       $command Command to prepare
     * @param ServiceClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */  Client used to send the command
     */
    public function __construct(
        CommandInterface $command,
        ServiceClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */
    ) {
        $this->command = $command;
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
    }

    /**
     * Set the HTTP request that will be sent for the command.
     *
     * @param RequestInterface $request Request to send for the command
     */
    public function setRequest(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Intercept the prepare event and inject a response.
     *
     * @param mixed $result Result to associate with the command
     */
    public function setResult($result)
    {
        $this->result = $result;
        $this->stopPropagation();
    }
}
