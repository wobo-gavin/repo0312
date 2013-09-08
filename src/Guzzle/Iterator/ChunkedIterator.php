<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Iterator;

/**
 * Pulls out chunks from an inner iterator and yields the chunks as arrays
 */
class ChunkedIterator extends \IteratorIterator
{
    /** @var int Size of each chunk */
    protected $chunkSize;

    /** @var array Current chunk */
    protected $chunk;

    /**
     * @param \Traversable $iterator  Traversable iterator
     * @param int          $chunkSize Size to make each chunk
     */
    public function __construct(\Traversable $iterator, $chunkSize)
    {
        parent::__construct($iterator);
        $this->chunkSize = $chunkSize;
    }

    public function rewind()
    {
        parent::rewind();
        $this->next();
    }

    public function next()
    {
        $this->chunk = array();
        for ($i = 0; $i < $this->chunkSize && parent::valid(); $i++) {
            $this->chunk[] = parent::current();
            parent::next();
        }
    }

    public function current()
    {
        return $this->chunk;
    }

    public function valid()
    {
        return !empty($this->chunk);
    }
}
