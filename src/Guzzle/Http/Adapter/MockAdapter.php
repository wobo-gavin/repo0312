<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestEvents;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestAfterSendEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface;

/**
 * Adapter that can be used to associate mock responses with a transaction
 */
class MockAdapter implements AdapterInterface
{
    private $response;

    /**
     * Set the response that will be served by the adapter
     *
     * @param ResponseInterface $response Response to serve
     */
    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function send(TransactionInterface $transaction)
    {
        $transaction->setResponse($this->response);
        $transaction->getRequest()->getEventDispatcher()->dispatch(
            RequestEvents::AFTER_SEND,
            new RequestAfterSendEvent($transaction)
        );
    }
}
