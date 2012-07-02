<?php

namespace /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\Common\Exception;

use /* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Exception\BatchTransferException;

class BatchTransferExceptionTest extends \/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Tests\/* Replaced /* Replaced /* Replaced Guzzle */ */ */TestCase
{
    public function testContainsBatch()
    {
        $e = new \Exception('Baz!');
        $t = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchTransferInterface');
        $d = $this->getMock('/* Replaced /* Replaced /* Replaced Guzzle */ */ */\Common\Batch\BatchDivisorInterface');
        $transferException = new BatchTransferException(array('foo'), array(1, 2), $e, $t, $d);
        $this->assertEquals(array('foo'), $transferException->getBatch());
        $this->assertSame($t, $transferException->getTransferStrategy());
        $this->assertSame($d, $transferException->getDivisorStrategy());
        $this->assertSame($e, $transferException->getPrevious());
        $this->assertEquals(array(1, 2), $transferException->getTransferredItems());
    }
}
