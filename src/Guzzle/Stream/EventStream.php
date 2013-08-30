<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Stream;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasDispatcherTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\HasDispatcherInterface;

/**
 * Stream decorator that emits events for read and write methods
 */
class EventStream implements StreamInterface, HasDispatcherInterface
{
    use StreamDecorator, HasDispatcherTrait;

    public function read($length)
    {
        $event = ['stream' => $this, 'length' => $length, 'data' => $this->stream->read($length)];
        $this->dispatch('stream.read', $event);

        if ($this->eof()) {
            $this->dispatch('stream.eof', ['stream' => $this]);
        }

        return $event['data'];
    }

    public function write($string)
    {
        $event = ['stream' => $this, 'length' => $this->stream->write($string), 'data' => $string];
        $this->dispatch('stream.write', $event);

        return $event['length'];
    }
}
