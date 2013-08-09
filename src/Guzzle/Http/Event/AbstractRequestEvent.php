<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;

abstract class AbstractRequestEvent extends Event
{
    /** @var Transaction */
    protected $transaction;

    /** @var RequestInterface $request */
    private $request;

    /**
     * @param RequestInterface        $request
     * @param Transaction             $transaction Transaction that contains the request
     */
    public function __construct(RequestInterface $request, Transaction $transaction)
    {
        parent::__construct();
        $this->transaction = $transaction;
        $this->request = $request;
    }

    /**
     * Get the /* Replaced /* Replaced /* Replaced client */ */ */ associated with the event
     *
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->transaction->getClient();
    }

    /**
     * Get the request object
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Emit an error event
     */
    protected function emitError()
    {
        $this->request->getEventDispatcher()->dispatch(
            'request.error',
            new RequestErrorEvent($this->request, $this->transaction)
        );
    }

    /**
     * Emit an after_send event
     */
    protected function emitAfterSend()
    {
        $this->request->getEventDispatcher()->dispatch(
            'request.after_send',
            new RequestAfterSendEvent($this->request, $this->transaction)
        );
    }
}
