<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface;

/**
 * Event object emitted before a request is sent.
 *
 * You may change the Response associated with the request using the
 * intercept() method of the event.
 */
class BeforeEvent extends AbstractRequestEvent
{
    /**
     * Intercept the request and associate a response
     *
     * @param ResponseInterface $response Response to set
     */
    public function intercept(ResponseInterface $response)
    {
        $trans = $this->getTransaction();
        $trans->response = $response;
        $trans->exception = null;
        $this->stopPropagation();
        RequestEvents::emitComplete($trans);
    }
}
