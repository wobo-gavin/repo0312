<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Exception;

use Psr\Http\Message\StreamInterface;

/**
 * Exception thrown when a seek fails on a stream.
 */
class SeekException extends \RuntimeException implements /* Replaced /* Replaced /* Replaced Guzzle */ */ */Exception
{
    private $stream;

    public function __construct(StreamInterface $stream, $pos = 0, $msg = '')
    {
        $this->stream = $stream;
        $msg = $msg ?: 'Could not seek the stream to position ' . $pos;
        parent::__construct($msg);
    }

    /**
     * @return StreamInterface
     */
    public function getStream()
    {
        return $this->stream;
    }
}
