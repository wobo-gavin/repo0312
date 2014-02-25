<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Subscriber;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\SubscriberInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\RequestLocation\BodyLocation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\RequestLocation\HeaderLocation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\RequestLocation\JsonLocation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\RequestLocation\PostFieldLocation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\RequestLocation\PostFileLocation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\RequestLocation\QueryLocation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\RequestLocation\XmlLocation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\PrepareEvent;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\RequestLocation\RequestLocationInterface;

/**
 * Subscriber used to create HTTP requests for commands based on a service
 * description.
 */
class PrepareRequest implements SubscriberInterface
{
    /** @var RequestLocationInterface[] */
    private $requestLocations;

    public static function getSubscribedEvents()
    {
        return ['prepare' => ['onPrepare']];
    }

    /**
     * @param RequestLocationInterface[] $requestLocations Extra request locations
     */
    public function __construct(array $requestLocations = [])
    {
        static $defaultRequestLocations;
        if (!$defaultRequestLocations) {
            $defaultRequestLocations = [
                'body'      => new BodyLocation('body'),
                'query'     => new QueryLocation('query'),
                'header'    => new HeaderLocation('header'),
                'json'      => new JsonLocation('json'),
                'xml'       => new XmlLocation('xml'),
                'postField' => new PostFieldLocation('postField'),
                'postFile'  => new PostFileLocation('postFile')
            ];
        }

        $this->requestLocations = $requestLocations + $defaultRequestLocations;
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
     * Prepares a request for sending using location visitors
     *
     * @param /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command Command to prepare
     * @param /* Replaced /* Replaced /* Replaced Guzzle */ */ */ClientInterface  $/* Replaced /* Replaced /* Replaced client */ */ */  Client that owns the command
     * @param RequestInterface       $request Request being created
     * @throws \RuntimeException If a location cannot be handled
     */
    protected function prepareRequest(
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command,
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        RequestInterface $request
    ) {
        $visitedLocations = [];
        $context = ['/* Replaced /* Replaced /* Replaced client */ */ */' => $/* Replaced /* Replaced /* Replaced client */ */ */, 'command' => $command];
        $operation = $command->getOperation();

        // Visit each actual parameter
        foreach ($operation->getParams() as $name => $param) {
            /* @var Parameter $param */
            $location = $param->getLocation();
            // Skip parameters that have not been set or are URI location
            if ($location == 'uri' || !$command->hasParam($name)) {
                continue;
            }
            if (!isset($this->requestLocations[$location])) {
                throw new \RuntimeException("No location registered for $location");
            }
            $visitedLocations[$location] = true;
            $this->requestLocations[$location]->visit(
                $command,
                $request,
                $param,
                $context
            );
        }

        // Ensure that the after() method is invoked for additionalParameters
        if ($additional = $operation->getAdditionalParameters()) {
            $visitedLocations[$additional->getLocation()] = true;
        }

        // Call the after() method for each visited location
        foreach (array_keys($visitedLocations) as $location) {
            $this->requestLocations[$location]->after(
                $command,
                $request,
                $operation,
                $context
            );
        }
    }

    /**
     * Create a request for the command and operation
     *
     * @param /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command Command being executed
     * @param /* Replaced /* Replaced /* Replaced Guzzle */ */ */ClientInterface  $/* Replaced /* Replaced /* Replaced client */ */ */  Client used to execute the command
     *
     * @return RequestInterface
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
}
