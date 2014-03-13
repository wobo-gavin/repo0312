<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Adapter;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\ClientInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;

/**
 * Converts a sequence of request objects into a transaction.
 * @internal
 */
class TransactionIterator implements \Iterator
{
    /** @var \Iterator */
    private $source;

    /** @var array */
    private $options;

    /** @var ClientInterface */
    private $/* Replaced /* Replaced /* Replaced client */ */ */;

    public function __construct(
        $source, ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        array $options
    ) {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->options = $options;
        if ($source instanceof \Iterator) {
            $this->source = $source;
        } elseif (is_array($source)) {
            $this->source = new \ArrayIterator($source);
        } else {
            throw new \InvalidArgumentException('Expected an Iterator or array');
        }
    }

    public function current()
    {
        $request = $this->source->current();

        if (!$request instanceof RequestInterface) {
            throw new \RuntimeException('All must implement RequestInterface');
        }

        if (isset($this->options['before'])) {
            $request->getEmitter()->on('before', $this->options['before'], -255);
        }

        if (isset($this->options['complete'])) {
            $request->getEmitter()->on('complete', $this->options['complete'], -255);
        }

        if (isset($this->options['error'])) {
            $request->getEmitter()->on('error', $this->options['error'], -255);
        }

        return new Transaction($this->/* Replaced /* Replaced /* Replaced client */ */ */, $request);
    }

    public function next()
    {
        $this->source->next();
    }

    public function key()
    {
        return $this->source->key();
    }

    public function valid()
    {
        return $this->source->valid();
    }

    public function rewind() {}
}
