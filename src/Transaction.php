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
    /** @var ClientInterface */
    public $/* Replaced /* Replaced /* Replaced client */ */ */;

    /** @var RequestInterface */
    public $request;

    /** @var ResponseInterface */
    public $response;

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
