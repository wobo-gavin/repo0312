<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;

class ServiceClient implements ServiceClientInterface
{
    private $/* Replaced /* Replaced /* Replaced client */ */ */;
    private $description;
    private $config;

    public function __construct(
        ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        DescriptionInterface $description,
        array $config = []
    ) {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->description = $description;
        $this->config = $config;
    }

    public function getCommand($name, array $args = [])
    {
        if (!$this->description->hasOperation($name)) {
            throw new \InvalidArgumentException('No operation found matching ' . $name);
        }
    }

    public function execute(CommandInterface $command)
    {
        $request = $command->getRequest();

        try {
            $response = $this->/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
            return $command->processResponse($response);
        } catch (RequestException $e) {
            if (!$e->hasResponse()) {
                throw $e;
            }
            // $error = $command->processError($e);
            // throw new OperationErrorException($command, $error, $e);
        }
    }

    public function getDescription()
    {
        return $this->description;
    }
}
