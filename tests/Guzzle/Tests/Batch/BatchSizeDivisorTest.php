<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Batch;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchSizeDivisor;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchSizeDivisor
 */
class BatchSizeDivisorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testDividesBatch()
    {
        $queue = new \SplQueue();
        $queue[] = 'foo';
        $queue[] = 'baz';
        $queue[] = 'bar';
        $d = new BatchSizeDivisor(3);
        $this->assertEquals(3, $d->getSize());
        $d->setSize(2);
        $batches = $d->createBatches($queue);
        $this->assertEquals(array(array('foo', 'baz'), array('bar')), $batches);
    }
}
