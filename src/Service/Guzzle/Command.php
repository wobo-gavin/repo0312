<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\HasDataTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HasEmitterTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\Description\OperationInterface;

class Command implements CommandInterface
{
    use HasDataTrait, HasEmitterTrait;
    private $operation;

    public function __construct(OperationInterface $operation, array $args)
    {
        $this->operation = $operation;
        $this->data = $args;
    }

    public function getOperation()
    {
        return $this->operation;
    }
}
