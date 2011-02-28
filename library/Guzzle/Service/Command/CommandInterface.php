<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Filter\Chain;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\Response;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Client;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandException;

/**
 * Command object to handle preparing and processing /* Replaced /* Replaced /* Replaced client */ */ */ requests and
 * responses of the requests
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
interface CommandInterface
{
    /**
     * Constructor
     *
     * @param array|Collection $parameters (optional) Collection of parameters
     *      to set on the command
     */
    public function __construct($parameters = null);

    /**
     * Get whether or not the command can be batched
     *
     * @return bool
     */
    public function canBatch();

    /**
     * Execute the command
     *
     * @return Command
     * @throws CommandException if a /* Replaced /* Replaced /* Replaced client */ */ */ has not been associated with the command
     */
    public function execute();

    /**
     * Get the /* Replaced /* Replaced /* Replaced client */ */ */ object that will execute the command
     *
     * @return Client|null
     */
    public function getClient();

    /**
     * Get the request object associated with the command
     *
     * @return RequestInterface
     * @throws CommandException if the command has not been executed
     */
    public function getRequest();

    /**
     * Get the response object associated with the command
     *
     * @return Response
     * @throws CommandException if the command has not been executed
     */
    public function getResponse();

    /**
     * Get the result of the command
     *
     * @return Response By default, commands return a Response
     *      object unless overridden in a subclass
     * @throws CommandException if the command has not been executed
     */
    public function getResult();

    /**
     * Returns TRUE if the command has been prepared for executing
     *
     * @return bool
     */
    public function isPrepared();

    /**
     * Returns TRUE if the command has been executed
     *
     * @return bool
     */
    public function isExecuted();

    /**
     * Prepare the command for executing.
     *
     * Create a request object for the command.
     *
     * @param Client $/* Replaced /* Replaced /* Replaced client */ */ */ (optional) The /* Replaced /* Replaced /* Replaced client */ */ */ object used to execute the command
     *
     * @return Command Provides a fluent interface.
     * @throws CommandException if a /* Replaced /* Replaced /* Replaced client */ */ */ object has not been set previously
     *      or in the prepare()
     */
    public function prepare(Client $/* Replaced /* Replaced /* Replaced client */ */ */ = null);

    /**
     * Set the /* Replaced /* Replaced /* Replaced client */ */ */ objec that will execute the command
     *
     * @param Client $/* Replaced /* Replaced /* Replaced client */ */ */ The /* Replaced /* Replaced /* Replaced client */ */ */ objec that will execute the command
     *
     * @return Command
     */
    public function setClient(Client $/* Replaced /* Replaced /* Replaced client */ */ */);

    /**
     * Set an HTTP header on the outbound request
     *
     * @param string $header The name of the header to set
     * @param string $value The value to set on the header
     *
     * @return AbstractCommand
     */
    public function setRequestHeader($header, $value);

    /**
     * Get the object that manages the request headers
     *
     * @return Collection
     */
    public function getRequestHeaders();
}