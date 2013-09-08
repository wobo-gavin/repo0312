<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream;

/**
 * Stread decorator that prevents a stream from being seeked
 */
class NoSeekStream implements StreamInterface
{
    use StreamDecoratorTrait;

    public function seek($offset, $whence = SEEK_SET)
    {
        return false;
    }

    public function isSeekable()
    {
        return false;
    }
}
