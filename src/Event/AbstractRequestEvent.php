<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;

/**
 * Base class for request events, providing a request and /* Replaced /* Replaced /* Replaced client */ */ */ getter.
 */
abstract class AbstractRequestEvent extends AbstractEvent
{
    /** @var Transaction */
    protected $transaction;

    /**
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the HTTP /* Replaced /* Replaced /* Replaced client */ */ */ associated with the event.
     *
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->transaction->/* Replaced /* Replaced /* Replaced client */ */ */;
    }

    /**
     * Get the request object
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->transaction->request;
    }

    /**
     * Get the number of transaction retries.
     *
     * @return int
     */
    public function getRetryCount()
    {
        return $this->transaction->retries;
    }

    /**
     * @return Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}
