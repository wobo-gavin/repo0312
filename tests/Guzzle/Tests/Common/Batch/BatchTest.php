<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\Batch;
use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\BatchTransferException;

/**
 * @covers /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\Batch
 */
class BatchTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    private function getMockTransfer()
    {
        return $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchTransferInterface');
    }

    private function getMockDivisor()
    {
        return $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchDivisorInterface');
    }

    public function testAddsItemsToQueue()
    {
        $batch = new Batch($this->getMockTransfer(), $this->getMockDivisor());
        $this->assertSame($batch, $batch->add('foo'));
        $this->assertEquals(1, count($batch));
    }

    public function testFlushReturnsItems()
    {
        $transfer = $this->getMockTransfer();
        $transfer->expects($this->exactly(2))
            ->method('transfer');

        $divisor = $this->getMockDivisor();
        $divisor->expects($this->once())
            ->method('createBatches')
            ->will($this->returnValue(array(array('foo', 'baz'), array('bar'))));

        $batch = new Batch($transfer, $divisor);

        $batch->add('foo')->add('baz')->add('bar');
        $items = $batch->flush();

        $this->assertEquals(array('foo', 'baz', 'bar'), $items);
    }

    public function testThrowsExceptionContainingTheFailedBatch()
    {
        $called = 0;
        $originalException = new \Exception('Foo!');

        $transfer = $this->getMockTransfer();
        $transfer->expects($this->exactly(2))
            ->method('transfer')
            ->will($this->returnCallback(function () use (&$called, $originalException) {
                if (++$called == 2) {
                    throw $originalException;
                }
            }));

        $divisor = $this->getMockDivisor();
        $batch = new Batch($transfer, $divisor);

        // PHPunit clones objects before passing them to a callback.
        // Horrible hack to get around this!
        $queue = $this->readAttribute($batch, 'queue');

        $divisor->expects($this->once())
            ->method('createBatches')
            ->will($this->returnCallback(function ($batch) use ($queue) {
                foreach ($queue as $item) {
                    $items[] = $item;
                }
                return array_chunk($items, 2);
            }));

        $batch->add('foo')->add('baz')->add('bar')->add('bee')->add('boo');
        $this->assertEquals(5, count($batch));

        try {
            $items = $batch->flush();
            $this->fail('Expected exception');
        } catch (BatchTransferException $e) {
            $this->assertEquals($originalException, $e->getPrevious());
            $this->assertEquals(array('bar', 'bee'), array_values($e->getBatch()));
            $this->assertEquals(1, count($batch));
        }
    }
}