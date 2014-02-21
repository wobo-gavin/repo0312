<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HasEmitterTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\Description\DescriptionInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\Event\CommandErrorEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\Event\PrepareEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\Event\ProcessEvent;

/**
 * Default /* Replaced /* Replaced /* Replaced Guzzle */ */ */ service description based /* Replaced /* Replaced /* Replaced client */ */ */.
 */
abstract class ServiceClient implements ServiceClientInterface
{
    use HasEmitterTrait;

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
        $this->config = new Collection($config);
    }

    public function getHttpClient()
    {
        return $this->/* Replaced /* Replaced /* Replaced client */ */ */;
    }

    public function execute(CommandInterface $command)
    {
        $event = new PrepareEvent($command);
        $command->getEmitter()->emit('prepare', $event);
        if (!($request = $event->getRequest())) {
            throw new \RuntimeException('No request was prepared for the '
                . 'command. One of the event listeners must set a request on '
                . 'the prepare event.');
        }

        // Handle request errors with the command
        $request->getEmitter()->on(
            'error',
            function (ErrorEvent $e) use ($command) {
                $event = new CommandErrorEvent($command, $e);
                $command->getEmitter()->emit('error', $event);
                if ($event->getResult()) {
                    $e->stopPropagation();
                }
            }
        );

        $response = $this->/* Replaced /* Replaced /* Replaced client */ */ */->send($request);
        $event = new ProcessEvent($command, $request, $response);
        $command->getEmitter()->emit('process', $event);

        return $event->getResult();
    }

    public function executeAll($commands, array $options = [])
    {

    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getConfig($keyOrPath = null)
    {
        return $keyOrPath === null
            ? $this->config->toArray()
            : $this->config->getPath($keyOrPath);
    }

    public function setConfig($keyOrPath, $value)
    {
        $this->config->setPath($keyOrPath, $value);
    }
}
