<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Decorator;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface;

/**
 * Stream decorator trait implementing {@see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface}
 */
trait StreamDecoratorTrait
{
    /** @var StreamInterface Decorated stream */
    private $stream;

    /**
     * Allow decorators to implement custom methods
     *
     * @param string $method Missing method name
     * @param array  $args   Method arguments
     *
     * @return mixed
     */
    public function __call($method, array $args)
    {
        return call_user_func_array(array($this->stream, $method), $args);
    }

    /**
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface::close
     */
    public function close()
    {
        return $this->stream->close();
    }

    /**
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface::getMetadata
     */
    public function getMetadata($key = null)
    {
        return $this->stream->getMetadata($key);
    }

    /**
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface::detach
     */
    public function detach()
    {
        $this->stream->detach();

        return $this;
    }

    /**
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface::getSize
     */
    public function getSize()
    {
        return $this->stream->getSize();
    }

    /**
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface::tell
     */
    public function tell()
    {
        return $this->stream->tell();
    }

    /**
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface::seek
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        return $this->stream->seek($offset, $whence);
    }

    /**
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamInterface::isSeekable
     */
    public function isSeekable()
    {
        return $this->stream->isSeekable();
    }
}
