<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Curl\CurlHandle;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\Operation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\OperationInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ValidatorInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\SchemaValidator;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\CommandException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\ValidationException;

/**
 * Command object to handle preparing and processing /* Replaced /* Replaced /* Replaced client */ */ */ requests and responses of the requests
 */
abstract class AbstractCommand extends Collection implements CommandInterface
{
    // Option used to specify custom headers to add to the generated request
    const HEADERS_OPTION = 'command.headers';
    // Option used to add an onComplete method to a command
    const ON_COMPLETE = 'command.on_complete';
    // Option used to disable any pre-sending command validation
    const DISABLE_VALIDATION = 'command.disable_validation';
    // Option used to override how a command result will be formatted
    const RESPONSE_PROCESSING = 'command.response_processing';
    // Different response types that commands can use
    const TYPE_RAW = 'raw';
    const TYPE_NATIVE = 'native';
    const TYPE_MODEL = 'model';
    // Option used to change the entity body used to store a response
    const RESPONSE_BODY = 'command.response_body';

    /**
     * @var ClientInterface Client object used to execute the command
     */
    protected $/* Replaced /* Replaced /* Replaced client */ */ */;

    /**
     * @var RequestInterface The request object associated with the command
     */
    protected $request;

    /**
     * @var mixed The result of the command
     */
    protected $result;

    /**
     * @var OperationInterface API information about the command
     */
    protected $operation;

    /**
     * @var mixed callable
     */
    protected $onComplete;

    /**
     * @var ValidatorInterface Validator used to prepare and validate properties against a JSON schema
     */
    protected $validator;

    /**
     * Constructor
     *
     * @param array|Collection   $parameters Collection of parameters to set on the command
     * @param OperationInterface $operation Command definition from description
     */
    public function __construct($parameters = null, OperationInterface $operation = null)
    {
        parent::__construct($parameters);
        $this->operation = $operation ?: $this->createOperation();
        foreach ($this->operation->getParams() as $name => $arg) {
            $currentValue = $this->get($name);
            $configValue = $arg->getValue($currentValue);
            // If default or static values are set, then this should always be updated on the config object
            if ($currentValue !== $configValue) {
                $this->set($name, $configValue);
            }
        }

        $headers = $this->get(self::HEADERS_OPTION);
        if (!$headers instanceof Collection) {
            $this->set(self::HEADERS_OPTION, new Collection((array) $headers));
        }

        // You can set a command.on_complete option in your parameters to set an onComplete callback
        if ($onComplete = $this->get('command.on_complete')) {
            $this->remove('command.on_complete');
            $this->setOnComplete($onComplete);
        }

        // If no response processing value was specified, then attempt to use the highest level of processing
        if (!$this->get(self::RESPONSE_PROCESSING)) {
            $this->set(self::RESPONSE_PROCESSING, self::TYPE_MODEL);
        }

        $this->init();
    }

    /**
     * Custom clone behavior
     */
    public function __clone()
    {
        $this->request = null;
        $this->result = null;
    }

    /**
     * Execute the command in the same manner as calling a function
     *
     * @return mixed Returns the result of {@see AbstractCommand::execute}
     */
    public function __invoke()
    {
        return $this->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->operation->getName();
    }

    /**
     * Get the API command information about the command
     *
     * @return OperationInterface
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * {@inheritdoc}
     */
    public function setOnComplete($callable)
    {
        if (!is_callable($callable)) {
            throw new InvalidArgumentException('The onComplete function must be callable');
        }

        $this->onComplete = $callable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (!$this->/* Replaced /* Replaced /* Replaced client */ */ */) {
            throw new CommandException('A /* Replaced /* Replaced /* Replaced client */ */ */ must be associated with the command before it can be executed.');
        }

        return $this->/* Replaced /* Replaced /* Replaced client */ */ */->execute($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getClient()
    {
        return $this->/* Replaced /* Replaced /* Replaced client */ */ */;
    }

    /**
     * {@inheritdoc}
     */
    public function setClient(ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */)
    {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        if (!$this->request) {
            throw new CommandException('The command must be prepared before retrieving the request');
        }

        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse()
    {
        if (!$this->isExecuted()) {
            $this->execute();
        }

        return $this->request->getResponse();
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        if (!$this->isExecuted()) {
            $this->execute();
        }

        if (null === $this->result) {
            $this->process();
            // Call the onComplete method if one is set
            if ($this->onComplete) {
                call_user_func($this->onComplete, $this);
            }
        }

        return $this->result;
    }

    /**
     * {@inheritdoc}
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isPrepared()
    {
        return $this->request !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function isExecuted()
    {
        return $this->request !== null && $this->request->getState() == 'complete';
    }

    /**
     * {@inheritdoc}
     */
    public function prepare()
    {
        if (!$this->isPrepared()) {
            if (!$this->/* Replaced /* Replaced /* Replaced client */ */ */) {
                throw new CommandException('A /* Replaced /* Replaced /* Replaced client */ */ */ must be associated with the command before it can be prepared.');
            }

            // Notify subscribers of the /* Replaced /* Replaced /* Replaced client */ */ */ that the command is being prepared
            $this->/* Replaced /* Replaced /* Replaced client */ */ */->dispatch('command.before_prepare', array('command' => $this));

            // Fail on missing required arguments, and change parameters via filters
            $this->validate();
            // Delegate to the subclass that implements the build method
            $this->build();

            // Add custom request headers set on the command
            if ($headers = $this->get(self::HEADERS_OPTION)) {
                foreach ($headers as $key => $value) {
                    $this->request->setHeader($key, $value);
                }
            }

            // Add any curl options to the request
            if ($options = $this->get(Client::CURL_OPTIONS)) {
                $this->request->getCurlOptions()->merge(CurlHandle::parseCurlConfig($options));
            }

            // Set a custom response body
            if ($responseBody = $this->get(self::RESPONSE_BODY)) {
                $this->request->setResponseBody($responseBody);
            }
        }

        return $this->request;
    }

    /**
     * Set the validator used to validate and prepare command parameters and nested JSON schemas. If no validator is
     * set, then the command will validate using the default {@see SchemaValidator}.
     *
     * @param ValidatorInterface $validator Validator used to prepare and validate properties against a JSON schema
     *
     * @return self
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestHeaders()
    {
        return $this->get(self::HEADERS_OPTION);
    }

    /**
     * Initialize the command (hook that can be implemented in subclasses)
     */
    protected function init() {}

    /**
     * Create the request object that will carry out the command
     */
    abstract protected function build();

    /**
     * Hook used to create an operation for concrete commands that are not associated with a service description
     *
     * @return OperationInterface
     */
    protected function createOperation()
    {
        return new Operation(array('name' => get_class($this)));
    }

    /**
     * Create the result of the command after the request has been completed.
     * Override this method in subclasses to customize this behavior
     */
    protected function process()
    {
        $this->result = $this->get(self::RESPONSE_PROCESSING) != self::TYPE_RAW
            ? DefaultResponseParser::getInstance()->parse($this)
            : $this->request->getResponse();
    }

    /**
     * Validate and prepare the command based on the schema and rules defined by the command's Operation object
     *
     * @throws ValidationException when validation errors occur
     */
    protected function validate()
    {
        // Do not perform request validation/transformation if it is disable
        if ($this->get(self::DISABLE_VALIDATION)) {
            return;
        }

        $errors = array();
        $validator = $this->getValidator();
        foreach ($this->operation->getParams() as $name => $schema) {
            $value = $this->get($name);
            if (!$validator->validate($schema, $value)) {
                $errors = array_merge($errors, $validator->getErrors());
            } elseif ($value !== $this->get($name)) {
                // Update the config value if it changed and no validation errors were encountered
                $this->data[$name] = $value;
            }
        }

        if (!empty($errors)) {
            $e = new ValidationException('Validation errors: ' . implode("\n", $errors));
            $e->setErrors($errors);
            throw $e;
        }
    }

    /**
     * Get the validator used to prepare and validate properties. If no validator has been set on the command, then
     * the default {@see SchemaValidator} will be used.
     *
     * @return ValidatorInterface
     */
    protected function getValidator()
    {
        if (!$this->validator) {
            $this->validator = SchemaValidator::getInstance();
        }

        return $this->validator;
    }
}
