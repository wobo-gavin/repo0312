<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\AdapterException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\BatchException;

/**
 * Adapter interface used to transfer multiple HTTP requests
 */
interface BatchAdapterInterface
{
    /**
     * Transfers multiple HTTP requests in parallel
     *
     * @param \Iterator $transactions Iterable of TransactionInterface objects
     * @param int       $parallel     Maximum number of requests to send in parallel
     *
     * @throws BatchException|AdapterException
     */
    public function batch(\Iterator $transactions, $parallel);
}
