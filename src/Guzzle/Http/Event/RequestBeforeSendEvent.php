<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface;

/**
 * Event object emitted before a request is sent.
 *
 * You can intercept a request and inject a response onto the event object. Intercepting a request from an event
 * listener will prevent the /* Replaced /* Replaced /* Replaced client */ */ */ from sending the request over the wire. The injected response will then be used as
 * the response for the request.
 */
class RequestBeforeSendEvent extends AbstractRequestEvent
{
    /**
     * Intercept the request and inject a response
     *
     * @param ResponseInterface $response Response to set
     */
    public function intercept(ResponseInterface $response)
    {
        $request = $this->getRequest();
        $this->transaction[$request] = $response;
        $this->stopPropagation();

        // Emit the 'request.after_send' event for the request
        $request->getEventDispatcher()->dispatch(
            'request.after_send',
            new RequestAfterSendEvent($request, $this->transaction)
        );
    }
}
