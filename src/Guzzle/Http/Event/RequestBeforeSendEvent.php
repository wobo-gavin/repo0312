<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;

/**
 * Event object emitted before a request is sent.
 *
 * You can intercept a request and inject a response or exception onto the event object. Intercepting a request from an
 * event listener will prevent the /* Replaced /* Replaced /* Replaced client */ */ */ from sending the request over the wire. The injected response will then be
 * used as the result of the request.
 */
class RequestBeforeSendEvent extends AbstractRequestEvent
{
    /**
     * Intercept the request and inject a response or exception
     *
     * @param ResponseInterface|RequestException $result Response or Exception to set
     */
    public function intercept($result)
    {
        $this->stopPropagation();

        if ($result instanceof ResponseInterface) {
            $this->getTransaction()->setResponse($result);
            $this->emitAfterSend();
        } else {
            $this->emitError($result);
        }
    }
}
