<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchTransferInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchDivisorInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface;

/**
 * Batch transfer strategy used to efficiently transfer a batch of requests.
 * This class is to be used with {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchInterface}
 */
class BatchRequestTransfer implements BatchTransferInterface, BatchDivisorInterface
{
    /** @var int Size of each command batch */
    protected $batchSize;

    /**
     * Constructor used to specify how large each batch should be
     *
     * @param int $batchSize Size of each batch
     */
    public function __construct($batchSize = 50)
    {
        $this->batchSize = $batchSize;
    }

    /**
     * Creates batches of requests by grouping requests by their associated curl multi object.
     * {@inheritdoc}
     */
    public function createBatches(\SplQueue $queue)
    {
        // Create batches by /* Replaced /* Replaced /* Replaced client */ */ */ objects
        $groups = new \SplObjectStorage();
        foreach ($queue as $item) {
            if (!$item instanceof RequestInterface) {
                throw new InvalidArgumentException('All items must implement /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Http\Message\RequestInterface');
            }
            $/* Replaced /* Replaced /* Replaced client */ */ */ = $item->getClient();
            if (!$groups->contains($/* Replaced /* Replaced /* Replaced client */ */ */)) {
                $groups->attach($/* Replaced /* Replaced /* Replaced client */ */ */, array($item));
            } else {
                $current = $groups[$/* Replaced /* Replaced /* Replaced client */ */ */];
                $current[] = $item;
                $groups[$/* Replaced /* Replaced /* Replaced client */ */ */] = $current;
            }
        }

        $batches = array();
        foreach ($groups as $batch) {
            $batches = array_merge($batches, array_chunk($groups[$batch], $this->batchSize));
        }

        return $batches;
    }

    public function transfer(array $batch)
    {
        if ($batch) {
            reset($batch)->getClient()->send($batch);
        }
    }
}
