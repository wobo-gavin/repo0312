<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter\Transaction;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface;

/**
 * Exception encountered while transferring multiple requests in parallel
 */
class BatchException extends TransferException
{
    /** @var ClientInterface */
    protected $/* Replaced /* Replaced /* Replaced client */ */ */;

    /** @var Transaction */
    protected $transaction;

    public function __construct(
        $message = '',
        Transaction $transaction,
        ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        \Exception $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->transaction = $transaction;
    }

    /**
     * Get the transaction that encountered errors
     *
     * @return Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Get the /* Replaced /* Replaced /* Replaced client */ */ */ that sent the transaction
     *
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->/* Replaced /* Replaced /* Replaced client */ */ */;
    }
}
