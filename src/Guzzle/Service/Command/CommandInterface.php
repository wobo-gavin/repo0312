<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiCommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\CommandException;

/**
 * Command object to handle preparing and processing /* Replaced /* Replaced /* Replaced client */ */ */ requests and
 * responses of the requests
 */
interface CommandInterface
{
    /**
     * Specify a callable to execute when the command completes
     *
     * @param mixed $callable Callable to execute when the command completes.
     *     The callable must accept a {@see CommandInterface} object as the
     *     only argument.
     *
     * @return CommandInterface
     * @throws InvalidArgumentException
     */
    function setOnComplete($callable);

    /**
     * Get the short form name of the command
     *
     * @return string
     */
    function getName();

    /**
     * Get the API command information about the command
     *
     * @return ApiCommandInterface
     */
    function getApiCommand();

    /**
     * Execute the command and return the result
     *
     * @return mixed Returns the result of {@see CommandInterface::execute}ß
     * @throws CommandException if a /* Replaced /* Replaced /* Replaced client */ */ */ has not been associated with the command
     */
    function execute();

    /**
     * Get the /* Replaced /* Replaced /* Replaced client */ */ */ object that will execute the command
     *
     * @return ClientInterface|null
     */
    function getClient();

    /**
     * Set the /* Replaced /* Replaced /* Replaced client */ */ */ object that will execute the command
     *
     * @param ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */ The /* Replaced /* Replaced /* Replaced client */ */ */ object that will execute the command
     *
     * @return Command
     */
    function setClient(ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */);

    /**
     * Get the request object associated with the command
     *
     * @return RequestInterface
     * @throws CommandException if the command has not been executed
     */
    function getRequest();

    /**
     * Get the response object associated with the command
     *
     * @return Response
     * @throws CommandException if the command has not been executed
     */
    function getResponse();

    /**
     * Get the result of the command
     *
     * @return Response By default, commands return a Response
     *      object unless overridden in a subclass
     * @throws CommandException if the command has not been executed
     */
    function getResult();

    /**
     * Set the result of the command
     *
     * @param mixed $result Result to set
     *
     * @return self
     */
    function setResult($result);

    /**
     * Returns TRUE if the command has been prepared for executing
     *
     * @return bool
     */
    function isPrepared();

    /**
     * Returns TRUE if the command has been executed
     *
     * @return bool
     */
    function isExecuted();

    /**
     * Prepare the command for executing and create a request object.
     *
     * @return RequestInterface Returns the generated request
     * @throws CommandException if a /* Replaced /* Replaced /* Replaced client */ */ */ object has not been set previously
     *      or in the prepare()
     */
    function prepare();

    /**
     * Get the object that manages the request headers that will be set on any
     * outbound requests from the command
     *
     * @return Collection
     */
    function getRequestHeaders();
}
