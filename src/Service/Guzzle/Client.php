<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\Description\DescriptionInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\ServiceClient;

class Client extends ServiceClient
{
    private $commandFactory;

    public function __construct(
        ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */Description $description,
        array $config = []
    ) {
        parent::__construct($/* Replaced /* Replaced /* Replaced client */ */ */, $description, $config);
        $this->commandFactory = isset($config['command_factory'])
            ? $config['command_factory']
            : self::getDefaultCommandFactory($description);
    }

    public function getCommand($name, array $args = [])
    {
        $description = $this->getDescription();
        if (!$description->hasOperation($name)) {
            throw new \InvalidArgumentException("No operation found matching {$name}");
        }

        return new Command($description->getOperation($name), $args);
    }

    public static function getDefaultCommandFactory(DescriptionInterface $description)
    {
        return function ($name, array $args = []) use ($description) {
            // If the command cannot be found, try again with a capital first
            // letter.
            if (!$description->hasOperation($name)) {
                $name = ucfirst($name);
            }

            if (!($operation = $description->getOperation($name))) {
                return null;
            }

            $class = $operation->getMetadata('class') ?: '/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Command';

            return new $class($args, $operation);
        };
    }
}
