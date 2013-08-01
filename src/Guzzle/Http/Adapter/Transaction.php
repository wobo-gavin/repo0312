<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\AdapterException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;

/**
 * SplObjectStorage object that only allows Requests that map to Responses or Exception
 */
class Transaction extends \SplObjectStorage
{
    public function offsetSet($object, $data = null)
    {
        if (!($object instanceof RequestInterface)) {
            throw new \InvalidArgumentException('Offset must be a request');
        }

        if (!($data instanceof ResponseInterface || $data instanceof RequestException)) {
            throw new \InvalidArgumentException('Value must be a response or RequestException');
        }

        parent::offsetSet($object, $data);
    }

    /**
     * Get an array of results of the transaction. Each item in the array is
     * either a {@see ResponseInterface} or {@see RequestException} object.
     *
     * @return array
     */
    public function getResults()
    {
        $responses = [];
        foreach ($this as $request) {
            $responses[] = $this[$request];
        }

        return $responses;
    }

    /**
     * Check if the transaction has any exceptions
     *
     * @return bool
     */
    public function hasExceptions()
    {
        foreach ($this as $request) {
            if ($this[$request] instanceof AdapterException) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get a Transaction object that only contains exceptions
     *
     * @return Transaction
     */
    public function getExceptions()
    {
        $transaction = new Transaction();
        foreach ($this as $request) {
            if ($this[$request] instanceof AdapterException) {
                $transaction[$request] = $this[$request];
            }
        }

        return $transaction;
    }

    /**
     * Get a Transaction object that only contains valid responses
     *
     * @return Transaction
     */
    public function getResponses()
    {
        $transaction = new Transaction();
        foreach ($this as $request) {
            if ($this[$request] instanceof ResponseInterface) {
                $transaction[$request] = $this[$request];
            }
        }

        return $transaction;
    }
}
