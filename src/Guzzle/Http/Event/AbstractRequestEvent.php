<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Event;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\TransactionInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;

abstract class AbstractRequestEvent extends Event
{
    /** @var TransactionInterface */
    private $transaction;

    /**
     * @param TransactionInterface $transaction Transaction that contains the request
     */
    public function __construct(TransactionInterface $transaction)
    {
        $this->transaction = $transaction;
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
        return $this->transaction->getRequest();
    }

    /**
     * @return TransactionInterface
     */
    protected function getTransaction()
    {
        return $this->transaction;
    }
}
