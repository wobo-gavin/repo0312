<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\AdapterException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface;

/**
 * Event object emitted after a request has been sent.
 *
 * You may change the result value associated with a request using the setResult() method of the event.
 */
class AfterSendEvent extends AbstractRequestEvent
{
    /**
     * Set a transactional result for the request
     *
     * @param ResponseInterface|AdapterException $result Result to set for the request
     */
    public function setResult($result)
    {
        $this->transaction[$this['request']] = $result;
    }

    /**
     * Get the transactional result for the request
     *
     * @return ResponseInterface|AdapterException
     */
    public function getResult()
    {
        return $this->transaction[$this['request']];
    }

    /**
     * Check if the result of the request is a response object
     *
     * @return bool
     */
    public function hasResponse()
    {
        return $this->transaction[$this['request']] instanceof ResponseInterface;
    }

    /**
     * Check if the result of the request is an AdapterException object
     *
     * @return bool
     */
    public function hasException()
    {
        return $this->transaction[$this['request']] instanceof \Exception;
    }
}
