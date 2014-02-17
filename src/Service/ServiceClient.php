<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\HasEmitterTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\Command\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\Description\DescriptionInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\CommandDescriptionFactory;

/**
 * Default /* Replaced /* Replaced /* Replaced Guzzle */ */ */ service description based /* Replaced /* Replaced /* Replaced client */ */ */
 */
class ServiceClient implements ServiceClientInterface
{
    use HasEmitterTrait;

    private $/* Replaced /* Replaced /* Replaced client */ */ */;
    private $description;
    private $config;
    private $commandFactory;

    public function __construct(
        ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        DescriptionInterface $description,
        array $config = []
    ) {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->description = $description;
        $this->config = $config;
        $this->commandFactory = isset($config['command_factory'])
            ? $config['command_factory']
            : new CommandDescriptionFactory($this->description);
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
        $this->getHttpClient()->send($command->getRequest());

        return $command->getResult();
    }

    public function getDescription()
    {
        return $this->description;
    }
}
