<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\LocationVisitor;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Description\ApiParam;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;

/**
 * Location visitor used to add values to different locations in a request with different behaviors as needed
 */
interface LocationVisitorInterface
{
    /**
     * Called after visiting all parameters
     *
     * @param CommandInterface $command Command being prepared
     * @param RequestInterface $request Request being prepared
     */
    public function after(CommandInterface $command, RequestInterface $request);

    /**
     * Called once for each parameter being visited that matches the location type
     *
     * @param CommandInterface $command Command being prepared
     * @param RequestInterface $request Request being prepared
     * @param string           $key     Location key
     * @param string           $value   Value to set
     * @param ApiParam         $param   Parameter being visited
     */
    public function visit(CommandInterface $command, RequestInterface $request, $key, $value, ApiParam $param = null);
}
