<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter;

/**
 * Adapter interface used to transfer HTTP requests
 */
interface AdapterInterface
{
    /**
     * Transfers one or more HTTP requests and populates responses
     *
     * @param Transaction $transaction Hash of request to response object with an associated /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return Transaction Returns a hash mapping RequestInterface to ResponseInterface objects or AdapterExceptions
     */
    public function send(Transaction $transaction);
}
