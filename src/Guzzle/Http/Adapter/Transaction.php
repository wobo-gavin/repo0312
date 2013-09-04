<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface;

class Transaction implements TransactionInterface
{
    /** @var ClientInterface */
    private $/* Replaced /* Replaced /* Replaced client */ */ */;
    /** @var RequestInterface */
    private $request;
    /** @var ResponseInterface */
    private $response;

    /**
     * @param ClientInterface  $/* Replaced /* Replaced /* Replaced client */ */ */  Client that is used to send the requests
     * @param RequestInterface $request
     */
    public function __construct(ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */, RequestInterface $request)
    {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getClient()
    {
        return $this->/* Replaced /* Replaced /* Replaced client */ */ */;
    }
}
