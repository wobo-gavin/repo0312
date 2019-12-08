<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception;

use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

class InvalidRequestException extends \InvalidArgumentException implements RequestExceptionInterface, /* Replaced /* Replaced /* Replaced Guzzle */ */ */Exception
{
    /**
     * @var RequestInterface
     */
    private $request;

    public function __construct(RequestInterface $request, string $message)
    {
        $this->request = $request;
        parent::__construct($message);
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
