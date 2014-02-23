<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Description\Operation;

/**
 * Represents a command that is sent using a /* Replaced /* Replaced /* Replaced Guzzle */ */ */ service description.
 */
interface /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface extends CommandInterface
{
    /**
     * Returns the API description operation associated with the command
     *
     * @return Operation
     */
    public function getOperation();
}
