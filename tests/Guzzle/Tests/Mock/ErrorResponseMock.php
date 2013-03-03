<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Mock;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Plugin\ErrorResponse\ErrorResponseExceptionInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;

class ErrorResponseMock extends \Exception implements ErrorResponseExceptionInterface
{
    public $command;
    public $response;

    public static function fromCommand(CommandInterface $command, Response $response)
    {
        return new self($command, $response);
    }

    public function __construct($command, $response)
    {
        $this->command = $command;
        $this->response = $response;
        $this->message = 'Error from ' . $response;
    }
}
