<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description;

/**
 * Build /* Replaced /* Replaced /* Replaced Guzzle */ */ */ commands based on an ApiCommand object
 */
class ApiCommandFactory implements CommandFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createCommand(ApiCommand $command, array $args)
    {
        $class = $command->getConcreteClass();

        return new $class($args, $command);
    }
}