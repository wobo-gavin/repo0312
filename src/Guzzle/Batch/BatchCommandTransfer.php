<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchTransferInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchDivisorInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Exception\InconsistentClientTransferException;

/**
 * Efficiently transfers multiple commands in parallel per /* Replaced /* Replaced /* Replaced client */ */ */
 * This class is to be used with {@see /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchInterface}
 */
class BatchCommandTransfer implements BatchTransferInterface, BatchDivisorInterface
{
    /** @var int Size of each command batch */
    protected $batchSize;

    /**
     * @param int $batchSize Size of each batch
     */
    public function __construct($batchSize = 50)
    {
        $this->batchSize = $batchSize;
    }

    /**
     * Creates batches by grouping commands by their associated /* Replaced /* Replaced /* Replaced client */ */ */
     * {@inheritdoc}
     */
    public function createBatches(\SplQueue $queue)
    {
        $groups = new \SplObjectStorage();
        foreach ($queue as $item) {
            if (!$item instanceof CommandInterface) {
                throw new InvalidArgumentException('All items must implement /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Service\Command\CommandInterface');
            }
            $/* Replaced /* Replaced /* Replaced client */ */ */ = $item->getClient();
            if (!$groups->contains($/* Replaced /* Replaced /* Replaced client */ */ */)) {
                $groups->attach($/* Replaced /* Replaced /* Replaced client */ */ */, new \ArrayObject(array($item)));
            } else {
                $groups[$/* Replaced /* Replaced /* Replaced client */ */ */]->append($item);
            }
        }

        $batches = array();
        foreach ($groups as $batch) {
            $batches = array_merge($batches, array_chunk($groups[$batch]->getArrayCopy(), $this->batchSize));
        }

        return $batches;
    }

    public function transfer(array $batch)
    {
        if (empty($batch)) {
            return;
        }

        // Get the /* Replaced /* Replaced /* Replaced client */ */ */ of the first found command
        $/* Replaced /* Replaced /* Replaced client */ */ */ = reset($batch)->getClient();

        // Keep a list of all commands with invalid /* Replaced /* Replaced /* Replaced client */ */ */s
        $invalid = array_filter($batch, function ($command) use ($/* Replaced /* Replaced /* Replaced client */ */ */) {
            return $command->getClient() !== $/* Replaced /* Replaced /* Replaced client */ */ */;
        });

        if (!empty($invalid)) {
            throw new InconsistentClientTransferException($invalid);
        }

        $/* Replaced /* Replaced /* Replaced client */ */ */->execute($batch);
    }
}
