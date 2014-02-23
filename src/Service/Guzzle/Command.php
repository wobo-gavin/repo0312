<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\EmitterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\HasDataTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HasEmitterTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Description\Operation;

/**
 * Default /* Replaced /* Replaced /* Replaced Guzzle */ */ */ command implementation.
 */
class Command implements /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface
{
    use HasDataTrait, HasEmitterTrait;

    /** @var Operation */
    private $operation;

    /** @var Collection */
    private $config;

    /**
     * @param Operation        $operation Operation associated with the command
     * @param array            $args      Arguments to pass to the command
     * @param EmitterInterface $emitter   Emitter used by the command
     */
    public function __construct(
        Operation $operation,
        array $args,
        EmitterInterface $emitter = null
    ) {
        $this->operation = $operation;
        $this->data = $args;
        $this->emitter = $emitter;
    }

    public function getName()
    {
        return $this->operation->getName();
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function hasParam($name)
    {
        return array_key_exists($name, $this->data);
    }

    public function getConfig()
    {
        if (!$this->config) {
            $this->config = new Collection();
        }

        return $this->config;
    }
}
