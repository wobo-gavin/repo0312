<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\AbstractEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\CommandInterface;

class AbstractCommandEvent extends AbstractEvent
{
    /** @var CommandInterface */
    protected $command;

    /** @var RequestInterface */
    protected $request;

    /** @var mixed|null */
    protected $result;

    /**
     * Get the command associated with the event
     *
     * @return CommandInterface
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Gets the HTTP request that will be sent for the command (if one is set).
     *
     * @return RequestInterface|null
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns the result of the command if it was intercepted.
     *
     * @return mixed|null
     */
    public function getResult()
    {
        return $this->result;
    }
}
