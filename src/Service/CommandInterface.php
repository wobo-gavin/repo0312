<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\HasEmitterInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\ToArrayInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\Description\OperationInterface;

/**
 * A command object manages input and output of an operation using an
 * {@see OperationInterface} object.
 *
 * A command emits the following events:
 * - prepare: Emitted when the command is converting a command into a request
 * - process: Emitted when the command is processing a response
 * - error:   Emitted after an error occurs for a command.
 */
interface CommandInterface extends \ArrayAccess, ToArrayInterface, HasEmitterInterface
{
    /**
     * Get the API operation information about the command
     *
     * @return OperationInterface
     */
    public function getOperation();
}
