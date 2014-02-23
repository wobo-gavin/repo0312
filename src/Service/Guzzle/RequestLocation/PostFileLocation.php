<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\RequestLocation;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Post\PostBodyInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Post\PostFileInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Post\PostFile;

/**
 * Adds POST files to a request
 */
class PostFileLocation extends AbstractLocation
{
    public function visit(
        RequestInterface $request,
        Parameter $param,
        $value,
        array $context
    ) {
        $body = $request->getBody();
        if (!($body instanceof PostBodyInterface)) {
            throw new \RuntimeException('Must be a POST body interface');
        }

        $value = $param->filter($value);
        if (!($value instanceof PostFileInterface)) {
            $value = new PostFile($param->getWireName(), $value);
        }

        $body->addFile($value);
    }
}
