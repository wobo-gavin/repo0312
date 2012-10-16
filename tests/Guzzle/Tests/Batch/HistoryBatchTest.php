<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Batch;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\HistoryBatch;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\Batch;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\HistoryBatch
 */
class HistoryBatchTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testMaintainsHistoryOfItemsAddedToBatch()
    {
        $batch = new Batch(
            $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchTransferInterface'),
            $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchDivisorInterface')
        );

        $history = new HistoryBatch($batch);
        $history->add('foo')->add('baz');
        $this->assertEquals(array('foo', 'baz'), $history->getHistory());
        $history->clearHistory();
        $this->assertEquals(array(), $history->getHistory());
    }
}
