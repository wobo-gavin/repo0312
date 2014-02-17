<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface;

/**
 * Represents a transactions that consists of a request, response, and /* Replaced /* Replaced /* Replaced client */ */ */
 */
interface TransactionInterface
{
    /**
     * @return RequestInterface
     */
    public function getRequest();

    /**
     * @return ResponseInterface|null
     */
    public function getResponse();

    /**
     * Set a response on the transaction
     *
     * @param ResponseInterface $response Response to set
     */
    public function setResponse(ResponseInterface $response);

    /**
     * @return ClientInterface
     */
    public function getClient();
}
