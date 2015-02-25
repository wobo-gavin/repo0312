<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception;

use Psr\Http\Message\StreamableInterface;

/**
 * Exception thrown when a seek fails on a stream.
 */
class SeekException extends \RuntimeException
{
    private $stream;

    public function __construct(StreamableInterface $stream, $pos = 0, $msg = '')
    {
        $this->stream = $stream;
        $msg = $msg ?: 'Could not seek the stream to position ' . $pos;
        parent::__construct($msg);
    }

    /**
     * @return StreamableInterface
     */
    public function getStream()
    {
        return $this->stream;
    }
}
