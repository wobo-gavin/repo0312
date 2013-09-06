<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Php;

/**
 * Trait implementing {@see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\WritableStreamInterface}
 */
trait WritableTrait
{
    /**
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\WritableStreamInterface::write
     */
    public function write($string)
    {
        // We can't know the size after writing anything
        $this->size = null;

        return fwrite($this->stream, $string);
    }
}
