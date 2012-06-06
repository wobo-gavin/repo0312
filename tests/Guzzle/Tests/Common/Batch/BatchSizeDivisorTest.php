<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchSizeDivisor;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchSizeDivisor
 */
class BatchSizeDivisorTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testDividesBatch()
    {
        $queue = new \SplQueue();
        $queue[] = 'foo';
        $queue[] = 'baz';
        $queue[] = 'bar';
        $d = new BatchSizeDivisor(2);
        $batches = $d->createBatches($queue);
        $this->assertEquals(array(array('foo', 'baz'), array('bar')), $batches);
    }
}
