<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\NullObject;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\ClientInterface as HttpClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandSet;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ServiceDescription;

/**
 * Client interface for executing commands on a web service.
 */
interface ClientInterface extends HttpClientInterface
{
    /**
     * Basic factory method to create a new /* Replaced /* Replaced /* Replaced client */ */ */.  Extend this method in
     * subclasses to build more complex /* Replaced /* Replaced /* Replaced client */ */ */s.
     *
     * @param array|Collection $config (optional) Configuartion data
     *
     * @return ClientInterface
     */
    static function factory($config);

    /**
     * Get a command by name.  First, the /* Replaced /* Replaced /* Replaced client */ */ */ will see if it has a service
     * description and if the service description defines a command by the
     * supplied name.  If no dynamic command is found, the /* Replaced /* Replaced /* Replaced client */ */ */ will look for
     * a concrete command class exists matching the name supplied.  If neither
     * are found, an InvalidArgumentException is thrown.
     *
     * @param string $name Name of the command to retrieve
     * @param array $args (optional) Arguments to pass to the command
     *
     * @return CommandInterface
     * @throws \InvalidArgumentException if no command can be found by name
     */
    function getCommand($name, array $args = array());

    /**
     * Execute a command and return the response
     *
     * @param CommandInterface|CommandSet $command The command or set to execute
     *
     * @return mixed Returns the result of the executed command's
     *       {@see CommandInterface::getResult} method if a CommandInterface is
     *       passed, or the CommandSet itself if a CommandSet is passed
     * @throws \InvalidArgumentException if an invalid command is passed
     * @throws Command\CommandSetException if a set contains commands associated
     *      with other /* Replaced /* Replaced /* Replaced client */ */ */s
     */
    function execute($command);

    /**
     * Set the service description of the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @param ServiceDescription $service Service description that describes
     *      all of the commands and information of the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return ClientInterface
     */
    function setDescription(ServiceDescription $service);

    /**
     * Get the service description of the /* Replaced /* Replaced /* Replaced client */ */ */
     *
     * @return ServiceDescription|NullObject
     */
    function getDescription();
}