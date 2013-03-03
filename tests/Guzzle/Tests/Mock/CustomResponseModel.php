<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Mock;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\ResponseClassInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\OperationCommand;

class CustomResponseModel implements ResponseClassInterface
{
    public $command;

    public static function fromCommand(OperationCommand $command)
    {
        return new self($command);
    }

    public function __construct($command)
    {
        $this->command = $command;
    }
}
