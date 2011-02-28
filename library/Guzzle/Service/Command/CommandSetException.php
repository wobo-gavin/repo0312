<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceException;

/**
 * Command Set exception
 *
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class CommandSetException extends ServiceException
{
    /**
     * @var array Commands with an invalid /* Replaced /* Replaced /* Replaced client */ */ */
     */
    private $invalidCommands = array();

    /**
     * Get the invalid commands in the CommandSet
     *
     * @return array
     */
    public function getInvalidCommands()
    {
        return $this->invalidCommands;
    }

    /**
     * Set the invalid commands in the CommandSet
     *
     * @param array $commands Array of Command objects
     */
    public function setInvalidCommands(array $commands)
    {
        $this->invalidCommands = $commands;
    }
}