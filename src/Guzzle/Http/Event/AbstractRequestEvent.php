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

    /**
     * @param RequestInterface        $request
     * @param Transaction             $transaction Transaction that contains the request
     */
    public function __construct(RequestInterface $request, Transaction $transaction)
    {
        $this->transaction = $transaction;
        parent::__construct(['request' => $request, '/* Replaced /* Replaced /* Replaced client */ */ */' => $transaction->getClient()]);
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
        return $this['request'];
    }
}
