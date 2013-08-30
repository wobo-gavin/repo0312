<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Header\HeaderCollection;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Header\HeaderFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Header\HeaderFactoryInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Header\HeaderInterface;

/**
 * Trait that implements HasHeadersInterface
 */
trait HasHeadersTrait
{
    /** @var HeaderCollection HTTP header collection */
    private $headers;

    /** @var HeaderFactoryInterface $headerFactory */
    private  $headerFactory;

    public function addHeader($header, $value = null)
    {
        if (isset($this->headers[$header])) {
            return $this->headers[$header]->add($value);
        } elseif ($value instanceof HeaderInterface) {
            return $this->headers[$header] = $value;
        } else {
            return $this->headers[$header] = $this->headerFactory->createHeader($header, $value);
        }
    }

    public function getHeader($header)
    {
        return $this->headers[$header];
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeader($header, $value = null)
    {
        unset($this->headers[$header]);

        return $this->addHeader($header, $value);
    }

    public function setHeaders(array $headers)
    {
        $this->headers->clear();
        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }

        return $this;
    }

    public function hasHeader($header)
    {
        return isset($this->headers[$header]);
    }

    public function removeHeader($header)
    {
        unset($this->headers[$header]);

        return $this;
    }
}
