<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Subscriber;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\SubscriberInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface as Request;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Post\PostBodyInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Post\PostFile;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Post\PostFileInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\PrepareEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Description\Parameter;

/**
 * Subscriber used to create HTTP requests for commands based on a service
 * description.
 */
class PrepareRequest implements SubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return ['prepare' => ['onPrepare']];
    }

    public function onPrepare(PrepareEvent $event)
    {
        /* @var /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command */
        $command = $event->getCommand();
        /* @var /* Replaced /* Replaced /* Replaced Guzzle */ */ */ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */ */
        $/* Replaced /* Replaced /* Replaced client */ */ */ = $event->getClient();
        $request = $this->createRequest($command, $/* Replaced /* Replaced /* Replaced client */ */ */);
        $this->prepareRequest($command, $/* Replaced /* Replaced /* Replaced client */ */ */, $request);
        $event->setRequest($request);
    }

    /**
     * Create a request for the command and operation
     *
     * @param /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command Command being executed
     * @param /* Replaced /* Replaced /* Replaced Guzzle */ */ */ClientInterface  $/* Replaced /* Replaced /* Replaced client */ */ */  Client used to execute the command
     *
     * @return Request
     * @throws \RuntimeException
     */
    protected function createRequest(
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command,
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */
    ) {
        $operation = $command->getOperation();

        // If the command does not specify a template, then assume the base URL
        // of the /* Replaced /* Replaced /* Replaced client */ */ */
        if (null === ($uri = $operation->getUri())) {
            return $/* Replaced /* Replaced /* Replaced client */ */ */->getHttpClient()->createRequest(
                $operation->getHttpMethod(),
                $/* Replaced /* Replaced /* Replaced client */ */ */->getDescription()->getBaseUrl(),
                $command['request_options'] ?: []
            );
        }

        return $this->createCommandWithUri($command, $/* Replaced /* Replaced /* Replaced client */ */ */);
    }

    /**
     * Create a request for an operation with a uri merged onto a base URI
     */
    private function createCommandWithUri(
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command,
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */
    ) {
        // Get the path values and use the /* Replaced /* Replaced /* Replaced client */ */ */ config settings
        $variables = [];
        $operation = $command->getOperation();
        foreach ($operation->getParams() as $name => $arg) {
            /* @var Parameter $arg */
            if ($arg->getLocation() == 'uri') {
                if (isset($command[$name])) {
                    $variables[$name] = $arg->filter($command[$name]);
                    if (!is_array($variables[$name])) {
                        $variables[$name] = (string) $variables[$name];
                    }
                }
            }
        }

        return $/* Replaced /* Replaced /* Replaced client */ */ */->getHttpClient()->createRequest(
            $operation->getHttpMethod(),
            [$/* Replaced /* Replaced /* Replaced client */ */ */->getDescription()->getBaseUrl()->combine($operation->getUri()), $variables],
            $command['request_options'] ?: []
        );
    }

    protected function prepareRequest(
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command,
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        Request $request
    ) {
        static $methods;
        if (!$methods) {
            $methods = array_flip(get_class_methods(__CLASS__));
        }

        $context = ['command' => $command, '/* Replaced /* Replaced /* Replaced client */ */ */' => $/* Replaced /* Replaced /* Replaced client */ */ */];
        foreach ($command->getOperation()->getParams() as $name => $value) {
            /* @var Parameter $value */
            // Skip parameters that have not been set or are URI location
            if (!$command->hasParam($name) || $value->getLocation() == 'uri') {
                continue;
            }
            $method = 'visit_' . $value->getLocation();
            if (isset($methods[$method])) {
                $this->{$method}($request, $value, $command[$name], $context);
            } else {
                // @todo: Handle more complicated or custom locations somehow
            }
        }
    }

    /**
     * Adds a header to the request.
     */
    protected function visit_header(Request $request, Parameter $param, $value)
    {
        $request->setHeader($param->getWireName(), $param->filter($value));
    }

    /**
     * Adds a query string value to the request.
     */
    protected function visit_query(Request $request, Parameter $param, $value)
    {
        $request->setHeader($param->getWireName(), $this->prepValue($value, $param));
    }

    /**
     * Adds a body to the request.
     */
    protected function visit_body(Request $request, Parameter $param, $value)
    {
        $request->setBody(Stream::factory($param->filter($value)));
    }

    /**
     * Adds a POST field to the request.
     */
    protected function visit_postField(Request $request, Parameter $param, $value)
    {
        $body = $request->getBody();
        if (!($body instanceof PostBodyInterface)) {
            throw new \RuntimeException('Must be a POST body interface');
        }

        $body->setField($param->getWireName(), $this->prepValue($value, $param));
    }

    /**
     * Adds a POST file to the request.
     */
    protected function visit_postFile(Request $request, Parameter $param, $value)
    {
        $body = $request->getBody();
        if (!($body instanceof PostBodyInterface)) {
            throw new \RuntimeException('Must be a POST body interface');
        }

        $value = $param->filter($value);
        if (!($value instanceof PostFileInterface)) {
            $value = new PostFile($param->getWireName(), $value);
        }

        $body->addFile($value);
    }

    /**
     * Prepare (filter and set desired name for request item) the value for
     * request.
     *
     * @param mixed     $value
     * @param Parameter $param
     *
     * @return array|mixed
     */
    protected function prepValue($value, Parameter $param)
    {
        return is_array($value)
            ? $this->resolveRecursively($value, $param)
            : $param->filter($value);
    }

    /**
     * Recursively prepare and filter nested values.
     *
     * @param array     $value Value to map
     * @param Parameter $param Parameter related to the current key.
     *
     * @return array Returns the mapped array
     */
    protected function resolveRecursively(array $value, Parameter $param)
    {
        foreach ($value as $name => &$v) {
            switch ($param->getType()) {
                case 'object':
                    if ($subParam = $param->getProperty($name)) {
                        $key = $subParam->getWireName();
                        $value[$key] = $this->prepValue($v, $subParam);
                        if ($name != $key) {
                            unset($value[$name]);
                        }
                    } elseif ($param->getAdditionalProperties() instanceof Parameter) {
                        $v = $this->prepValue($v, $param->getAdditionalProperties());
                    }
                    break;
                case 'array':
                    if ($items = $param->getItems()) {
                        $v = $this->prepValue($v, $items);
                    }
                    break;
            }
        }

        return $param->filter($value);
    }
}
