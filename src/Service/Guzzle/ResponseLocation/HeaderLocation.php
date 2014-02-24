<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\ResponseLocation;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\ResponseInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface;

/**
 * Extracts headers from the response into a result fields
 */
class HeaderLocation extends AbstractLocation
{
    public function visit(
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command,
        ResponseInterface $response,
        Parameter $param,
        &$result,
        array $context = []
    ) {
        // Retrieving a single header by name
        $name = $param->getName();
        if ($header = $response->getHeader($param->getWireName())) {
            $result[$name] = $param->filter($header);
        }
    }

    public function after(
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command,
        ResponseInterface $response,
        Parameter $model,
        &$result,
        array $context = []
    ) {
        $additional = $model->getAdditionalProperties();
        if ($additional instanceof Parameter &&
            $additional->getLocation() == $this->locationName
        ) {
            foreach ($response->getHeaders() as $key => $header) {
                if (!isset($result[$key])) {
                    $result[$key] = $additional->filter(implode($header, ', '));
                }
            }
        }
    }
}
