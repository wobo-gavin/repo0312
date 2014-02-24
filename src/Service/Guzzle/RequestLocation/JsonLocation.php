<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\RequestLocation;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Description\Operation;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Description\Parameter;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Service\/* Replaced /* Replaced /* Replaced Guzzle */ */ */\/* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Stream\Stream;

/**
 * Creates a JSON document
 */
class JsonLocation extends AbstractLocation
{
    /** @var bool Whether or not to add a Content-Type header when JSON is found */
    private $jsonContentType;

    /** @var array */
    private $jsonData;

    /**
     * @param string $contentType Content-Type header to add to the request if
     *     JSON is added to the body. Pass an empty string to omit.
     */
    public function __construct($contentType = 'application/json')
    {
        $this->jsonContentType = $contentType;
    }

    public function visit(
        RequestInterface $request,
        Parameter $param,
        $value,
        array $context
    ) {
        if (null === $this->jsonData) {
            $this->jsonData = [];
        }

        $this->jsonData[$param->getWireName()] = $this->prepareValue($value, $param);
    }

    public function after(
        /* Replaced /* Replaced /* Replaced Guzzle */ */ */CommandInterface $command,
        RequestInterface $request,
        Operation $operation,
        array $context
    ) {
        $data = $this->jsonData;
        $this->jsonData = null;

        // Add additional parameters to the JSON document
        $additional = $operation->getAdditionalParameters();
        if ($additional && $additional->getLocation() == $this->locationName) {
            foreach ($command->toArray() as $key => $value) {
                if (!$operation->hasParam($key)) {
                    $data[$key] = $this->prepareValue($value, $additional);
                }
            }
        }

        // Don't overwrite the Content-Type if one is set
        if ($this->jsonContentType && !$request->hasHeader('Content-Type')) {
            $request->setHeader('Content-Type', $this->jsonContentType);
        }

        $request->setBody(Stream::factory(json_encode($data)));
    }
}
