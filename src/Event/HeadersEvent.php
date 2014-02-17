<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter\TransactionInterface;

/**
 * Event object emitted after the response headers of a request have been received
 *
 * You may intercept the exception and inject a response into the event to rescue the request.
 */
class HeadersEvent extends AbstractRequestEvent
{
    /**
     * @param TransactionInterface $transaction Transaction that contains the request and response
     */
    public function __construct(TransactionInterface $transaction)
    {
        parent::__construct($transaction);
    }

    /**
     * Get the response the was received
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->getTransaction()->getResponse();
    }
}
