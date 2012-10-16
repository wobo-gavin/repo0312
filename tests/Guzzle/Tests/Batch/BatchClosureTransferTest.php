<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Batch;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchClosureTransfer;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchClosureTransfer
 */
class BatchClosureTransferTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    /**
     * @var /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchClosureTransfer The transfer fixture
     */
    protected $transferStrategy;

    /**
     * @var array|null An array for keeping track of items passed into the transfer closure
     */
    protected $itemsTransferred;

    protected function setUp()
    {
        $this->itemsTransferred = null;
        $itemsTransferred =& $this->itemsTransferred;

        $this->transferStrategy = new BatchClosureTransfer(function (array $batch) use (&$itemsTransferred) {
            $itemsTransferred = $batch;
            return;
        });
    }

    public function testTransfersBatch()
    {
        $batchedItems = array('foo', 'bar', 'baz');
        $this->transferStrategy->transfer($batchedItems);

        $this->assertEquals($batchedItems, $this->itemsTransferred);
    }

    public function testTransferBailsOnEmptyBatch()
    {
        $batchedItems = array();
        $this->transferStrategy->transfer($batchedItems);

        $this->assertNull($this->itemsTransferred);
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\InvalidArgumentException
     */
    public function testEnsuresCallableIsCallable()
    {
        $foo = new BatchClosureTransfer('uh oh!');
    }
}
