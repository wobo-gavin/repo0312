<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Php;

/**
 * Trait implementing {@see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\ReadableStreamInterface}
 */
trait ReadableTrait
{
    abstract function isSeekable();
    abstract function seek($offset, $whence = 0);
    abstract function tell();

    /**
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\ReadableStreamInterface::__toString
     */
    public function __toString()
    {
        if (!$this->isSeekable() && $this->eof()) {
            return '';
        }

        $originalPos = $this->tell();
        $body = stream_get_contents($this->stream, -1, 0);
        $this->seek($originalPos);

        return $body;
    }

    /**
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\ReadableStreamInterface::eof
     */
    public function eof()
    {
        return feof($this->stream);
    }

    /**
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\ReadableStreamInterface::read
     */
    public function read($length)
    {
        return fread($this->stream, $length);
    }
}
