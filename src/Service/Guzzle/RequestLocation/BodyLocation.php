<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\RequestLocation;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream;

/**
 * Adds a body to a request
 */
class BodyLocation extends AbstractLocation
{
    public function visit(
        RequestInterface $request,
        Parameter $param,
        $value,
        array $context
    ) {
        $request->setBody(Stream::factory($param->filter($value)));
    }
}
