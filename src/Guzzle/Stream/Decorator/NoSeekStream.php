<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Decorator;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\ReadableStreamInterface;

/**
 * Stream decorator that prevents a readable stream from being seeked
 */
class NoSeekStream implements ReadableStreamInterface
{
    use ReadableStreamDecoratorTrait;

    public function seek($offset, $whence = SEEK_SET)
    {
        return false;
    }

    public function isSeekable()
    {
        return false;
    }
}
