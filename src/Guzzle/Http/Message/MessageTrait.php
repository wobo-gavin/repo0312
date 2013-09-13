<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Mimetypes;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Stream;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface;

/**
 * HTTP request/response message trait
 */
trait MessageTrait
{
    use HasHeadersTrait;

    /** @var StreamInterface Message body */
    private $body;

    /** @var string HTTP protocol version of the message */
    private $protocolVersion = '1.1';

    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    public function setBody($body)
    {
        if ($body === null) {
            // Setting a null body will remove the body of the request
            $this->body = null;
            $this->removeHeader('Content-Length');
            $this->removeHeader('Transfer-Encoding');
        } else {
            $this->body = Stream::factory($body);
            // Auto detect the Content-Type from the body if possible
            if (!$this->hasHeader('Content-Type')) {
                $contentType = Mimetypes::getInstance()->fromFilename($this->body->getUri());
                $this->setHeader('Content-Type', $contentType);
            }
            // Set the Content-Length header if it can be determined
            $size = $this->body->getSize();
            if ($size !== null && $size !== false) {
                $this->setHeader('Content-Length', $size);
            }
        }

        return $this;
    }

    private function initializeMessage(array $options = [])
    {
        if (isset($options['protocol_version'])) {
            $this->protocolVersion = $options['protocol_version'];
        }

        $this->headers = new HeaderCollection();
    }
}
