<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\RequestLocation;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Description\Operation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;

/**
 * Handles locations specified in a service description
 */
interface RequestLocationInterface
{
    /**
     * Visits a location for each top-level parameter
     *
     * @param /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command Command being prepared
     * @param RequestInterface       $request Request being modified
     * @param Parameter              $param   Parameter being visited
     * @param array                  $context Associative array containing a
     *     '/* Replaced /* Replaced /* Replaced client */ */ */' key referencing the /* Replaced /* Replaced /* Replaced client */ */ */ that created the command.
     */
    public function visit(
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command,
        RequestInterface $request,
        Parameter $param,
        array $context
    );

    /**
     * Called when all of the parameters of a command have been visited.
     *
     * @param /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command   Command being prepared
     * @param RequestInterface       $request   Request being modified
     * @param Operation              $operation Operation being serialized
     * @param array                  $context   Associative array containing a
     *     '/* Replaced /* Replaced /* Replaced client */ */ */' key referencing the /* Replaced /* Replaced /* Replaced client */ */ */ that created the command.
     */
    public function after(
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command,
        RequestInterface $request,
        Operation $operation,
        array $context
    );
}
