<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HasEmitterTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\CommandException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\EventWrapper;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Description\/* Replaced /* Replaced /* Replaced Guzzle */ */ */Description;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Subscriber\PrepareRequest;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Subscriber\ProcessResponse;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Subscriber\ValidateInput;

/**
 * Default /* Replaced /* Replaced /* Replaced Guzzle */ */ */ web service /* Replaced /* Replaced /* Replaced client */ */ */ implementation.
 */
class /* Replaced /* Replaced /* Replaced Guzzle */ */ */Client implements /* Replaced /* Replaced /* Replaced Guzzle */ */ */ClientInterface
{
    use HasEmitterTrait;

    /** @var ClientInterface HTTP /* Replaced /* Replaced /* Replaced client */ */ */ used to send requests */
    private $/* Replaced /* Replaced /* Replaced client */ */ */;

    /** @var /* Replaced /* Replaced /* Replaced Guzzle */ */ */Description /* Replaced /* Replaced /* Replaced Guzzle */ */ */ service description */
    private $description;

    /** @var Collection Service /* Replaced /* Replaced /* Replaced client */ */ */ configuration data */
    private $config;

    /** @var callable Factory used for creating commands */
    private $commandFactory;

    /**
     * @param ClientInterface   $/* Replaced /* Replaced /* Replaced client */ */ */      Client used to send HTTP requests
     * @param /* Replaced /* Replaced /* Replaced Guzzle */ */ */Description $description /* Replaced /* Replaced /* Replaced Guzzle */ */ */ service description
     * @param array             $config      Configuration options
     *     - defaults: Associative array of default command parameters to add
     *       to each command created by the /* Replaced /* Replaced /* Replaced client */ */ */.
     *     - validate: Specify if command input is validated (defaults to true).
     *       Changing this setting after the /* Replaced /* Replaced /* Replaced client */ */ */ has been created will have
     *       no effect.
     *     - process: Specify if HTTP responses are parsed (defaults to true).
     *       Changing this setting after the /* Replaced /* Replaced /* Replaced client */ */ */ has been created will have
     *       no effect.
     *     - request_locations: Associative array of location types mapping to
     *       RequestLocationInterface objects.
     *     - response_locations: Associative array of location types mapping to
     *       ResponseLocationInterface objects.
     */
    public function __construct(
        ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */Description $description,
        array $config = []
    ) {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->description = $description;
        if (!isset($config['defaults'])) {
            $config['defaults'] = [];
        }
        $this->config = new Collection($config);
        $this->processConfig();
    }

    public function __call($name, array $arguments)
    {
        return $this->execute($this->getCommand($name, $arguments));
    }

    public function getCommand($name, array $args = [])
    {
        $factory = $this->commandFactory;
        // Merge in default command options
        $args += $this->config['defaults'];
        if (!($command = $factory($name, $args, $this))) {
            throw new \InvalidArgumentException("Invalid operation: $name");
        }

        return $command;
    }

    public function execute(CommandInterface $command)
    {
        try {
            $event = EventWrapper::prepareCommand($command, $this);
            if (null !== ($result = $event->getResult())) {
                return $result;
            }
            $request = $event->getRequest();
            return EventWrapper::processCommand(
                $command,
                $this,
                $request,
                $this->/* Replaced /* Replaced /* Replaced client */ */ */->send($request)
            );
        } catch (CommandException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new CommandException(
                'Error executing the command',
                $this,
                $command,
                null,
                null,
                $e
            );
        }
    }

    public function executeAll($commands, array $options = [])
    {

    }

    public function getHttpClient()
    {
        return $this->/* Replaced /* Replaced /* Replaced client */ */ */;
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

    /**
     * Creates a callable function used to create command objects from a
     * service description.
     *
     * @param /* Replaced /* Replaced /* Replaced Guzzle */ */ */Description $description Service description
     *
     * @return callable Returns a command factory
     */
    public static function defaultCommandFactory(/* Replaced /* Replaced /* Replaced Guzzle */ */ */Description $description)
    {
        return function (
            $name,
            array $args = [],
            /* Replaced /* Replaced /* Replaced Guzzle */ */ */ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */
        ) use ($description) {
            // Try with a capital and lowercase first letter
            if (!$description->hasOperation($name)) {
                $name = ucfirst($name);
            }

            if (!($operation = $description->getOperation($name))) {
                return null;
            }

            return new Command($operation, $args, clone $/* Replaced /* Replaced /* Replaced client */ */ */->getEmitter());
        };
    }

    /**
     * Prepares the /* Replaced /* Replaced /* Replaced client */ */ */ based on the configuration settings of the /* Replaced /* Replaced /* Replaced client */ */ */.
     */
    protected function processConfig()
    {
        // Use the passed in command factory or a custom factory if provided
        $this->commandFactory = isset($config['command_factory'])
            ? $config['command_factory']
            : self::defaultCommandFactory($this->description);

        // Add event listeners based on the configuration option
        $emitter = $this->getEmitter();

        if (!isset($this->config['validate']) ||
            $this->config['validate'] === true
        ) {
            $emitter->addSubscriber(new ValidateInput());
        }

        $emitter->addSubscriber(new PrepareRequest(
            $this->config['request_locations'] ?: []
        ));

        if (!isset($config['process']) || $config['process'] === true) {
            $emitter->addSubscriber(new ProcessResponse(
                $this->config['response_locations'] ?: []
            ));
        }
    }
}
