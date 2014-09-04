<?php
namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Event\ListenerAttacherTrait;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */Http\Message\RequestInterface;

/**
 * Converts a sequence of request objects into a transaction.
 * @internal
 */
class TransactionIterator implements \Iterator
{
    use ListenerAttacherTrait;

    /** @var \Iterator */
    private $source;

    /** @var ClientInterface */
    private $/* Replaced /* Replaced /* Replaced client */ */ */;

    /** @var array Listeners to attach to each request */
    private $eventListeners = [];

    public function __construct(
        $source,
        ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        array $options
    ) {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->eventListeners = $this->prepareListeners(
            $options,
            ['before', 'complete', 'error', 'prepare']
        );
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

        $this->attachListeners($request, $this->eventListeners);

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

    public function rewind()
    {
        if (!($this->source instanceof \Generator)) {
            $this->source->rewind();
        }
    }
}
