<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\Decorator;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\StreamFactory;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\ReadableStreamInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream\DuplexStreamInterface;

/**
 * Stream decorator that can cache previously read bytes from a sequentially read stream
 */
class CachingStream implements ReadableStreamInterface
{
    use ReadableStreamDecoratorTrait;

    /** @var ReadableStreamInterface Remote stream used to actually pull data onto the buffer */
    private $remoteStream;

    /** @var int The number of bytes to skip reading due to a write on the temporary buffer */
    private $skipReadBytes = 0;

    /**
     * We will treat the buffer object as the body of the stream
     *
     * @param ReadableStreamInterface $stream Stream to cache
     * @param DuplexStreamInterface   $target Optionally specify where data is cached
     */
    public function __construct(
        ReadableStreamInterface $stream,
        DuplexStreamInterface $target = null
    ) {
        $this->remoteStream = $stream;
        $this->stream = $target ?: StreamFactory::create(fopen('php://temp', 'r+'));
    }

    public function getSize()
    {
        return max($this->stream->getSize(), $this->remoteStream->getSize());
    }

    /**
     * {@inheritdoc}
     * @throws \RuntimeException When seeking with SEEK_END or when seeking past the total size of the buffer stream
     */
    public function seek($offset, $whence = SEEK_SET)
    {
        if ($whence == SEEK_SET) {
            $byte = $offset;
        } elseif ($whence == SEEK_CUR) {
            $byte = $offset + $this->tell();
        } else {
            throw new \RuntimeException(__CLASS__ . ' supports only SEEK_SET and SEEK_CUR seek operations');
        }

        // You cannot skip ahead past where you've read from the remote stream
        if ($byte > $this->stream->getSize()) {
            throw new \RuntimeException(
                "Cannot seek to byte {$byte} when the buffered stream only contains {$this->stream->getSize()} bytes"
            );
        }

        return $this->stream->seek($byte);
    }

    public function read($length)
    {
        // Perform a regular read on any previously read data from the buffer
        $data = $this->stream->read($length);
        $remaining = $length - strlen($data);

        // More data was requested so read from the remote stream
        if ($remaining) {
            // If data was written to the buffer in a position that would have been filled from the remote stream,
            // then we must skip bytes on the remote stream to emulate overwriting bytes from that position. This
            // mimics the behavior of other PHP stream wrappers.
            $remoteData = $this->remoteStream->read($remaining + $this->skipReadBytes);

            if ($this->skipReadBytes) {
                $len = strlen($remoteData);
                $remoteData = substr($remoteData, $this->skipReadBytes);
                $this->skipReadBytes = max(0, $this->skipReadBytes - $len);
            }

            $data .= $remoteData;
            $this->stream->write($remoteData);
        }

        return $data;
    }

    public function eof()
    {
        return $this->stream->eof() && $this->remoteStream->eof();
    }

    /**
     * Close both the remote stream and buffer stream
     */
    public function close()
    {
        $this->remoteStream->close() && $this->stream->close();
    }
}
