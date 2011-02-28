<?php
/**
 * @package /* Replaced /* Replaced /* Replaced Guzzle */ */ */ PHP <http://www./* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 * @license See the LICENSE file that was distributed with this source code.
 */

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Collection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ApiCommand;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\ServiceDescription;

/**
 * Build /* Replaced /* Replaced /* Replaced Guzzle */ */ */ commands based on a service document using concrete classes for
 * each command.
 * 
 * @author Michael Dowling <michael@/* Replaced /* Replaced /* Replaced guzzle */ */ */php.org>
 */
class ConcreteCommandFactory extends AbstractCommandFactory
{
    /**
     * {@inheritdoc}
     */
    protected function createCommand(ApiCommand $command, Collection $args)
    {
        $class = $command->getConcreteClass();
        
        return new $class($args->getAll(), $command);
    }
}