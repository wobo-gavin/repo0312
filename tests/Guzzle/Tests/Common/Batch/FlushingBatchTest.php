<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\FlushingBatch;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\Batch;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\FlushingBatch
 */
class FlushingBatchTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testFlushesWhenSizeMeetsThreshold()
    {
        $t = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchTransferInterface', array('transfer'));
        $d = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchDivisorInterface', array('createBatches'));

        $batch = new Batch($t, $d);
        $queue = $this->readAttribute($batch, 'queue');

        $d->expects($this->exactly(2))
            ->method('createBatches')
            ->will($this->returnCallback(function () use ($queue) {
                $items = array();
                foreach ($queue as $item) {
                    $items[] = $item;
                }
                return array($items);
            }));

        $t->expects($this->exactly(2))
            ->method('transfer');

        $flush = new FlushingBatch($batch, 2);
        $flush->add('foo')->add('baz')->add('bar')->add('bee')->add('boo');
        $this->assertEquals(1, count($flush));
    }
}