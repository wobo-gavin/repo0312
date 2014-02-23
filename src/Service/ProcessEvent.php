<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface;

/**
 * Event emitted when the HTTP response of a command is being processed.
 *
 * Event listeners can inject a result onto the event to change the result of
 * the command.
 */
class ProcessEvent extends AbstractCommandEvent
{
    /** @var ResponseInterface */
    private $response;

    /**
     * @param CommandInterface       $command  Command
     * @param ServiceClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */   Client used to send the command
     * @param RequestInterface       $request  Request that was sent
     * @param ResponseInterface      $response Response that was received
     * @param mixed                  $result   Can specify the result up-front
     */
    public function __construct(
        CommandInterface $command,
        ServiceClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        RequestInterface $request,
        ResponseInterface $response = null,
        $result = null
    ) {
        $this->command = $command;
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->request = $request;
        $this->response = $response;
        $this->result = $result;
    }

    /**
     * Get the response that was received for the request (if one is present).
     *
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set the processed result on the event.
     *
     * @param mixed $result Result to associate with the command
     */
    public function setResult($result)
    {
        $this->result = $result;
    }
}
