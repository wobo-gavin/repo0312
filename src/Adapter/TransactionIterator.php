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

    /** @var ClientInterface */
    private $/* Replaced /* Replaced /* Replaced client */ */ */;

    /** @var array of hashes containing 'name', 'fn', 'priority', and 'once' */
    private $eventListeners;

    public function __construct(
        $source, ClientInterface $/* Replaced /* Replaced /* Replaced client */ */ */,
        array $options
    ) {
        $this->/* Replaced /* Replaced /* Replaced client */ */ */ = $/* Replaced /* Replaced /* Replaced client */ */ */;
        $this->configureEvents($options);
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

        if ($this->eventListeners) {
            $emitter = $request->getEmitter();
            foreach ($this->eventListeners as $ev) {
                if ($ev['once']) {
                    $emitter->once($ev['name'], $ev['fn'], $ev['priority']);
                } else {
                    $emitter->on($ev['name'], $ev['fn'], $ev['priority']);
                }
            }
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

    private function configureEvents(array $options)
    {
        static $namedEvents = ['before', 'complete', 'error'];

        foreach ($namedEvents as $event) {
            if (isset($options[$event])) {
                if (is_callable($options[$event])) {
                    $this->eventListeners[] = [
                        'name'     => $event,
                        'fn'       => $options[$event],
                        'priority' => 0,
                        'once'     => false
                    ];
                } else {
                    $this->addEvent($event, $options[$event]);
                }
            }
        }
    }

    private function addEvent($eventName, $event)
    {
        static $default = ['priority' => 0, 'once' => false];

        if (!is_array($event)) {
            throw new \InvalidArgumentException('Each event listener must be a'
                . ' callable or an array of associative arrays where each'
                . ' associative array contains a "fn" key.');
        }

        if (isset($event['fn'])) {
            $event['name'] = $eventName;
            $this->eventListeners[] = $event + $default;
        } else {
            foreach ($event as $e) {
                $this->addEvent($eventName, $e);
            }
        }
    }
}
