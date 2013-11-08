<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasDispatcherTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Event\RequestErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Exception\RequestException;

/**
 * Default /* Replaced /* Replaced /* Replaced Guzzle */ */ */ service description based /* Replaced /* Replaced /* Replaced client */ */ */
 */
class ServiceClient implements ServiceClientInterface
{
    use HasDispatcherTrait;

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

    public function getHttpClient()
    {
        return $this->/* Replaced /* Replaced /* Replaced client */ */ */;
    }

    public function getCommand($name, array $args = [])
    {
        if (!$this->description->hasOperation($name)) {
            throw new \InvalidArgumentException('No operation found matching ' . $name);
        }
    }

    public function execute(CommandInterface $command)
    {
        try {
            $response = $this->/* Replaced /* Replaced /* Replaced client */ */ */->send($command->getRequest());
            return $command->processResponse($response);
        } catch (RequestException $e) {
            return $command->processError($e);
            // throw new OperationErrorException($command, $error, $e);
        }
    }

    public function getDescription()
    {
        return $this->description;
    }
}
