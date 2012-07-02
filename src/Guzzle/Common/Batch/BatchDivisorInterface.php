<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch;

/**
 * Interface used for dividing a queue of items into an array of batches
 */
interface BatchDivisorInterface
{
    /**
     * Divide a queue of items into an array batches
     *
     * @param \SplQueue $queue Queue of items to divide into batches. Items are
     *                         removed from the queue as they are iterated.
     *
     * @return array|\Traversable Returns an array or Traversable object that
     *                            contains arrays of items to transfer
     */
    function createBatches(\SplQueue $queue);
}
