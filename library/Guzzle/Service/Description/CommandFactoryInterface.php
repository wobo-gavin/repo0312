<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description;

/**
 * Interface for building /* Replaced /* Replaced /* Replaced Guzzle */ */ */ commands.
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
interface CommandFactoryInterface
{
    /**
     * Build a webservice command by name based on a service description
     *
     * @param ApiCommand $command Description of the command to create
     * @param array $args (optional) Arguments to pass to the command
     *
     * @return /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface
     * @throws InvalidArgumentException if the command was not found
     */
    public function createCommand(ApiCommand $command, array $args);
}