<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface;

/**
 * Adapter interface used to transfer HTTP requests.
 *
 * @link http://docs./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org/en//* Replaced /* Replaced /* Replaced guzzle */ */ */4/adapters.html for a full
 *     explanation of adapters and their responsibilities.
 */
interface AdapterInterface
{
    /**
     * Transfers an HTTP request and populates a response
     *
     * @param TransactionInterface $transaction Transaction abject to populate
     *
     * @return ResponseInterface
     */
    public function send(TransactionInterface $transaction);
}
