<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream;

/**
 * Stream decorator trait implementing {@see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\WritableStreamInterface}
 */
trait WritableStreamDecoratorTrait
{
    use StreamDecoratorTrait;

    /**
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\WritableStreamInterface::write
     */
    public function write($string)
    {
        return $this->stream->write($string);
    }
}
