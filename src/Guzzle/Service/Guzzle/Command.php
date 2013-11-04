<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasDataTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\ResponseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\OperationInterface;

class Command implements CommandInterface
{
    use HasDataTrait;
    protected $operation;

    public function __construct(array $args, OperationInterface $operation)
    {
        $this->data = $args;
        $this->operation = $operation;
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function getRequest()
    {

    }

    public function processResponse(ResponseInterface $response)
    {

    }

    public function processError(RequestException $e)
    {

    }
}
