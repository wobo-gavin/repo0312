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
    protected $request;
    protected $serializer;

    public function __construct(array $args)
    {
        $this->data = $args;
        //$this->operation = $operation;
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function getRequest()
    {
        if (!isset($this['/* Replaced /* Replaced /* Replaced client */ */ */'])) {
            throw new \RuntimeException('A /* Replaced /* Replaced /* Replaced client */ */ */ must be specified on the command');
        }

        if (!$this->request) {
            $this->request = $this['/* Replaced /* Replaced /* Replaced client */ */ */']->createRequest('GET', 'https://raw.github.com/aws/aws-sdk-core-ruby/master/apis/CloudFront-2012-05-05.json');
        }

        return $this->request;
    }

    public function processResponse(ResponseInterface $response)
    {
        return $response->json();
    }

    public function processError(RequestException $e)
    {
        return $e->getResponse()->json();
    }
}
