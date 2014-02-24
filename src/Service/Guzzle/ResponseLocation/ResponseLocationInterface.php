<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\ResponseLocation;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface;

/**
 * Location visitor used to parse values out of a response into an associative
 * array
 */
interface ResponseLocationInterface
{
    /**
     * Called before visiting all parameters. This can be used for seeding the
     * result of a command with default data (e.g. populating with JSON data in
     * the response then adding to the parsed data).
     *
     * @param /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command  Command being visited
     * @param ResponseInterface      $response Response being visited
     * @param Parameter              $model    Response model
     * @param mixed                  $result   Result associative array value
     *                                         being updated by reference.
     * @param array                  $context  Parsing context
     */
    public function before(
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command,
        ResponseInterface $response,
        Parameter $model,
        &$result,
        array $context = []
    );

    /**
     * Called after visiting all parameters
     *
     * @param /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command  Command being visited
     * @param ResponseInterface      $response Response being visited
     * @param Parameter              $model    Response model
     * @param mixed                  $result   Result associative array value
     *                                         being updated by reference.
     * @param array                  $context  Parsing context
     */
    public function after(
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command,
        ResponseInterface $response,
        Parameter $model,
        &$result,
        array $context = []
    );

    /**
     * Called once for each parameter being visited that matches the location
     * type.
     *
     * @param /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command  Command being visited
     * @param ResponseInterface      $response Response being visited
     * @param Parameter              $param    Parameter being visited
     * @param mixed                  $result   Result associative array value
     *                                         being updated by reference.
     * @param array                  $context  Parsing context
     */
    public function visit(
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command,
        ResponseInterface $response,
        Parameter $param,
        &$result,
        array $context = []
    );
}
