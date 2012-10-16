<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Batch;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchBuilder;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchBuilder
 */
class BatchBuilderTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    private function getMockTransfer()
    {
        return $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchTransferInterface');
    }

    private function getMockDivisor()
    {
        return $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchDivisorInterface');
    }

    private function getMockBatchBuilder()
    {
        return BatchBuilder::factory()
            ->transferWith($this->getMockTransfer())
            ->createBatchesWith($this->getMockDivisor());
    }

    public function testFactoryCreatesInstance()
    {
        $builder = BatchBuilder::factory();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchBuilder', $builder);
    }

    public function testAddsAutoFlush()
    {
        $batch = $this->getMockBatchBuilder()->autoFlushAt(10)->build();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\FlushingBatch', $batch);
    }

    public function testAddsExceptionBuffering()
    {
        $batch = $this->getMockBatchBuilder()->bufferExceptions()->build();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\ExceptionBufferingBatch', $batch);
    }

    public function testAddHistory()
    {
        $batch = $this->getMockBatchBuilder()->keepHistory()->build();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\HistoryBatch', $batch);
    }

    public function testAddsNotify()
    {
        $batch = $this->getMockBatchBuilder()->notify(function() {})->build();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\NotifyingBatch', $batch);
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\RuntimeException
     */
    public function testTransferStrategyMustBeSet()
    {
        $batch = BatchBuilder::factory()->createBatchesWith($this->getMockDivisor())->build();
    }

    /**
     * @expectedException /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\RuntimeException
     */
    public function testDivisorStrategyMustBeSet()
    {
        $batch = BatchBuilder::factory()->transferWith($this->getMockTransfer())->build();
    }

    public function testTransfersRequests()
    {
        $batch = BatchBuilder::factory()->transferRequests(10)->build();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchRequestTransfer', $this->readAttribute($batch, 'transferStrategy'));
    }

    public function testTransfersCommands()
    {
        $batch = BatchBuilder::factory()->transferCommands(10)->build();
        $this->assertInstanceOf('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Batch\BatchCommandTransfer', $this->readAttribute($batch, 'transferStrategy'));
    }
}
