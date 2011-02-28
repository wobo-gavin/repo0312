<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription;

/**
 * Interface for building /* Replaced /* Replaced /* Replaced Guzzle */ */ */ commands based on a service document.
 *
 * This class handles building commands based on a service document, injecting
 * configuration data with values from the service doc, and validating that
 * the commands being built meet the criteria specified in the service doc.
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
interface CommandFactoryInterface
{
    /**
     * Create a new command factory
     *
     * @param ServiceDescription $service Service description describing the service
     */
    public function __construct(ServiceDescription $service);

    /**
     * Build a webservice command based on the service document
     *
     * @param string $command Name of the command to retrieve
     * @param array $args (optional) Arguments to pass to the command
     *
     * @return CommandInterface
     *
     * @throws InvalidArgumentException if the command was not found for the service
     */
    public function buildCommand($name, array $args = array());
}