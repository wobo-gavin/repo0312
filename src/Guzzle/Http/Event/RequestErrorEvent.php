<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface;

/**
 * Event object emitted after a request has been sent and an error was encountered
 *
 * You may intercept the exception and inject a response into the event to rescue the request.
 */
class RequestErrorEvent extends AbstractRequestEvent
{
    /**
     * Intercept the exception and inject a response
     *
     * @param ResponseInterface $response Response to set
     */
    public function intercept(ResponseInterface $response)
    {
        $request = $this->getRequest();
        $this->transaction[$request] = $response;
        $this->stopPropagation();
        $this->emitAfterSend();
    }

    /**
     * Get the exception that was encountered
     *
     * @return RequestException
     */
    public function getException()
    {
        return $this->transaction[$this->getRequest()];
    }

    /**
     * Check if the exception has a response
     *
     * @return bool
     */
    public function hasResponse()
    {
        return $this->getException()->hasResponse();
    }

    /**
     * Get the response the was received (if any)
     *
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->getException()->getResponse();
    }
}
