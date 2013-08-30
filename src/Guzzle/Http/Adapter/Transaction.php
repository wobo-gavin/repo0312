<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\MessageFactoryInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface;

class Transaction
{
    /** @var ClientInterface */
    private $/* Replaced /* Replaced /* Replaced client */ */ */;

    /** @var RequestInterface */
    private $request;

    /** @var ResponseInterface */
    private $response;

    /** @var MessageFactoryInterface */
    private $messageFactory;

    /**
     * @param ClientInterface         $/* Replaced /* Replaced /* Replaced client */ */ */  Client that is used to send the requests
     * @param RequestInterface        $request
     * @param MessageFactoryInterface $messageFactory
     */
    public function __construct(
        ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        RequestInterface $request,
        MessageFactoryInterface $messageFactory
    ) {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->request = $request;
        $this->messageFactory = $messageFactory;
    }

    /**
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set a response on the transaction
     *
     * @param string          $statusCode   HTTP response status code
     * @param string          $reasonPhrase Response reason phrase
     * @param array           $headers      Headers of the response
     * @param StreamInterface $body         Response body
     */
    public function setResponse($statusCode, $reasonPhrase, array $headers, StreamInterface $body)
    {
        $this->response = $this->messageFactory->createResponse($statusCode, $reasonPhrase, $headers, $body);
    }

    /**
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->/* Replaced /* Replaced /* Replaced client */ */ */;
    }
}
