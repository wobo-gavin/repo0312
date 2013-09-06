<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream;

/**
 * Stream decorator trait implementing {@see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\ReadableStreamInterface}
 */
trait ReadableStreamDecoratorTrait
{
    use StreamDecoratorTrait;

    /**
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\ReadableStreamInterface::__toString
     */
    public function __toString()
    {
        $buffer = '';

        try {
            if ($this->seek(0)) {
                while (!$this->eof()) {
                    $buffer .= $this->read(32768);
                }
                $this->seek(0);
            }
        } catch (\Exception $e) {
            // Really, PHP? https://bugs.php.net/bug.php?id=53648
            trigger_error(__METHOD__ . ' exception: ' . (string) $e, E_USER_ERROR);
        }

        return $buffer;
    }

    /**
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\ReadableStreamInterface::eof
     */
    public function eof()
    {
        return $this->stream->eof();
    }

    /**
     * @see \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\ReadableStreamInterface::read
     */
    public function read($length)
    {
        return $this->stream->read($length);
    }
}
