<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface;

/**
 * Represents the relationship between a /* Replaced /* Replaced /* Replaced client */ */ */, request, and response.
 *
 * You can access the request, response, and /* Replaced /* Replaced /* Replaced client */ */ */ using their corresponding
 * public properties.
 */
class Transaction
{
    /**
     * HTTP /* Replaced /* Replaced /* Replaced client */ */ */ used to transfer the request.
     *
     * @var ClientInterface
     */
    public $/* Replaced /* Replaced /* Replaced client */ */ */;

    /**
     * The request that is being sent.
     *
     * @var RequestInterface
     */
    public $request;

    /**
     * The response associated with the transaction. A response will not be
     * present when a networking error occurs or an error occurs before sending
     * the request.
     *
     * @var ResponseInterface|null
     */
    public $response;

    /**
     * Exception associated with the transaction. If this exception is present
     * when processing synchronous or future commands, then it is thrown. When
     * intercepting a failed transaction, you MUST set this value to null in
     * order to prevent the exception from being thrown.
     *
     * @var \Exception
     */
    public $exception;

    /**
     * @param ClientInterface  $/* Replaced /* Replaced /* Replaced client */ */ */  Client that is used to send the requests
     * @param RequestInterface $request Request to send
     */
    public function __construct(
        ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        RequestInterface $request
    ) {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->request = $request;
    }
}
